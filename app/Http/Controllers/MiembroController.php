<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MiembroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Miembro::query();

        // Búsqueda
        if ($request->has('search') && $request->input('search.value') != '') {
            $searchValue = $request->input('search.value');
            $query->where(function($q) use ($searchValue) {
                $q->where('nombre', 'LIKE', "%{$searchValue}%")
                  ->orWhere('cedula', 'LIKE', "%{$searchValue}%");
            });
        }
        
        // Conteo total de registros antes de la paginación
        $totalRecords = Miembro::count();
        $recordsFiltered = $query->count();

        // Paginación
        if ($request->has('start') && $request->has('length')) {
            $start = $request->input('start');
            $length = $request->input('length');
            if ($length != -1) {
                $query->offset($start)->limit($length);
            }
        }

        // Ordenamiento
        if ($request->has('order') && count($request->input('order'))) {
            $order = $request->input('order')[0];
            $columnIndex = $order['column'];
            $columnName = $request->input('columns')[$columnIndex]['data'];
            $direction = $order['dir'];
            $query->orderBy($columnName, $direction);
        } else {
            $query->orderBy('nombre', 'asc');
        }

        $miembros = $query->get();

        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalRecords),
            "recordsFiltered" => intval($recordsFiltered),
            "data" => $miembros
        ]);
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
            'cedula' => 'required|string|unique:miembros,cedula',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string',
            'contacto_emergencia_nombre' => 'nullable|string|max:255',
            'contacto_emergencia_telefono' => 'nullable|string|max:20',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // 2MB Max
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('public/uploads');
            $validated['foto'] = Storage::url($path);
        } else {
            $validated['foto'] = '/storage/uploads/default.png'; // Ruta a una imagen por defecto
        }
        
        // El estatus por defecto es 'activo' al crear un miembro
        $validated['estatus'] = 'activo';

        $miembro = Miembro::create($validated);

        return response()->json(['success' => true, 'message' => 'Miembro registrado con éxito.', 'data' => $miembro], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Miembro  $miembro
     * @return \Illuminate\Http\Response
     */
    public function show(Miembro $miembro)
    {
        // Carga la última suscripción del miembro
        $miembro->load('suscripciones');
        return response()->json(['success' => true, 'data' => $miembro]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Miembro  $miembro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Miembro $miembro)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'cedula' => ['required', 'string', Rule::unique('miembros')->ignore($miembro->id)],
            'telefono' => 'nullable|string|max:20',
            'correo' => 'nullable|email|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'direccion' => 'nullable|string',
            'contacto_emergencia_nombre' => 'nullable|string|max:255',
            'contacto_emergencia_telefono' => 'nullable|string|max:20',
            'estatus' => ['required', Rule::in(['activo', 'inactivo', 'suspendido'])],
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('foto')) {
            // Opcional: Eliminar la foto anterior si no es la de por defecto
            if ($miembro->foto && $miembro->foto !== '/uploads/default.png') {
                Storage::delete(str_replace('/storage', 'public', $miembro->foto));
            }
            $path = $request->file('foto')->store('public/uploads');
            $validated['foto'] = Storage::url($path);
        }

        $miembro->update($validated);

        return response()->json(['success' => true, 'message' => 'Miembro actualizado con éxito.', 'data' => $miembro]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Miembro  $miembro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Miembro $miembro)
    {
        // En lugar de borrar, cambiamos el estado a 'eliminado' o 'inactivo'
        // para mantener la integridad de los datos (soft delete).
        try {
            // Opcional: Eliminar la foto
            if ($miembro->foto && $miembro->foto !== '/storage/uploads/default.png') {
                Storage::delete(str_replace('/storage', 'public', $miembro->foto));
            }
            $miembro->delete();
            return response()->json(['success' => true, 'message' => 'Miembro eliminado con éxito.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'No se pudo eliminar el miembro. Es posible que tenga registros asociados.'], 500);
        }
    }

    public function buscarPorCedula($cedula)
    {
        $miembro = Miembro::where('numero_cedula', $cedula)->first();

        if ($miembro) {
            return response()->json($miembro);
        } else {
            return response()->json(['message' => 'Miembro no encontrado'], 404);
        }
    }
}
