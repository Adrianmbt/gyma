<?php

namespace App\Http\Controllers;

use App\Models\Entrenador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EntrenadorController extends Controller
{
    public function index()
    {
        return response()->json(['success' => true, 'data' => Entrenador::all()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => 'required|string|unique:entrenadores,cedula',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|unique:entrenadores,correo',
            'especialidad' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/uploads');
            $validated['foto'] = Storage::url($path);
        } else {
            $validated['foto'] = '/uploads/default.png';
        }

        $entrenador = Entrenador::create($validated);
        return response()->json(['success' => true, 'message' => 'Entrenador registrado con éxito.', 'data' => $entrenador], 201);
    }

    public function show(Entrenador $entrenador)
    {
        return response()->json(['success' => true, 'data' => $entrenador]);
    }

    public function update(Request $request, Entrenador $entrenador)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => ['required', 'string', Rule::unique('entrenadores')->ignore($entrenador->id)],
            'telefono' => 'nullable|string|max:20',
            'correo' => ['nullable', 'email', Rule::unique('entrenadores')->ignore($entrenador->id)],
            'especialidad' => 'nullable|string|max:255',
            'foto' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($entrenador->foto && $entrenador->foto !== '/uploads/default.png') {
                Storage::delete(str_replace('/storage', 'public', $entrenador->foto));
            }
            $path = $request->file('foto')->store('public/uploads');
            $validated['foto'] = Storage::url($path);
        }

        $entrenador->update($validated);
        return response()->json(['success' => true, 'message' => 'Entrenador actualizado con éxito.', 'data' => $entrenador]);
    }

    public function destroy(Entrenador $entrenador)
    {
        try {
            if ($entrenador->foto && $entrenador->foto !== '/uploads/default.png') {
                Storage::delete(str_replace('/storage', 'public', $entrenador->foto));
            }
            $entrenador->delete();
            return response()->json(['success' => true, 'message' => 'Entrenador eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el entrenador.'], 500);
        }
    }
}
