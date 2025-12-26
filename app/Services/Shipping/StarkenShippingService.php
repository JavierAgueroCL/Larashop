<?php

namespace App\Services\Shipping;

use App\Models\StarkenCity;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StarkenShippingService
{
    protected $baseUrl;
    protected $rut;
    protected $clave;
    protected $token;
    protected $originCityCode;

    public function __construct()
    {
        $this->baseUrl = config('services.starken.base_url');
        $this->rut = config('services.starken.rut');
        $this->clave = config('services.starken.clave');
        $this->token = config('services.starken.token');
        $this->originCityCode = config('services.starken.origin_code'); // Ensure this is set in .env as integer ID
    }

    public function getRates(string $comunaName, float $weight, float $width, float $height, float $depth)
    {
        // 1. Find Starken Destination Code
        // Normalize name: remove accents, uppercase
        // Starken names are usually uppercase.
        // Simple fuzzy search or direct match.
        $starkenCity = StarkenCity::where('comuna_name', 'LIKE', '%' . strtoupper($comunaName) . '%')->first();

        if (!$starkenCity) {
            Log::warning("Starken: Comuna '$comunaName' not found in mapping.");
            return null;
        }

        $url = $this->baseUrl . '/consultarTarifas';

        $payload = [
            "codigoCiudadOrigen" => (int) $this->originCityCode,
            "codigoCiudadDestino" => (int) $starkenCity->city_code, // Starken uses City Code for destination in example payload? Or Comuna? 
            // The prompt says "Para obtener las ciudades de destinos y hacer el match... y asi puder obtener el codigoCiudadDestino".
            // So we send codigoCiudad as destination.
            "codigoAgenciaDestino" => 0,
            "codigoAgenciaOrigen" => 0,
            "alto" => (int) $height,
            "ancho" => (int) $width,
            "largo" => (int) $depth,
            "kilos" => $weight,
            "cuentaCorriente" => "",
            "cuentaCorrienteDV" => "",
            "rutCliente" => $this->rut
        ];

        try {
            $response = Http::withHeaders([
                'rut' => $this->rut,
                'clave' => $this->clave,
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
            ])->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Starken API Error: ' . $response->body(), ['payload' => $payload]);
            return null;

        } catch (\Exception $e) {
            Log::error('Starken Connection Error: ' . $e->getMessage());
            return null;
        }
    }
}
