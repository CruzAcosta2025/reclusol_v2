<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use Illuminate\Http\Request;
//use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RequerimientoController extends Controller
{
    /*-------------------------------------------------
    |  Mostrar formulario
    *------------------------------------------------*/
    public function mostrar()
    {
        return view('requerimientos.requerimiento');
    }

    public function index()
    {
        return view('requerimientos.filtrar');
    }

    /*-------------------------------------------------
    |  Guardar requerimiento
    *------------------------------------------------*/
    public function store(Request $request)
    {
        /* ---------- 1. VALIDACIÓN ---------- */
        $validated = $request->validate([
            'area_solicitante'           => 'required|string|max:50',
            'sucursal'                   => 'required|string|max:50',
            'cliente'                    => 'required|string|max:50',
            'cargo_solicitado'           => 'required|string|max:50',
            'cantidad_requerida'         => 'required|integer|min:1|max:255',
            'fecha_limite'               => 'required|date|after_or_equal:today',
            'edad_minima'                => 'required|integer|min:18|max:65',
            'edad_maxima'                => 'required|integer|min:18|max:65',
            'requiere_licencia_conducir' => 'boolean',
            'requiere_sucamec'           => 'boolean',
            'nivel_estudios'             => 'required|string|max:50',
            'experiencia_minima'         => 'required|string|max:50',
            'requisitos_adicionales'     => 'nullable|string',
            'validado_rrhh'              => 'boolean',
            'escala_remunerativa'        => 'required|string|max:10',
            'prioridad'                  => 'nullable|string|max:50',
        ]);

        /* ---------- 2. TRANSFORMAR CHECKBOX ---------- */
        $validated['requiere_licencia_conducir'] = $request->boolean('requiere_licencia_conducir');
        $validated['requiere_sucamec']           = $request->boolean('requiere_sucamec');
        $validated['validado_rrhh']              = $request->boolean('validado_rrhh');

        try {
            $requerimiento = DB::transaction(
                fn() =>
                Requerimiento::create($validated)
            );

            Log::info('Requerimiento guardado', $requerimiento->toArray());
            return back()->with('success', '¡Requerimiento creado con éxito!');
        } catch (\Throwable $e) {
            Log::error('Error al guardar requerimiento', ['message' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Error al guardar. Inténtalo de nuevo.']);
        }
    }

    public function filtrar(Request $request)
    {
        $query = Requerimiento::query();

        // Filtros dinámicos
        if ($request->filled('sucursal')) {
            $query->where('sucursal', $request->sucursal);
        }

        if ($request->filled('cliente')) {
            $query->where('cliente', $request->cliente);
        }

        if ($request->filled('area_solicitante')) {
            $query->where('area_solicitante', $request->area_solicitante);
        }


        if ($request->filled('cargo_solicitado')) {
            $query->where('cargo_solicitado', $request->cargo_solicitado);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('buscar')) {
            $search = $request->buscar;
            $query->where(function ($q) use ($search) {
                $q->where('cargo_solicitado', 'like', "%{$search}%")
                    ->orWhere('requisitos_adicionales', 'like', "%{$search}%");
            });
        }

        // Ordenar
        $query->orderBy('created_at', 'desc');

        // Paginación
        $requerimientos = $query->paginate(15)->withQueryString();

        return view('requerimientos.filtrar', compact('requerimientos'));
    }

    public function edit(Requerimiento $requerimiento)
    {
        // resources/views/postulantes/partials/form-edit.blade.php
        return view('requerimientos.partials.form-edit', compact('requerimiento'));
    }

    /**
     * Valida y actualiza el postulante.
     */
    public function update(Request $request, Requerimiento $requerimiento)
    {
        // 1. Validar sólo los campos editables
        $data = $request->validate([
            'area_solicitante'           => 'required|string|max:50',
            'sucursal'                   => 'required|string|max:50',
            'cliente'                    => 'required|string|max:50',
            'cargo_solicitado'           => 'required|string|max:50',
            'cantidad_requerida'         => 'required|integer|min:1|max:255',
            'fecha_limite'               => 'required|date|after_or_equal:today',
            'edad_minima'                => 'required|integer|min:18|max:65',
            'edad_maxima'                => 'required|integer|min:18|max:65',
            'requiere_licencia_conducir' => 'boolean',
            'requiere_sucamec'           => 'boolean',
            'nivel_estudios'             => 'required|string|max:50',
            'experiencia_minima'         => 'required|string|max:50',
            'requisitos_adicionales'     => 'nullable|string',
            //'validado_rrhh'              => 'boolean',
            //'escala_remunerativa'        => 'required|string|max:10',
            'prioridad'                  => 'nullable|string|max:50',
        ]);

        // 2. Actualizar
        try {
            $requerimiento->update($data);
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar postulante', [
                'id'    => $requerimiento->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar. Intenta de nuevo.'
            ], 500);
        }
    }

    /**
     * Elimina un postulante.
     */
    public function destroy(Requerimiento $requerimiento)
    {
        try {
            $requerimiento->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar postulante', [
                'error' => $e->getMessage(),
                'id'    => $requerimiento->id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar. Intenta de nuevo.'
            ], 500);
        }
    }
}
