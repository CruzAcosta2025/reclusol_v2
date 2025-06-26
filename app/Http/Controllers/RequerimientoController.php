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
            $requerimiento = DB::transaction(fn () =>
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
}
