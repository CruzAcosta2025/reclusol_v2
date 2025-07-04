<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;


class PostulanteController extends Controller
{
    /*-------------------------------------------------
    |  Mostrar formulario
    *------------------------------------------------*/

    public function mostrar()
    {
        // Se busca resources/views/postulantes/registroPrimario.blade.php
        return view('postulantes.registroPrimario');
    }

    public function crear()
    {
        return view('postulantes.registroPrimario');
    }

    public function ver()
    {
        return view('postulantes.selection');
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
            $postulante = DB::transaction(
                fn() =>
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

    public function filtrar(Request $request)
    {
        // Construimos la consulta base
        $query = Postulante::query();

        // FILTROS dinámicos
        //if ($request->filled('sucursal')) {
        //  $query->where('sucursal', $request->sucursal);
        //}
        //if ($request->filled('cliente')) {
        //  $query->where('cliente', $request->cliente);
        //}
        //if ($request->filled('localidad')) {
        //  $query->where('localidad', $request->localidad);
        //}
        if ($request->filled('ciudad')) {
            $query->where('ciudad', $request->ciudad);
        }
        //if ($request->filled('estado')) {
        //  $query->where('estado', $request->estado);
        //}
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nombres', 'LIKE', "%$search%")
                    ->orWhere('apellidos', 'LIKE', "%$search%")
                    ->orWhere('dni', 'LIKE', "%$search%");
            });
        }

        $postulantes = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();


        // ORDENAMIENTO
        //$order = $request->get('order', 'reciente');
        //switch ($order) {
        //  case 'antiguo':
        //    $query->orderBy('created_at', 'asc');
        //  break;
        //case 'alfabetico':
        //  $query->orderBy('apellidos', 'asc')->orderBy('nombres', 'asc');
        //break;
        //default:
        //  $query->orderBy('created_at', 'desc');
        //break;
        //}

        // PAGINAR resultados
        //$postulantes = $query->paginate(15)->withQueryString();

        // Estadísticas por estado
        //$stats = [
        //  'en_proceso' => Postulante::where('estado', 'en_proceso')->count(),
        //'aprobados'  => Postulante::where('estado', 'aprobado')->count(),
        //'pendientes' => Postulante::where('estado', 'pendiente')->count(),
        //'rechazados' => Postulante::where('estado', 'rechazado')->count(),
        //];

        return view('postulantes.filtrar', compact('postulantes'));
    }

    /**
     * Devuelve el fragmento HTML con el formulario de edición.
     */
    public function edit(Postulante $postulante)
    {
        // resources/views/postulantes/partials/form-edit.blade.php
        return view('postulantes.partials.form-edit', compact('postulante'));
    }

    /**
     * Valida y actualiza el postulante.
     */
    public function update(Request $request, Postulante $postulante)
    {
        // 1. Validar sólo los campos editables
        $data = $request->validate([
            'nombres'  => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni'                 => ['required','string','size:8', Rule::unique('postulantes', 'dni')->ignore($postulante->id),],
            'edad'     => 'required|integer|min:18|max:120',
            'ciudad'   => 'required|string|max:50',
            'distrito' => 'required|string|max:50',
            'celular'  => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'estado_civil' => 'required|string|max:50',
            'nacionalidad' => 'required|string|max:50',
            'cargo'    => 'required|string|max:50',
            'fecha_postula'    => 'required|date',
            'experiencia_rubro' => 'required|string|max:50',
            'sucamec' => 'required|string|max:10',
            'grado_instruccion' => 'required|string|max:50',
            'servicio_militar' => 'required|string|max:15',
            'licencia_arma' => 'required|string|max:10',
            'licencia_conducir' => 'required|string|max:10',

            // si permites cambiar archivos, validalos y guárdalos aquí
        ]);

        // 2. Actualizar
        try {
            $postulante->update($data);
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar postulante', [
                'id'    => $postulante->id,
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
    public function destroy(Postulante $postulante)
    {
        try {
            $postulante->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar postulante', [
                'error' => $e->getMessage(),
                'id'    => $postulante->id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar. Intenta de nuevo.'
            ], 500);
        }
    }
}
