<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BcvApiController extends Controller
{
    public function getTasa()
    {
        try {
            // URL de la API de DolarVzla
            $response = Http::get('https://api.dolarvzla.com/public/exchange-rate');

            if ($response->successful()) {
                $data = $response->json();
                // Verificamos que la respuesta tenga la estructura esperada
                if (isset($data['current']['usd'])) {
                    return response()->json(['precio' => $data['current']['usd']]);
                }
            }

            // Si la API falla o la estructura de datos es incorrecta, devolvemos un error
            return response()->json(['error' => 'No se pudo obtener la tasa del BCV.'], 500);

        } catch (\Exception $e) {
            // Si hay una excepción en la conexión, devolvemos un error
            return response()->json(['error' => 'Error de conexión con la API del BCV.'], 503);
        }
    }
}
