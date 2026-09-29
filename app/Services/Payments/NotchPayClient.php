<?php

namespace App\Services\Payments;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class NotchPayClient
{
    private function request(): PendingRequest
    {
        $key = config('services.notchpay.key');

        if (! is_string($key) || $key === '') {
            throw new RuntimeException('NOTCHPAY_API_KEY is not configured.');
        }

        return Http::baseUrl((string) config('services.notchpay.base_url'))
            ->withHeaders(['Authorization' => $key])
            ->acceptJson()
            ->asJson()
            ->timeout(20);
    }

    /** @param array<string, mixed> $payload */
    public function initialize(array $payload): array
    {
        return $this->request()->post('/payments', $payload)->throw()->json();
    }

    /** @return array<string, mixed> */
    public function retrieve(string $reference): array
    {
        return $this->request()->get('/payments/'.urlencode($reference))->throw()->json();
    }
}
