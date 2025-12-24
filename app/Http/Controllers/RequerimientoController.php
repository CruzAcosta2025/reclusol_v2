<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use App\Models\User;
use App\Models\EstadoRequerimiento;
use App\Models\Sucursal;
use App\Models\TipoCargo;
use App\Models\Cargo;
use App\Models\Distrito;
use App\Models\TipoPersonal;
use App\Models\PrioridadRequerimiento;
use App\Models\Provincia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Departamento;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Notifications\NuevoRequerimientoCreado;
use App\Services\RequerimientoService;

class RequerimientoController extends Controller
{

    protected $service;

    public function __construct(RequerimientoService $service)
    {
        $this->service = $service;
    }

    public function mostrar() //registro
    {
        $data = $this->service->getCatalogos();
        return view('requerimientos.requerimiento', $data);
    }

    public function clientesPorSucursalSP(Request $request)
    {
        $sucursal = trim((string) $request->query('codigo_sucursal', ''));
        $buscar   = $request->query('q');

        try {
            $data = $this->service->clientesPorSucursal($sucursal, $buscar);
            return response()->json($data, 200);
        } catch (\Throwable $e) {
            Log::error('SP clientesPorSucursal error', [
                'sucursal' => $sucursal,
                'error'    => $e->getMessage()
            ]);
            return response()->json([], 200);
        }
    }

    public function sedesPorCliente(Request $request)
    {
        $codigo = $request->input('codigo_cliente');
        $sedes = $this->service->sedesPorCliente($codigo);
        return response()->json($sedes);
    }

    public function tiposPorTipoPersonal(Request $request)
    {
        $tipoPersonal = $request->query('tipo_personal');
        $data = $this->service->tiposPorTipoPersonal($tipoPersonal);
        return response()->json($data);
    }

    public function cargosPorTipo(Request $request)
    {
        $tipoPersonal = $request->query('tipo_personal');
        $tipoCargo    = $request->query('tipo_cargo');
        $data = $this->service->cargosPorTipo($tipoPersonal, $tipoCargo);
        return response()->json($data);
    }

    public function cargoTipo($codiCarg)
    {
        $tipo = $this->service->cargoTipo($codiCarg);
        return response()->json([
            'cargo_tipo' => $tipo
        ]);
    }

    public function index()
    {
        return view('requerimientos.filtrar');
    }

    public function probarRepositorio()
    {
        $requerimiento = $this->service->getById(2);

        if (!$requerimiento) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json($requerimiento);
    }

