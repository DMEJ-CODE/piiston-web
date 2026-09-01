<?php

namespace App\Http\Controllers\Api\v1\Finance;

use App\Http\Controllers\Controller;
use App\Http\Resources\Finance\InvoiceResource;
use App\Repositories\Finance\InvoiceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository)
    {
        $this->invoiceRepository = $invoiceRepository;
    }

    public function index(): JsonResponse
    {
        $invoices = $this->invoiceRepository->getCustomerInvoices(Auth::id());

        return response()->json(InvoiceResource::collection($invoices));
    }

    public function show(int $id): JsonResponse
    {
        $invoice = $this->invoiceRepository->findById($id);
        if (! $invoice || $invoice->customer_id !== Auth::id()) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        return response()->json(new InvoiceResource($invoice));
    }
}
