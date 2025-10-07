<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class BcvController extends Controller
{
    public function obtenerTasa()
    {
        // Cachear la tasa por 30 minutos
        $tasa = Cache::remember('tasa_bcv', 1800, function () {
            // API 1: DolarAPI (más confiable)
            try {
                $response = Http::timeout(10)->get('https://ve.dolarapi.com/v1/dolares/oficial');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['promedio'])) {
                        return [
                            'valor' => floatval($data['promedio']),
                            'fecha' => $data['fechaActualizacion'] ?? date('Y-m-d H:i:s'),
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error API DolarAPI: ' . $e->getMessage());
            }

            // API 2: PyDolarVe
            try {
                $response = Http::timeout(10)->get('https://pydolarve.org/api/v1/dollar?page=bcv');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['monitors']['usd']['price'])) {
                        return [
                            'valor' => floatval($data['monitors']['usd']['price']),
                            'fecha' => $data['monitors']['usd']['last_update'] ?? date('Y-m-d H:i:s'),
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error API PyDolarVe: ' . $e->getMessage());
            }

            // API 3: ExchangeRate-API (respaldo internacional)
            try {
                $response = Http::timeout(10)->get('https://api.exchangerate-api.com/v4/latest/USD');
                
                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['rates']['VES'])) {
                        return [
                            'valor' => floatval($data['rates']['VES']),
                            'fecha' => date('Y-m-d H:i:s'),
                        ];
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Error API ExchangeRate: ' . $e->getMessage());
            }

            // Valor por defecto si todas las APIs fallan
            \Log::warning('Todas las APIs de tasa BCV fallaron, usando valor por defecto');
            return [
                'valor' => 50.00,
                'fecha' => date('Y-m-d H:i:s'),
                'error' => 'No se pudo obtener la tasa actual. Usando valor por defecto.'
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $tasa
        ]);
    }

    public function limpiarCache()
    {
        Cache::forget('tasa_bcv');
        return response()->json([
            'success' => true,
            'message' => 'Cache de tasa BCV limpiado'
        ]);
    }
}
