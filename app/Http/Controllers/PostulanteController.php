<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PostulanteController extends Controller
{
    /*-------------------------------------------------
    |  Mostrar formulario
    *------------------------------------------------*/
    public function mostrar()
    {
        return view('postulantes.registroPrimario');
    }

    /*-------------------------------------------------
    |  Guardar postulante
    *------------------------------------------------*/
    public function store(Request $request)
    {
        /* ---------- 1. VALIDAR ---------- */
        $validated = $request->validate([
            'nombres'            => 'required|string|max:50',
            'apellidos'          => 'required|string|max:50',
            'dni'                => 'required|string|size:8|unique:postulantes,dni',
            'edad'               => 'required|integer|min:18|max:120',
            'ciudad'             => 'required|string|max:50',
            'distrito'           => 'required|string|max:50',
            'celular'            => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'estado_civil'       => 'required|string|max:50',
            'nacionalidad'       => 'required|string|max:50',
            'cargo'              => 'required|string|max:50',
            'fecha_postula'      => 'required|date',
            'experiencia_rubro'  => 'required|string|max:50',
            'sucamec'            => 'required|string|max:10',
            'grado_instruccion'  => 'required|string|max:50',
            'servicio_militar'   => 'required|string|max:15',
            'licencia_arma'      => 'required|string|max:10',
            'licencia_conducir'  => 'required|string|max:10',

            // Archivos  (máx. 5 MB — ya los validas en el front)
            'cv'  => 'required|file|max:5120',
            'cul' => 'required|file|max:5120',

            // Checkbox términos
            'terms' => 'accepted',
        ]);

        /* ---------- 2. GUARDAR ARCHIVOS ---------- */
        $validated['cv']  = $request->file('cv')
                                    ->store('postulantes/cv', 'public');

        $validated['cul'] = $request->file('cul')
                                    ->store('postulantes/cul', 'public');

        unset($validated['terms']); // no se guarda en la tabla

        /* ---------- 3. INSERTAR EN TRANSACCIÓN ---------- */
        try {
            $postulante = DB::transaction(fn () =>
                Postulante::create($validated)
            );
        } catch (\Throwable $e) {
            Log::error('Error al guardar postulante', [
                'error' => $e->getMessage(),
                'dni'   => $request->input('dni')
            ]);

            // Redirigir con mensaje de error para SweetAlert
            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Ocurrió un problema al guardar la postulación. Intenta de nuevo.'
                ]);
        }

        /* ---------- 4. ÉXITO ---------- */
        //Log::info('Postulante guardado', $postulante->toArray());

            return back()->with('success', 'Tu información ha sido guardada correctamente. Te estaremos comunicando');
    }
}
