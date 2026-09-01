<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Finance\InitializePaymentRequest;
use App\Http\Resources\Finance\PaymentResource;
use App\Repositories\Finance\PaymentRepositoryInterface;
use App\Services\Finance\PaymentService;
use Illuminate\Http\JsonResponse;
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
            // In a real app, return checkout URL from provider
            'checkout_url' => 'https://api.piiston.com/mock-checkout/'.$transaction->reference,
        ], 201);
    }

    public function verify(string $reference): JsonResponse
    {
        $transaction = $this->paymentRepository->findByReference($reference);
        if (! $transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(new PaymentResource($transaction));
    }
}
