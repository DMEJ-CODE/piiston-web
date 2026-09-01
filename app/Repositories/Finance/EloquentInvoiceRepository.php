<?php

namespace App\Repositories\Finance;

use App\Models\Finance\Invoice;
use Illuminate\Support\Collection;

class EloquentInvoiceRepository implements InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice
    {
        return Invoice::with('items')->find($id);
    }

    public function findByNumber(string $number): ?Invoice
    {
        return Invoice::where('invoice_number', $number)->first();
    }

    public function create(array $data): Invoice
    {
        return Invoice::create($data);
    }

    public function getCustomerInvoices(int $customerId): Collection
    {
        return Invoice::where('customer_id', $customerId)
            ->orderBy('issued_at', 'desc')
            ->get();
    }
}
