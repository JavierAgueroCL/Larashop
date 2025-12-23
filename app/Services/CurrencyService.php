<?php

namespace App\Services;

use CurrencyApi\CurrencyApi\CurrencyApiClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CurrencyService
{
    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.currencyapi.key');
        if ($this->apiKey) {
            $this->client = new CurrencyApiClient($this->apiKey);
        }
    }

    public function convert(float $amount, string $from, string $to): float
    {
        if ($from === $to) {
            return $amount;
        }

        // Cache rates for 1 hour
        $rates = Cache::remember('currency_rates_' . $from, 3600, function () use ($from) {
            if (!$this->client) {
                // Mock rates if no API key (Fallback logic)
                return [
                    'USD' => 0.0011, // Example: 1 CLP to USD
                    'CLP' => 900,    // Example: 1 USD to CLP
                    'EUR' => 0.0010,
                ];
            }

            try {
                $response = $this->client->latest(['base_currency' => $from]);
                $data = [];
                // API structure: ['data' => ['USD' => ['code' => 'USD', 'value' => 1.2], ...]]
                foreach ($response['data'] as $code => $currency) {
                    $data[$code] = $currency['value'];
                }
                return $data;
            } catch (\Exception $e) {
                Log::error('Currency API Error: ' . $e->getMessage());
                return [];
            }
        });

        $rate = $rates[$to] ?? 0;
        
        // Fallback for mock if rate not found in mock array
        if ($rate === 0 && !$this->client) {
             if ($from === 'USD' && $to === 'CLP') $rate = 900;
             if ($from === 'CLP' && $to === 'USD') $rate = 1/900;
        }

        return $amount * $rate;
    }
}
