<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\InitializePaymentRequest;
use App\Http\Resources\Finance\PaymentResource;
use App\Models\Finance\PaymentTransaction;
use App\Repositories\Finance\PaymentRepositoryInterface;
use App\Services\Finance\PaymentService;
use App\Services\Payments\NotchPayClient;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    protected $paymentRepository;

    public function __construct(PaymentService $paymentService, PaymentRepositoryInterface $paymentRepository)
    {
        $this->paymentService = $paymentService;
        $this->paymentRepository = $paymentRepository;
    }

    public function index(): JsonResponse
    {
        $transactions = $this->paymentRepository->getUserTransactions(Auth::id());

        return response()->json(PaymentResource::collection($transactions));
    }

    public function pay(InitializePaymentRequest $request): JsonResponse
    {
        $transaction = $this->paymentService->initialize($request->validated());

        return response()->json([
            'message' => 'Payment initialized',
            'transaction' => new PaymentResource($transaction),
            'checkout_url' => $transaction->checkout_url,
        ], 201);
    }

    public function verify(string $reference, NotchPayClient $notchPay): JsonResponse
    {
        $transaction = $this->paymentRepository->findByReference($reference);
        if (! $transaction || $transaction->payer_id !== Auth::id()) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        if ($transaction->notchpay_reference && config('services.notchpay.key')) {
            $payment = $notchPay->retrieve($transaction->notchpay_reference)['transaction'] ?? [];
            $this->paymentService->handleWebhook($transaction->reference, (string) ($payment['status'] ?? 'pending'));
            $transaction = $transaction->fresh();
        }

        return response()->json(new PaymentResource($transaction));
    }

    public function callback(Request $request, NotchPayClient $notchPay): JsonResponse
    {
        $reference = $request->string('reference')->toString();
        abort_unless($reference !== '', 422, 'Payment reference is required.');

        $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
        $transaction = PaymentTransaction::where('notchpay_reference', $reference)->orWhere('reference', $reference)->firstOrFail();
        $this->paymentService->handleWebhook($transaction->reference, strtoupper((string) ($payment['status'] ?? 'PENDING')) === 'COMPLETE' ? 'SUCCESS' : strtoupper((string) ($payment['status'] ?? 'PENDING')));

        return response()->json(new PaymentResource($transaction->fresh()));
    }

    public function webhook(Request $request, NotchPayClient $notchPay): JsonResponse
    {
        $secret = config('services.notchpay.webhook_secret');
        if (is_string($secret) && $secret !== '') {
            $signature = (string) $request->header('x-notchpay-signature');
            $expected = hash_hmac('sha256', $request->getContent(), $secret);
            abort_unless($signature !== '' && hash_equals($expected, $signature), 401);
        }

        $reference = (string) $request->input('data.reference', $request->input('reference'));
        if ($reference === '') {
            return response()->json(['received' => true]);
        }

        $payment = $notchPay->retrieve($reference)['transaction'] ?? [];
        $this->paymentService->handleWebhook($reference, (string) ($payment['status'] ?? $request->input('data.status', 'pending')));

        return response()->json(['received' => true]);
    }
}
