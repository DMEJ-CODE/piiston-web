<?php

namespace App\Repositories\Finance;

use App\Models\Finance\Invoice;
use Illuminate\Support\Collection;

interface InvoiceRepositoryInterface
{
    public function findById(int $id): ?Invoice;

    public function findByNumber(string $number): ?Invoice;

    public function create(array $data): Invoice;

    public function getCustomerInvoices(int $customerId): Collection;
}
