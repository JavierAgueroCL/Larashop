<?php

namespace App\Services\Shipping;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChilexpressShippingService
{
    protected $baseUrl;
    protected $subscriptionKey;
    protected $originComuna;

    public function __construct()
    {
        $this->baseUrl = config('services.chilexpress.base_url');
        $this->subscriptionKey = config('services.chilexpress.subscription_key');
        $this->originComuna = config('services.chilexpress.origin_comuna');
    }

    /**
     * Calculate shipping rate.
     *
     * @param string $destinationComuna
     * @param float $weight Weight in kg
     * @param float $width Width in cm
     * @param float $height Height in cm
     * @param float $depth Depth (Length) in cm
     * @param float $declaredValue Value of content in CLP
     * @return array|null
     */
    public function getRates(string $destinationComuna, float $weight, float $width, float $height, float $depth, float $declaredValue = 1000)
    {
        // TODO: Ensure $destinationComuna matches Chilexpress "CountyCode" (e.g., 'SANTIAGO CENTRO', 'PROVIDENCIA'). 
        // If the API requires specific codes (e.g., numeric or abbreviations), a mapping layer will be needed.

        if (!$this->subscriptionKey) {
            Log::warning('Chilexpress subscription key is missing.');
            return null;
        }

        // Endpoint for Rating
        $url = $this->baseUrl . '/rates/courier';
        
        Log::info('Chilexpress: Fetching rates', ['url' => $url, 'origin' => $this->originComuna, 'destination' => $destinationComuna]);

        // Prepare Payload
        // Note: This structure matches standard Chilexpress JSON REST API for rating
        $payload = [
            'originCountyCode' => $this->originComuna,
            'destinationCountyCode' => $destinationComuna,
            'package' => [
                'weight' => number_format($weight, 1, '.', ''),
                'height' => number_format($height, 1, '.', ''),
                'width' => number_format($width, 1, '.', ''),
                'length' => number_format($depth, 1, '.', ''),
            ],
            'productType' => 3, // 3 = Encomienda
            'contentType' => 1, 
            'declaredWorth' => number_format($declaredValue, 0, '', ''),
            'deliveryTime' => 0 // 0 = All available services
        ];

        try {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                'Content-Type' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                Log::info('Chilexpress API Response:', $response->json());
                return $response->json();
            }

            Log::error('Chilexpress API Error: ' . $response->body(), ['payload' => $payload]);
            return null;

        } catch (\Exception $e) {
            Log::error('Chilexpress Connection Error: ' . $e->getMessage());
            return null;
        }
    }
}
