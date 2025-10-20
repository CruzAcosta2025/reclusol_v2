<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use App\Models\User;
use App\Models\EstadoRequerimiento;
use App\Models\Sucursal;
use App\Models\TipoCargo;
use App\Models\Cargo;
use App\Models\PrioridadRequerimiento;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
//use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\NuevoRequerimientoCreado;


class RequerimientoController extends Controller
{
    /*-------------------------------------------------
    |  Mostrar formulario
    *------------------------------------------------*/
    public function mostrar()
    {
        $estados = EstadoRequerimiento::all();
        $prioridades = PrioridadRequerimiento::all();
        $sucursales =  Sucursal::forSelect();
        $tipoCargos = TipoCargo::forSelect();
        $cargos = Cargo::forSelect();


        // Clientes (base CONTROLCLIENTES2018)
        $clientes = collect(DB::connection('sqlsrv')->select('EXEC dbo.SP_LISTAR_CLIENTES'));


        return view('requerimientos.requerimiento', compact('estados', 'prioridades', 
        'clientes','sucursales','tipoCargos','cargos'));
    }


    public function sedesPorCliente(Request $request)
    {
        $codigo = $request->input('codigo_cliente');

        $sedes = collect(DB::select(
            'EXEC USP_SICOS_2024_LISTAR_SEDES_X_CLIENTE ?, ?, ?',
            [$codigo, '', 0]
        ));

        return response()->json($sedes);
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
            // DATOS AUTOMÁTICOS (se agregan luego, no validar)
            // 'user_id' => ...,
            // 'cargo_usuario' => ...,
            // 'fecha_solicitud' => ...,

            'area_solicitante'     => 'nullable|string|max:50',
            'departamento'         => 'nullable|string|max:50',
            'provincia'            => 'nullable|string|max:50',
            'distrito'             => 'nullable|string|max:50',
            'sucursal'             => 'required|string|max:50',
            'cliente'              => 'required|string|max:50',
            'sede'                 => 'nullable|string|max:50',
            'tipo_personal'        => 'required|string|max:50',
            'tipo_cargo'           => 'required|string|max:50',
            'cargo_solicitado'     => 'required|string|max:50',
            'ubicacion_servicio'   => 'nullable|string|max:50',
            'fecha_inicio'         => 'required|date',
            'fecha_fin'            => 'required|date|after_or_equal:fecha_inicio',
            'urgencia'             => 'required|string|max:50',
            'cantidad_requerida'   => 'required|integer|min:1|max:255',
            'cantidad_masculino'   => 'required|integer|min:0|max:255',
            'cantidad_femenino'    => 'required|integer|min:0|max:255',
            'edad_minima'          => 'required|integer|min:18|max:65',
            'edad_maxima'          => 'required|integer|min:18|max:70',
            'experiencia_minima'   => 'required|string|max:50',
            'curso_sucamec_operativo'   => 'nullable|string|max:50',
            'carne_sucamec_operativo'   => 'nullable|string|max:50',
            'licencia_armas'            => 'nullable|string|max:50',
            'servicio_acuartelado'      => 'nullable|string|max:50',
            'grado_academico'           => 'required|string|max:50',
            'formacion_adicional'       => 'required|string|max:50',
            'requiere_licencia_conducir' => 'nullable|string|max:50',
            'validado_rrhh'             => 'boolean',
            'escala_remunerativa'       => 'required|string|max:50',
            'beneficios'                => 'required|string|max:50',
            //'prioridad'                 => 'nullable|exists:prioridad_requerimiento,id',
            'estado'                    => 'required|exists:estado_requerimiento,id',
            //'fecha_limite'              => 'nullable|date|after_or_equal:today',

            //'requiere_sucamec'          => 'boolean',
            //'requisitos_adicionales'    => 'nullable|string',
        ]);


        /* ---------- 2. CAMPOS AUTOMÁTICOS ---------- */
        $validated['user_id'] = Auth::id();
        $validated['fecha_solicitud'] = now();
        $validated['cargo_usuario'] = Auth::user()->cargo ?? null;

        /* ---------- 3. TRANSFORMAR CHECKBOX ---------- */
        $validated['requiere_sucamec'] = $request->boolean('requiere_sucamec');
        $validated['validado_rrhh'] = $request->boolean('validado_rrhh');

        try {
            $requerimiento = DB::transaction(
                fn() =>
                Requerimiento::create($validated)
            );
            // Notificar a todos los usuarios (puedes filtrar si quieres solo admins/reclutadores)
            $usuarios = User::all(); // O un filtro si prefieres
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevoRequerimientoCreado($requerimiento));
            }

            Log::info('Requerimiento guardado', $requerimiento->toArray());
            return back()->with('success', '¡Requerimiento creado con éxito!');
        } catch (\Throwable $e) {
            Log::error('Error al guardar requerimiento', ['message' => $e->getMessage()]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Error al guardar. Inténtalo de nuevo.']);
        }
    }


    //REVIEW FILTRAR

    public function filtrar(Request $request)
    {
        //$query = Requerimiento::query();
        $query = Requerimiento::with('estado');


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

        if ($request->filled('tipo_cargo')) {
            $query->where('tipo_Cargo', $request->tipo_cargo);
        }

        if ($request->filled('cargo_solicitado')) {
            $query->where('cargo_solicitado', $request->cargo_solicitado);
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
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

        /*-------------------------------------------------
        |   Mapeo de nombres legibles
        -------------------------------------------------*/
        // Cargar catálogos

        /*
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


        // Mapear nombres legibles con str_pad
        foreach ($requerimientos as $r) {
            // Convertir a string sin ceros a la izquierda
            $codigoTipoCargo = (string) $r->tipo_cargo;
            $codigoCargo = (string)$r->cargo_solicitado;
            $codigoSucursal = (string)$r->sucursal;
            $codigoDepartamento = (string)$r->departamento;
            $codigoProvincia = (string)$r->provincia;
            $codigoDistrito = (string)$r->distrito;


            // Buscar normalizado
            $r->tipo_cargo_nombre = $tipoCargos->get($codigoTipoCargo)?->DESC_TIPO_CARG ?? $r->tipo_cargo;
            $r->cargo_nombre = $cargos->get($codigoCargo)?->DESC_CARGO ?? $r->cargo_solicitado;
            $r->sucursal_nombre = $sucursales->get($codigoSucursal)?->SUCU_DESCRIPCION ?? $r->sucursal;
            $r->departamento_nombre = $departamentos->get($codigoDepartamento)?->DEPA_DESCRIPCION ?? $r->departamento;
            $r->provincia_nombre = $provincias->get($codigoProvincia)?->PROVI_DESCRIPCION ?? $r->provincia;
            $r->distrito_nombre = $distritos->get($codigoDistrito)?->DIST_DESCRIPCION ?? $r->distrito;
        }

        */

        // Contar total general
        $requerimientosProcesos = Requerimiento::where('estado', 1)->count();

        // Contar cubiertos
        $requerimientosCubiertos = Requerimiento::where('estado', 2)->count(); // suponiendo que estado 2 = Cubierto

        // Contar cancelados
        $requerimientosCancelados = Requerimiento::where('estado', 3)->count(); // estado 3 = Cancelado

        // Contar vencidos
        $requerimientosVencidos = Requerimiento::where('estado', 4)->count(); // estado 4 = Vencido


        return view('requerimientos.filtrar', compact(
            'requerimientos',
            'tipoCargos',
            'cargos',
            'sucursales',
            'departamentos',
            'provincias',
            'distritos',
            'requerimientosProcesos',
            'requerimientosCubiertos',
            'requerimientosCancelados',
            'requerimientosVencidos'
        ));
    }

   /*
    public function edit(Requerimiento $requerimiento)
    {
        $estados = EstadoRequerimiento::all();

        $sucursales = DB::connection('si_solmar')
            ->table('SISO_SUCURSAL')
            ->select('SUCU_CODIGO', 'SUCU_DESCRIPCION')
            ->where('SUCU_VIGENCIA', 'SI')
            ->get();

        $tipoCargos = DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select('CODI_TIPO_CARG', 'DESC_TIPO_CARG')
            ->get();

        $cargos = DB::connection('si_solmar')
            ->table('CARGOS')
            ->select('CODI_CARG', 'DESC_CARGO', 'TIPO_CARG')
            ->where('CARG_VIGENCIA', 'SI')
            ->get();

        $nivelEducativo = DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get();

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

        return view('requerimientos.partials.form-edit', compact(
            'requerimiento',
            'estados',
            'sucursales',
            'tipoCargos',
            'cargos',
            'nivelEducativo',
            'departamentos',
            'provincias',
            'distritos'
        ));
    }
    */

    /**
     * Valida y actualiza el postulante.
     */
    public function update(Request $request, Requerimiento $requerimiento)
    {
        // 1. Validar sólo los campos editables
        $data = $request->validate([
            'area_solicitante'           => 'required|string|max:50',
            'distrito'                   => 'required|string|max:50',
            'provincia'                  => 'required|string|max:50',
            'departamento'               => 'required|string|max:50',
            'cliente'                    => 'required|string|max:50',
            'tipo_cargo'                 => 'required|string|max:50',
            'sucursal'                   => 'required|string|max:50',
            'cargo_solicitado'           => 'required|string|max:50',
            'cantidad_requerida'         => 'required|integer|min:1|max:255',
            'fecha_limite'               => 'required|date|after_or_equal:today',
            'edad_minima'                => 'required|integer|min:18|max:65',
            'edad_maxima'                => 'required|integer|min:18|max:65',
            'requiere_licencia_conducir' => 'required|string|max:50',
            'requiere_sucamec'           => 'boolean',
            'nivel_estudios'             => 'required|string|max:50',
            'experiencia_minima'         => 'required|string|max:50',
            'requisitos_adicionales'     => 'nullable|string',
            //'validado_rrhh'              => 'boolean',
            //'escala_remunerativa'        => 'required|string|max:10',
            'prioridad'                  => 'required|exists:prioridad_requerimiento,id',
            'estado'                     => 'required|exists:estado_requerimiento,id',

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
