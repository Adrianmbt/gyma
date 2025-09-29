<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Maneja la solicitud de inicio de sesión.
     */
    public function login(Request $request)
    {
        // 1. Validar los datos de entrada (usuario y clave)
        $request->validate([
            'usuario' => 'required|string',
            'clave' => 'required|string',
        ]);

        // 2. Buscar al usuario directamente por su nombre de usuario
        $user = \App\Models\Usuario::where('usuario', $request->usuario)->first();

        // 3. Verificar si el usuario existe y si la contraseña coincide
        if (!$user || !\Illuminate\Support\Facades\Hash::check($request->clave, $user->clave)) {
            // Si la autenticación falla, devolvemos un error
            throw ValidationException::withMessages([
                'usuario' => ['Usuario o clave incorrectos.'],
            ]);
        }

        // 4. Autenticar manualmente al usuario
        Auth::login($user);

        // 5. Si la autenticación es exitosa, regeneramos la sesión por seguridad
        $request->session()->regenerate();

        // 6. Devolvemos una respuesta JSON exitosa con los datos del usuario
        return response()->json([
            'success' => true,
            'message' => 'Inicio de sesión exitoso.',
            'user' => [
                'id' => $user->id,
                'nombre' => $user->nombre,
                'rol' => $user->rol,
            ]
        ]);
    }

    /**
     * Maneja la solicitud de cierre de sesión.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'message' => 'Sesión cerrada con éxito.']);
    }
}