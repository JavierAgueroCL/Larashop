<?php

namespace App\Console\Commands;

use App\Models\StarkenCity;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SyncStarkenCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'starken:sync-cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and sync cities from Starken API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Starken cities sync...');

        $url = config('services.starken.base_url') . '/listarCiudadesDestino';
        
        try {
            $response = Http::withHeaders([
                'rut' => config('services.starken.rut'),
                'clave' => config('services.starken.clave'),
                'Authorization' => 'Bearer ' . config('services.starken.token'),
                'Content-Type' => 'application/json',
                'Accept' => '*/*',
            ])->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['listaCiudadesDestino'])) {
                    $bar = $this->output->createProgressBar(count($data['listaCiudadesDestino']));
                    $bar->start();

                    // Truncate table first to ensure fresh data
                    StarkenCity::truncate();

                    foreach ($data['listaCiudadesDestino'] as $cityData) {
                        $regionCode = $cityData['codigoRegion'];
                        $cityCode = $cityData['codigoCiudad'];
                        $cityName = $cityData['nombreCiudad'];
                        
                        foreach ($cityData['listaComunas'] as $comunaData) {
                            StarkenCity::create([
                                'region_code' => $regionCode,
                                'city_code' => $cityCode,
                                'city_name' => $cityName,
                                'comuna_code' => $comunaData['codigoComuna'],
                                'comuna_name' => $comunaData['nombreComuna'],
                            ]);
                        }
                        $bar->advance();
                    }
                    
                    $bar->finish();
                    $this->newLine();
                    $this->info('Starken cities synced successfully.');
                } else {
                    $this->error('Invalid response structure.');
                    Log::error('Starken Sync Error: Invalid structure', ['response' => $data]);
                }
            } else {
                $this->error('Failed to fetch cities from Starken API. Status: ' . $response->status());
                Log::error('Starken Sync Error: ' . $response->body());
            }

        } catch (\Exception $e) {
            $this->error('Exception: ' . $e->getMessage());
            Log::error('Starken Sync Exception: ' . $e->getMessage());
        }
    }
}
