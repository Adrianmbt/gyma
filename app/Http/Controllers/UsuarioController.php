<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Reemplaza la consulta directa con Eloquent.
        // Nota: La seguridad (verificar si es admin) se manejará luego con Middleware.
        $usuarios = Usuario::select('id', 'nombre', 'cedula', 'telefono', 'usuario', 'rol')->orderBy('nombre')->get();
        return response()->json(['success' => true, 'data' => $usuarios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|unique:usuarios,cedula',
            'telefono' => 'nullable|string',
            'usuario' => 'required|string|unique:usuarios,usuario',
            'clave' => 'required|string|min:6',
            'rol' => ['required', Rule::in(['admin', 'recepcionista', 'entrenador'])],
        ]);

        $validated['clave'] = Hash::make($validated['clave']);

        $usuario = Usuario::create($validated);

        return response()->json(['success' => true, 'message' => 'Usuario registrado con éxito.', 'data' => $usuario], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(Usuario $usuario)
    {
        // Gracias al Route Model Binding de Laravel, el usuario ya se cargó automáticamente.
        return response()->json(['success' => true, 'data' => $usuario]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Usuario $usuario)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => ['required', 'string', Rule::unique('usuarios')->ignore($usuario->id)],
            'telefono' => 'nullable|string',
            'usuario' => ['required', 'string', Rule::unique('usuarios')->ignore($usuario->id)],
            'clave' => 'nullable|string|min:6', // La clave es opcional en la actualización
            'rol' => ['required', Rule::in(['admin', 'recepcionista', 'entrenador'])],
        ]);

        // Solo actualiza y hashea la contraseña si se proporcionó una nueva.
        if (!empty($validated['clave'])) {
            $validated['clave'] = Hash::make($validated['clave']);
        } else {
            unset($validated['clave']); // No actualices la clave si está vacía
        }

        $usuario->update($validated);

        return response()->json(['success' => true, 'message' => 'Usuario actualizado con éxito.', 'data' => $usuario]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Usuario  $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Usuario $usuario)
    {
        try {
            $usuario->delete();
            return response()->json(['success' => true, 'message' => 'Usuario eliminado con éxito.']);
        } catch (\Exception $e) {
            // Podríamos encontrar un error si, por ejemplo, el usuario está ligado
            // a otros registros y hay restricciones de clave foránea.
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el usuario. Es posible que esté asociado a otros registros.'], 500);
        }
    }
}
