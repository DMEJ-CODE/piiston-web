<?php

namespace App\Services\Finance;

use App\Models\Marketplace\Order;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateForOrder(Order $order): string
    {
        // Placeholder for PDF generation (e.g. using Snappy or DomPDF)
        $invoiceNumber = 'INV-'.str_pad($order->id, 6, '0', STR_PAD_LEFT);

        $content = "PIISTON INVOICE\n";
        $content .= "Number: $invoiceNumber\n";
        $content .= 'Customer: '.$order->buyer->name."\n";
        $content .= 'Total: '.$order->total_amount.' '.$order->currency->symbol."\n";

        $path = "invoices/{$invoiceNumber}.txt";
        Storage::disk('private')->put($path, $content);

        return Storage::disk('private')->url($path);
    }
}