    public function detalle($id)
    {
        $detalle = $this->service->getDetalleConCatalogos($id);

        if (!$detalle) {
            return response()->json(['error' => 'No encontrado'], 404);
        }

        return response()->json($detalle);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
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
                'formacion_adicional'       => 'nullable|string|max:50',
                'requiere_licencia_conducir' => 'nullable|string|max:50',
                'validado_rrhh'             => 'boolean',
                'sueldo_basico'             => 'required|string|max:50',
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
            $validated['cargo_usuario'] = Auth::user()->cargo ?? 'Sin especificar';

            /* ---------- 3. TRANSFORMAR CHECKBOX ---------- */
            $validated['requiere_sucamec'] = $request->boolean('requiere_sucamec');
            $validated['validado_rrhh'] = $request->boolean('validado_rrhh');

            /* ---------- 4. CREAR EN TRANSACCIÓN ---------- */
            $requerimiento = DB::transaction(function () use ($validated) {
                Log::info('Iniciando transacción...');
                $req = Requerimiento::create($validated);
                Log::info('Requerimiento creado con ID:', ['id' => $req->id]);
                return $req;
            });

            /* ---------- 5. NOTIFICACIONES ---------- */
            try {
                $usuarios = User::all();
                foreach ($usuarios as $usuario) {
                    $usuario->notify(new NuevoRequerimientoCreado($requerimiento));
                }
            } catch (\Throwable $notifError) {
                Log::warning('Error al enviar notificaciones (pero se guardó el registro):', [
                    'message' => $notifError->getMessage()
                ]);
            }

            Log::info('=== STORE COMPLETADO EXITOSAMENTE ===');
            
            // Devolver JSON para AJAX
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => '¡Requerimiento creado con éxito!',
                    'requerimiento' => $requerimiento
                ], 201);
            }
            
            return back()->with('success', '¡Requerimiento creado con éxito!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Error de validación:', $e->errors());
            
            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors()
                ], 422);
            }

            return back()
                ->withInput()
                ->withErrors($e->errors());
                
        } catch (\Throwable $e) {
            Log::error('=== ERROR CRÍTICO AL GUARDAR REQUERIMIENTO ===', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al guardar: ' . $e->getMessage()
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['general' => 'Error al guardar. Inténtalo de nuevo.']);
        }
    }

    //REVIEW FILTRAR

    /*
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
    */

    public function filtrar(Request $request)
    {
        $query = Requerimiento::query();

        $norm = function (?string $val, int $len = null) {
            if ($val === null || $val === '') return null;
            $val = (string) $val;
            if (!ctype_digit($val) || $len === null) return $val;
            return str_pad($val, $len, '0', STR_PAD_LEFT);
        };
        // Solo dígitos + pad (a prueba de espacios, NBSP, guiones)
        $normDigits = function (?string $val, int $len) {
            $raw = preg_replace('/\D+/', '', (string)($val ?? ''));
            if ($raw === '') return null;
            return str_pad($raw, $len, '0', STR_PAD_LEFT);
        };

        $tipoCargo = $request->filled('tipo_cargo') ? (string)$request->input('tipo_cargo') : null;
        $cargo     = $request->filled('cargo_solicitado')      ? (string)$request->input('cargo_solicitado')      : null;
        $depa      = $norm($request->input('departamento'), 2);
        $provi     = $norm($request->input('provincia'),     4);
        $distr     = $norm($request->input('distrito'),      6);
        $sucu      = $norm($request->input('sucursal'), 2);
        // Tenías 2; los códigos son de 5 dígitos
        $cli = $normDigits($request->input('cliente'), 5);

        $query->when($tipoCargo, fn($q) => $q->where('tipo_cargo', $tipoCargo));
        $query->when($cargo,     fn($q) => $q->where('cargo', $cargo));
        $query->when($depa,      fn($q) => $q->where('departamento', $depa));
        $query->when($provi,     fn($q) => $q->where('provincia', $provi));
        $query->when($distr,     fn($q) => $q->where('distrito', $distr));
        $query->when($sucu, fn($q) => $q->where('sucursal', $sucu));
        $query->when($cli,  fn($q) => $q->where('cliente',  $cli));

        // Stats con mismos filtros
        $base = clone $query;
        $stats = [
            'total'    => (clone $base)->count(),
            'en_validacion'    => (clone $base)->where('estado', '1')->count(),
            'aprobado' => (clone $base)->where('estado', '2')->count(),
            'cancelado' => (clone $base)->where('estado', '3')->count(),
            'cerrado' => (clone $base)->where('estado', '4')->count(),

        ];

        $query->orderBy('created_at', 'desc');
        $requerimientos = $query->paginate(15)->withQueryString();

        // Catálogos para la vista
        $departamentos = Departamento::forSelect(); // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $sucursales    = Sucursal::forSelect();    // [SUCU_CODIGO => SUCU_DESCRIPCION]
        $clientes      = collect(DB::connection('sqlsrv')->select('EXEC dbo.SP_LISTAR_CLIENTES'))
            ->mapWithKeys(function ($item) {
                $cod = is_string($item->CODIGO_CLIENTE) ? trim($item->CODIGO_CLIENTE) : $item->CODIGO_CLIENTE;
                return [$cod => $item->NOMBRE_CLIENTE];
            })->toArray(); // [CODIGO_CLIENTE => NOMBRE_CLIENTE]

        $tipoCargos    = TipoCargo::forSelect();    // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        $cargos = Cargo::vigentes()
            ->get(['CODI_CARG', 'DESC_CARGO', 'TIPO_CARG'])
            ->keyBy('CODI_CARG');

        /* === Provincias y Distritos desde SQL Server (tablas reales) === */
        // PROVINCIAS
        $provinciasList = Provincia::vigentes()
            ->get(['PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO'])
            ->map(function ($p) use ($normDigits) {
                $p->PROVI_CODIGO = $normDigits($p->PROVI_CODIGO, 4);
                $p->DEPA_CODIGO  = $normDigits($p->DEPA_CODIGO,  2);
                return $p;
            });
        $provinciasStr = $provinciasList->keyBy('PROVI_CODIGO');                 // '0608'
        $provinciasNum = $provinciasList->keyBy(fn($p) => (int)$p->PROVI_CODIGO); // 608

        // DISTRITOS
        $distritosList = Distrito::vigentes()
            ->get(['DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO'])
            ->map(function ($d) use ($normDigits) {
                $d->DIST_CODIGO  = $normDigits($d->DIST_CODIGO,  6);
                $d->PROVI_CODIGO = $normDigits($d->PROVI_CODIGO, 4);
                return $d;
            });
        $distritosStr = $distritosList->keyBy('DIST_CODIGO');                     // '060808'
        $distritosNum = $distritosList->keyBy(fn($d) => (int)$d->DIST_CODIGO);    // 60808
        $tiposPersonal = TipoPersonal::forSelect();


        // Helper que intenta con clave string y numérica
        $label2 = function ($collStr, $collNum, $code, $field) {
            if ($code === null || $code === '') return $code;
            if ($collStr->has($code)) {
                $o = $collStr->get($code);
                return $o->{$field} ?? (string)$o;
            }
            $numKey = (int)preg_replace('/\D+/', '', $code);
            if ($collNum->has($numKey)) {
                $o = $collNum->get($numKey);
                return $o->{$field} ?? (string)$o;
            }
            return $code;
        };

        // Mapeo legible por fila
        foreach ($requerimientos as $r) {
            $dep  = $normDigits($r->departamento ?? '', 2);
            $prov = $normDigits($r->provincia    ?? '', 4);
            $dist = $normDigits($r->distrito     ?? '', 6);

            // NOMBRE DE CARGO (usa cargo_solicitado)
            $r->cargo_nombre = optional($cargos->get((string)$r->cargo_solicitado))->DESC_CARGO
                ?? $r->cargo_solicitado;

            // SUCURSAL y CLIENTE legibles
            $r->sucursal_nombre = $sucursales[$normDigits($r->sucursal, 2)] ?? $r->sucursal;
            $r->cliente_nombre  = $clientes[$normDigits($r->cliente, 5)]   ?? $r->cliente;

            // Los que ya tenías
            $r->departamento_nombre = $departamentos[$dep] ?? $r->departamento;
            $r->provincia_nombre    = $label2($provinciasStr, $provinciasNum, $prov, 'PROVI_DESCRIPCION');
            $r->distrito_nombre     = $label2($distritosStr,  $distritosNum,  $dist, 'DIST_DESCRIPCION');
            $cod = $normDigits($r->tipo_personal ?? '', 2);
            $r->tipo_personal_nombre = $tiposPersonal[$cod] ?? $r->tipo_personal;
            // Mapear estado numérico -> etiqueta
            $estadoMap = [1 => 'En proceso', 2 => 'Cubierto', 3 => 'Cancelado', 4 => 'Vencido'];
            $r->estado_nombre = $estadoMap[(int)$r->estado] ?? null;
        }

        // Para la vista/JS seguimos exponiendo $provincias y $distritos
        $provincias = $provinciasStr;
        $distritos  = $distritosStr;

        // Contar total general
        $requerimientosProcesos = Requerimiento::where('estado', 1)->count();

        // Contar cubiertos
        $requerimientosCubiertos = Requerimiento::where('estado', 2)->count(); // suponiendo que estado 2 = Cubierto

        // Contar cancelados
        $requerimientosCancelados = Requerimiento::where('estado', 3)->count(); // estado 3 = Cancelado

        // Contar vencidos
        $requerimientosVencidos = Requerimiento::where('estado', 4)->count(); // estado 4 = Vencido

        // Exponer catálogos que usan los partials del modal
        $estados = EstadoRequerimiento::all();
        
        $nivelEducativo = DB::connection('si_solmar')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get();

        return view('requerimientos.filtrar', compact(
            'requerimientos',
            'tipoCargos',
            'cargos',
            'departamentos',
            'provincias',
            'distritos',
            'sucursales',
            'clientes',
            'stats',
            'nivelEducativo'
        ))->with([
            'tipoPersonal' => $tiposPersonal,
            'estados' => $estados,
        ]);
    }

    public function edit(Requerimiento $requerimiento)
    {
        // Obtener todos los catálogos desde el servicio
        $catalogos = $this->service->getCatalogos();
        
        // Agregar el requerimiento al array
        $catalogos['requerimiento'] = $requerimiento;
        
        // Retornar la vista con todos los datos
        return view('requerimientos.partials.form-edit', $catalogos);
    }

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

    public function destroy(Requerimiento $requerimiento)
    {
        try {
            $requerimiento->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar un requerimiento', [
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
