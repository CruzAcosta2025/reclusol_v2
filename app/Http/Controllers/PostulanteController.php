<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use App\Notifications\PostulanteEnListaNegra;
use App\Notifications\NuevoPostulanteRegistrado;


class PostulanteController extends Controller
{
    /*-------------------------------------------------
    |  Mostrar formulario GET
    *------------------------------------------------*/
    public function verificarListaNegra($dni)
    {
        try {
            $listaNegra = collect(DB::select(
                'EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre',
                [
                    'dni' => $dni,
                    'nombre' => null
                ]
            ));

            if ($listaNegra->isNotEmpty()) {
                // Si el SP devuelve campos como "motivo", puedes mostrarlo
                $mensaje = 'Este postulante está en la lista negra.';
                if (isset($listaNegra[0]->motivo)) {
                    $mensaje .= ' Motivo: ' . $listaNegra[0]->motivo;
                }
                return response()->json(['enListaNegra' => true, 'mensaje' => $mensaje]);
            } else {
                return response()->json(['enListaNegra' => false]);
            }
        } catch (\Exception $e) {
            // Responde el error para debug rápido
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function formExterno()
    {
        $departamentos = DB::connection('si_solmar')
            ->table('ADMI_DEPARTAMENTO')
            ->select('DEPA_CODIGO', 'DEPA_DESCRIPCION')
            ->where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get()
            ->keyBy('DEPA_CODIGO');


        $provincias = DB::connection('si_solmar')
            ->table('ADMI_PROVINCIA')
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->where('PROVI_VIGENCIA', 'SI')
            ->orderBy('PROVI_DESCRIPCION')
            ->get()
            ->keyBy('PROVI_CODIGO');

        $distritos = DB::connection('si_solmar')
            ->table('ADMI_DISTRITO')
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->where('DIST_VIGENCIA', 'SI')
            ->orderBy('DIST_DESCRIPCION')
            ->get()
            ->keyBy('DIST_CODIGO');

        $tipoCargos = DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')
            ->get()
            ->keyBy('CODI_TIPO_CARG');

        $cargos = DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO', 'TIPO_CARG')
            ->where('CARG_VIGENCIA', 'SI')
            ->get()
            ->keyBy('CODI_CARG');

        $nivelEducativo = DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get();

        $estadoCivil = DB::connection('si_solmar')
            ->table('ADMI_ESTADO_CIVIL')
            ->select('ESCI_CODIGO', 'ESCI_DESCRIPCION')
            ->get();

        return view('postulantes.registroExterno', compact(
            'departamentos',
            'provincias',
            'distritos',
            'cargos',
            'tipoCargos',
            'nivelEducativo',
            'estadoCivil'
        ));
    }

    public function formInterno()
    {
        $departamentos = DB::connection('si_solmar')
            ->table('ADMI_DEPARTAMENTO')
            ->select('DEPA_CODIGO', 'DEPA_DESCRIPCION')
            ->where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get()
            ->keyBy('DEPA_CODIGO');


        $provincias = DB::connection('si_solmar')
            ->table('ADMI_PROVINCIA')
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->where('PROVI_VIGENCIA', 'SI')
            ->orderBy('PROVI_DESCRIPCION')
            ->get()
            ->keyBy('PROVI_CODIGO');

        $distritos = DB::connection('si_solmar')
            ->table('ADMI_DISTRITO')
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->where('DIST_VIGENCIA', 'SI')
            ->orderBy('DIST_DESCRIPCION')
            ->get()
            ->keyBy('DIST_CODIGO');

        $tipoCargos = DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')
            ->get()
            ->keyBy('CODI_TIPO_CARG');

        $cargos = DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO', 'TIPO_CARG')
            ->where('CARG_VIGENCIA', 'SI')
            ->get()
            ->keyBy('CODI_CARG');

        $nivelEducativo = DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get();

        $estadoCivil = DB::connection('si_solmar')
            ->table('ADMI_ESTADO_CIVIL')
            ->select('ESCI_CODIGO', 'ESCI_DESCRIPCION')
            ->get();

        return view('postulantes.registroInterno', compact(
            'departamentos',
            'provincias',
            'distritos',
            'cargos',
            'tipoCargos',
            'nivelEducativo',
            'estadoCivil'
        ));
    }

    /*-------------------------------------------------
    |  Guardar postulante
    *------------------------------------------------*/
    public function storeInterno(Request $request)
    {
        /* ---------- 1. VALIDAR ---------- */
        $validated = $request->validate([
            'fecha_postula'      => 'required|date',
            'dni'                => 'required|string|size:8|unique:postulantes,dni',
            'nombres'            => 'required|string|max:50',
            'apellidos'          => 'required|string|max:50',
            'fecha_nacimiento'   => 'required|date',
            'edad'               => 'required|integer|min:18|max:120',
            'departamento'       => 'required|string|max:50',
            'provincia'          => 'required|string|max:50',
            'distrito'           => 'required|string|max:50',
            'nacionalidad'       => 'required|string|max:50',
            'grado_instruccion'  => 'required|string|max:50',
            'celular'            => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'tipo_cargo'         => 'required|string|max:50',
            'cargo'              => 'required|string|max:50',
            'experiencia_rubro'  => 'required|string|max:50',
            'sucamec'            => 'required|string|max:10',
            'carne_sucamec'      => 'required|string|max:10',
            'licencia_arma'      => 'required|string|max:10',
            'licencia_conducir'  => 'required|string|max:10',
            'servicio_militar'   => 'nullable|string|max:15',
            'estado_civil'       => 'nullable|string|max:15',
            // Archivos  (máx. 5 MB — ya los validas en el front)
            'cv'  => 'required|file|max:5120',
            'cul' => 'required|file|max:5120',

            // Checkbox términos
            //'terms' => 'accepted',
        ]);

        /* ---------- 2. GUARDAR ARCHIVOS ---------- */
        // Ruta a la carpeta compartida del servidor
        $dni = $request->input('dni');
        $basePath = '\\\\192.168.10.5\\sicos\\RECLUSOL\\DOCUMENTOS\\POSTULANTES\\' . $dni . '\\';
        // Nota: Usa doble backslash \\ en PHP para rutas de red

        // Crea la carpeta si no existe
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
        }

        // Nombres de archivos únicos
        $cvFileName  = 'cv_'  . time() . '.' . $request->file('cv')->getClientOriginalExtension();
        $culFileName = 'cul_' . time() . '.' . $request->file('cul')->getClientOriginalExtension();

        // Mueve los archivos a la carpeta de red
        $request->file('cv')->move($basePath, $cvFileName);
        $request->file('cul')->move($basePath, $culFileName);

        // Guarda solo la ruta relativa en base de datos (opcional, para mostrar luego)
        $validated['cv']  = $dni . '/' . $cvFileName;
        $validated['cul'] = $dni . '/' . $culFileName;

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
        Log::info('Postulante guardado', $postulante->toArray());

        return back()->with('success', 'Información guardada');
    }


    public function storeExterno(Request $request)
    {

        Log::info('Iniciando registro externo de postulante', [
            'ip' => $request->ip(),
            'dni' => $request->input('dni')
        ]);

        /* ---------- 1. VALIDAR ---------- */
        $validated = $request->validate([
            'fecha_postula'      => 'required|date',
            'dni'                => 'required|string|size:8|unique:postulantes,dni',
            'nombres'            => 'required|string|max:50',
            'apellidos'          => 'required|string|max:50',
            'fecha_nacimiento'   => 'required|date',
            'edad'               => 'required|integer|min:18|max:120',
            'departamento'       => 'required|string|max:50',
            'provincia'          => 'required|string|max:50',
            'distrito'           => 'required|string|max:50',
            'nacionalidad'       => 'required|string|max:50',
            'grado_instruccion'  => 'required|string|max:50',
            'celular'            => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'tipo_cargo'         => 'required|string|max:50',
            'cargo'              => 'required|string|max:50',
            'experiencia_rubro'  => 'required|string|max:50',
            'sucamec'            => 'required|string|max:10',
            'carne_sucamec'      => 'required|string|max:10',
            'licencia_arma'      => 'required|string|max:10',
            'licencia_conducir'  => 'required|string|max:10',
            'servicio_militar'   => 'nullable|string|max:15',
            'estado_civil'       => 'nullable|string|max:15',
            // Archivos  (máx. 5 MB — ya los validas en el front)
            'cv'  => 'required|file|max:5120',
            'cul' => 'required|file|max:5120',
        ]);
        Log::info('Validación exitosa', $validated);

        /* ---------- 2. GUARDAR ARCHIVOS ---------- */
        $dni = $request->input('dni');
        $basePath = '\\\\192.168.10.5\\sicos\\RECLUSOL\\DOCUMENTOS\\POSTULANTES\\' . $dni . '\\';
        if (!file_exists($basePath)) {
            mkdir($basePath, 0777, true);
            Log::info('Carpeta creada', ['path' => $basePath]);
        }
        // Nombres de archivos únicos
        $cvFileName  = 'cv_'  . time() . '.' . $request->file('cv')->getClientOriginalExtension();
        $culFileName = 'cul_' . time() . '.' . $request->file('cul')->getClientOriginalExtension();
        $request->file('cv')->move($basePath, $cvFileName);
        $request->file('cul')->move($basePath, $culFileName);
        Log::info('Archivos guardados', ['cv' => $cvFileName, 'cul' => $culFileName]);

        $validated['cv']  = $dni . '/' . $cvFileName;
        $validated['cul'] = $dni . '/' . $culFileName;
        unset($validated['terms']);

        /* ---------- 3. INSERTAR EN TRANSACCIÓN ---------- */
        try {
            $postulante = DB::transaction(
                fn() => Postulante::create($validated)
            );
            Log::info('Postulante creado', $postulante->toArray());
        } catch (\Throwable $e) {
            Log::error('Error al guardar postulante', [
                'error' => $e->getMessage(),
                'dni'   => $request->input('dni')
            ]);
            return back()
                ->withInput()
                ->withErrors([
                    'general' => 'Ocurrió un problema al guardar la postulación. Intenta de nuevo.'
                ]);
        }

        // ... Después de guardar el postulante
        $enListaNegra = collect(DB::select('EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre', [
            'dni' => $request->input('dni'),
            'nombre' => $request->input('nombres')
        ]));
        Log::info('Resultado lista negra', [
            'dni' => $request->input('dni'),
            'enListaNegra' => $enListaNegra->count()
        ]);

        $usuarios = User::all();

        if ($enListaNegra->count() > 0) {
            if (auth()->check()) {
                auth()->user()->notify(new PostulanteEnListaNegra($postulante));
            }
            // Enviar un flag a la vista para mostrar en el console
            return back()->with([
                'success' => 'Información guardada',
                'debug_console' => [
                    'mensaje' => '¡El postulante está en la lista negra!',
                    'dni' => $request->input('dni'),
                    'nombre' => $request->input('nombres'),
                    'notificado_a_usuario_id' => auth()->id()
                ]
            ]);
        } else {
            // Notifica si NO está en lista negra
            foreach ($usuarios as $user) {
                $user->notify(new NuevoPostulanteRegistrado($postulante));
            }
        }
        /* ---------- 4. ÉXITO ---------- */
        Log::info('Fin del registro externo');

        return back()->with('success', 'Información guardada');
    }


    public function ver()
    {
        return view('postulantes.selection');
    }


    public function filtrar(Request $request)
    {
        //$query = Requerimiento::query();
        //$query = Postulante::with('estado');
        $query = Postulante::query();

        // Filtros dinámicos
        //if ($request->filled('sucursal')) {
        //  $query->where('sucursal', $request->sucursal);
        //}

        //if ($request->filled('cliente')) {
        //  $query->where('cliente', $request->cliente);
        //}

        // if ($request->filled('area_solicitante')) {
        //   $query->where('area_solicitante', $request->area_solicitante);
        //}

        if ($request->filled('tipo_cargo')) {
            $query->where('tipo_cargo', $request->tipo_cargo);
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }


        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }
        if ($request->filled('provincia')) {
            $query->where('provincia', $request->provincia);
        }
        if ($request->filled('distrito')) {
            $query->where('distrito', $request->distrito);
        }


        // Ordenar
        $query->orderBy('created_at', 'desc');

        // Paginación
        $postulantes = $query->paginate(15)->withQueryString();

        /*-------------------------------------------------
        |   Mapeo de nombres legibles
        -------------------------------------------------*/
        // Cargar catálogos

        $tipoCargos = DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')
            //>where('CARG_VIGENCIA', 'SI')
            ->get()
            ->keyBy('CODI_TIPO_CARG');

        $cargos = DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO', 'TIPO_CARG')
            ->where('CARG_VIGENCIA', 'SI')
            ->get()
            ->keyBy('CODI_CARG');

        $sucursales = DB::connection('si_solmar')
            ->table('SISO_SUCURSAL')
            ->select('SUCU_CODIGO', 'SUCU_DESCRIPCION')
            ->where('SUCU_VIGENCIA', 'SI')
            ->get()
            ->keyBy('SUCU_CODIGO');

        $departamentos = DB::connection('si_solmar')
            ->table('ADMI_DEPARTAMENTO')
            ->select('DEPA_CODIGO', 'DEPA_DESCRIPCION')
            ->where('DEPA_VIGENCIA', 'SI')
            ->orderBy('DEPA_DESCRIPCION')
            ->get()
            ->keyBy('DEPA_CODIGO');


        $provincias = DB::connection('si_solmar')
            ->table('ADMI_PROVINCIA')
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->where('PROVI_VIGENCIA', 'SI')
            ->orderBy('PROVI_DESCRIPCION')
            ->get()
            ->keyBy('PROVI_CODIGO');

        $distritos = DB::connection('si_solmar')
            ->table('ADMI_DISTRITO')
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->where('DIST_VIGENCIA', 'SI')
            ->orderBy('DIST_DESCRIPCION')
            ->get()
            ->keyBy('DIST_CODIGO');

        $nivelEducativo = DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get()
            ->keyBy('NIED_CODIGO');

        $estadoCivil = DB::connection('si_solmar')
            ->table('ADMI_ESTADO_CIVIL')
            ->select('ESCI_CODIGO', 'ESCI_DESCRIPCION')
            ->get()
            ->keyBy('ESCI_CODIGO');


        // Mapear nombres legibles con str_pad
        foreach ($postulantes as $r) {
            // Convertir a string sin ceros a la izquierda
            $codigoTipoCargo = (string) $r->tipo_cargo;
            $codigoCargo = (string)$r->cargo;
            $codigoSucursal = (string)$r->sucursal;
            $codigoDepartamento = (string)$r->departamento;
            $codigoProvincia = (string)$r->provincia;
            $codigoDistrito = (string)$r->distrito;
            $codigoNivel = (string)$r->grado_instruccion;
            $codigoEstado = (string)$r->estado_civil;

            // Buscar normalizado
            $r->tipo_cargo_nombre = $tipoCargos->get($codigoTipoCargo)?->DESC_TIPO_CARG ?? $r->tipo_cargo;
            $r->cargo_nombre = $cargos->get($codigoCargo)?->DESC_CARGO ?? $r->cargo;
            $r->sucursal_nombre = $sucursales->get($codigoSucursal)?->SUCU_DESCRIPCION ?? $r->sucursal;
            $r->departamento_nombre = $departamentos->get($codigoDepartamento)?->DEPA_DESCRIPCION ?? $r->departamento;
            $r->provincia_nombre = $provincias->get($codigoProvincia)?->PROVI_DESCRIPCION ?? $r->provincia;
            $r->distrito_nombre = $distritos->get($codigoDistrito)?->DIST_DESCRIPCION ?? $r->distrito;
            $r->nivel_nombre = $nivelEducativo->get($codigoNivel)?->NIED_DESCRIPCION ?? $r->grado_instruccion;
            $r->estado_nombre = $estadoCivil->get($codigoEstado)?->ESCI_DESCRIPCION ?? $r->estado_civil;
        }

        // Contar total general
        // $requerimientosProcesos = Postulante::where('estado', 1)->count();

        // Contar cubiertos
        //$requerimientosCubiertos = Postulante::where('estado', 2)->count(); // suponiendo que estado 2 = Cubierto

        // Contar cancelados
        // $requerimientosCancelados = Postulante::where('estado', 3)->count(); // estado 3 = Cancelado

        // Contar vencidos
        //$requerimientosVencidos = Postulante::where('estado', 4)->count(); // estado 4 = Vencido


        return view('postulantes.filtrar', compact(
            'postulantes',
            'tipoCargos',
            'cargos',
            'sucursales',
            'departamentos',
            'provincias',
            'distritos',
            'nivelEducativo',
            'estadoCivil'
            //'requerimientosProcesos',
            //'requerimientosCubiertos',
            //'requerimientosCancelados',
            //'requerimientosVencidos'
        ));
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
            'nombres'             => 'required|string|max:50',
            'apellidos'           => 'required|string|max:50',
            'dni'                 => ['required', 'string', 'size:8', Rule::unique('postulantes', 'dni')->ignore($postulante->id),],
            'edad'                => 'required|integer|min:18|max:120',
            'departamento'        => 'required|string|max:50',
            'provincia'           => 'required|string|max:50',
            'distrito'            => 'required|string|max:50',
            'celular'             => 'required|string|max:50',
            'celular_referencia'  => 'nullable|digits:9',
            'estado_civil'        => 'required|string|max:50',
            'nacionalidad'        => 'required|string|max:50',
            'tipo_cargo'        => 'required|string|max:50',
            'cargo'               => 'required|string|max:50',
            'fecha_postula'       => 'required|date',
            'experiencia_rubro'   => 'required|string|max:50',
            'sucamec'             => 'required|string|max:10',
            'grado_instruccion'   => 'required|string|max:50',
            'servicio_militar'    => 'required|string|max:15',
            'licencia_arma'       => 'required|string|max:10',
            'licencia_conducir'   => 'required|string|max:10',

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

    public function descargarArchivo($id, $tipo)
    {
        $postulante = Postulante::findOrFail($id);

        // Solo permitir 'cv' o 'cul'
        if (!in_array($tipo, ['cv', 'cul'])) {
            abort(404, 'Tipo de archivo no válido');
        }

        $archivo = $tipo === 'cv' ? $postulante->cv : $postulante->cul;

        // Asegúrate que la ruta no tenga '/' al principio
        $ruta = '\\\\192.168.10.5\\sicos\\RECLUSOL\\DOCUMENTOS\\POSTULANTES\\' . $archivo;

        // Verifica si el archivo existe
        if (!file_exists($ruta)) {
            abort(404, 'Archivo no encontrado');
        }

        // Descargar el archivo
        return Response::download($ruta, $tipo . '_' . $postulante->dni . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }
}
