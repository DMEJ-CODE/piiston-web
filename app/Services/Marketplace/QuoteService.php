<?php

namespace App\Services\Marketplace;

use App\Models\Marketplace\Order;
use App\Models\Marketplace\PartQuote;
use Illuminate\Support\Facades\DB;

class QuoteService
{
    public function submitQuote(int $requestId, int $sellerId, array $data): PartQuote
    {
        $data['request_id'] = $requestId;
        $data['seller_id'] = $sellerId;
        $data['status'] = 'SENT';

        return PartQuote::create($data);
    }

    public function acceptQuote(int $quoteId): Order
    {
        return DB::transaction(function () use ($quoteId) {
            $quote = PartQuote::with('request')->findOrFail($quoteId);
            $quote->update(['status' => 'ACCEPTED']);

            // Close the request
            $quote->request->update(['status' => 'CLOSED']);

            // Create a Marketplace Order from the Quote
            $order = Order::create([
                'buyer_id' => $quote->request->user_id,
                'seller_id' => $quote->seller_id,
                'total_amount' => $quote->price,
                'currency_id' => $quote->currency_id,
                'payment_status' => 'PENDING',
                'order_status' => 'PENDING',
            ]);

            return $order;
        });
    }
}
