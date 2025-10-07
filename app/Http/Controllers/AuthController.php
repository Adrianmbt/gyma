<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Usuario;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'usuario' => 'required',
            'clave' => 'required',
        ]);

        $usuario = Usuario::where('usuario', $request->usuario)->first();

        if ($usuario && Hash::check($request->clave, $usuario->clave)) {
            session([
                'usuario_id' => $usuario->id,
                'usuario_nombre' => $usuario->nombre,
                'usuario_rol' => $usuario->rol,
            ]);

            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Usuario o clave incorrectos.'
        ], 401);
    }

    public function logout()
    {
        session()->flush();
        return response()->json(['success' => true]);
    }
}
