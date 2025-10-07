<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function listar()
    {
        $usuarios = Usuario::orderBy('nombre', 'asc')->get();
        return response()->json(['success' => true, 'data' => $usuarios]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:150',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula,' . $request->usuario_id,
            'usuario' => 'required|string|max:50|unique:usuarios,usuario,' . $request->usuario_id,
            'telefono' => 'nullable|string|max:30',
            'rol' => 'required|in:admin,supervisor,recepcionista',
        ]);

        $data = $request->only(['nombre', 'cedula', 'telefono', 'usuario', 'rol']);

        if ($request->filled('clave')) {
            $data['clave'] = Hash::make($request->clave);
        }

        if ($request->usuario_id) {
            $usuario = Usuario::findOrFail($request->usuario_id);
            $usuario->update($data);
            $message = 'Usuario actualizado con éxito.';
        } else {
            if (!$request->filled('clave')) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña es requerida para nuevos usuarios.'
                ], 400);
            }
            Usuario::create($data);
            $message = 'Usuario registrado con éxito.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function show($id)
    {
        $usuario = Usuario::findOrFail($id);
        return response()->json(['success' => true, 'data' => $usuario]);
    }

    public function destroy($id)
    {
        Usuario::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Usuario eliminado.']);
    }
}
