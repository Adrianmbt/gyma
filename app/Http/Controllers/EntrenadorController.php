<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrenador;

class EntrenadorController extends Controller
{
    public function listar()
    {
        $entrenadores = Entrenador::orderBy('nombre_completo', 'asc')->get();
        return response()->json(['success' => true, 'data' => $entrenadores]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:200',
            'numero_cedula' => 'required|string|max:20|unique:entrenadores,numero_cedula,' . $request->entrenador_id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'especialidad' => 'nullable|string|max:150',
            'costo_mensual' => 'required|numeric|min:0',
        ]);

        $data = $request->only([
            'nombre_completo', 'numero_cedula', 'telefono',
            'email', 'especialidad', 'costo_mensual'
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('uploads', 'public');
            $data['ruta_foto'] = $path;
        }

        if ($request->entrenador_id) {
            Entrenador::findOrFail($request->entrenador_id)->update($data);
            $message = 'Entrenador actualizado con éxito.';
        } else {
            $data['estatus'] = 'activo';
            $data['ruta_foto'] = $data['ruta_foto'] ?? 'uploads/default.png';
            Entrenador::create($data);
            $message = 'Entrenador registrado con éxito.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    public function show($id)
    {
        $entrenador = Entrenador::findOrFail($id);
        return response()->json(['success' => true, 'data' => $entrenador]);
    }

    public function destroy($id)
    {
        Entrenador::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Entrenador eliminado.']);
    }
}
