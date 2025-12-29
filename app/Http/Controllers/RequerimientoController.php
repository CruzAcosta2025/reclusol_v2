<?php

namespace App\Http\Controllers;

use App\Models\Requerimiento;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
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
        $tipoPersonal = trim((string) $request->query('tipo_personal', ''));
        $rows = $this->service->tiposPorTipoPersonal($tipoPersonal);

        $data = collect($rows)->map(function ($row) {
            $row = (array) $row;

            if (array_key_exists('value', $row) && array_key_exists('label', $row)) {
                return [
                    'value' => is_string($row['value']) ? trim($row['value']) : (string) $row['value'],
                    'label' => is_string($row['label']) ? trim($row['label']) : (string) $row['label'],
                ];
            }

            $value = $row['CODI_TIPO_CARG'] ?? $row['codi_tipo_carg'] ?? $row['codigo'] ?? $row['id'] ?? null;
            $label = $row['DESC_TIPO_CARG'] ?? $row['desc_tipo_carg'] ?? $row['descripcion'] ?? $row['nombre'] ?? $value;

            return [
                'value' => is_string($value) ? trim($value) : (string) $value,
                'label' => is_string($label) ? trim($label) : (string) $label,
            ];
        })->values();

        return response()->json($data);
    }

    public function cargosPorTipo(Request $request)
    {
        $tipoPersonal = trim((string) $request->query('tipo_personal', ''));
        $tipoCargo    = trim((string) $request->query('tipo_cargo', ''));
        $rows = $this->service->cargosPorTipo($tipoPersonal, $tipoCargo);

        $data = collect($rows)->map(function ($row) {
            $row = (array) $row;

            if (array_key_exists('value', $row) && array_key_exists('label', $row)) {
                return [
                    'value' => is_string($row['value']) ? trim($row['value']) : (string) $row['value'],
                    'label' => is_string($row['label']) ? trim($row['label']) : (string) $row['label'],
                ];
            }

            $value = $row['CODI_CARG'] ?? $row['codi_carg'] ?? $row['codigo'] ?? $row['id'] ?? null;
            $label = $row['DESC_CARGO'] ?? $row['desc_cargo'] ?? $row['descripcion'] ?? $row['nombre'] ?? $value;

            return [
                'value' => is_string($value) ? trim($value) : (string) $value,
                'label' => is_string($label) ? trim($label) : (string) $label,
            ];
        })->values();

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

    public function detalle($id)
    {
        try {
            // Este endpoint se usa para el modal de "ver"; devolver solo detalle evita fallos
            // por catlogos/tablas que pueden vivir en otro origen.
            $detalle = $this->service->getDetalleCompleto($id);

            if (!$detalle) {
                return response()->json(['error' => 'No encontrado'], 404);
            }

            return response()->json($detalle);
        } catch (\Throwable $e) {
            Log::error('Error en detalle de requerimiento', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'error' => 'Error interno al cargar el requerimiento.'
            ], 500);
        }
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

            // Calcular urgencia en backend (fuente de verdad)
            $urg = $this->service->calcularUrgencia($validated['fecha_inicio'] ?? null, $validated['fecha_fin'] ?? null);
            $urgValor = $urg['valor'] ?? null;
            if ($urgValor === null || $urgValor === 'Invalida') {
                $msg = $urg['texto'] ?? 'Fechas inválidas para calcular urgencia.';

                if ($request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                    return response()->json([
                        'success' => false,
                        'message' => $msg,
                        'errors'  => ['fecha_fin' => [$msg]],
                    ], 422);
                }

                return back()->withInput()->withErrors(['fecha_fin' => $msg]);
            }

            // Validación opcional de coincidencia: si el frontend manda algo distinto, se sobreescribe y se registra
            if (!empty($validated['urgencia']) && $validated['urgencia'] !== $urgValor) {
                Log::warning('Urgencia enviada no coincide con urgencia calculada', [
                    'urgencia_enviada' => $validated['urgencia'],
                    'urgencia_calculada' => $urgValor,
                    'fecha_inicio' => $validated['fecha_inicio'] ?? null,
                    'fecha_fin' => $validated['fecha_fin'] ?? null,
                    'user_id' => Auth::id(),
                ]);
            }
            $validated['urgencia'] = $urgValor;
            
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

    public function filtrar(Request $request)
    {
        $resultado = $this->service->filtrar($request->all());

        return view('requerimientos.filtrar', [
            'requerimientos' => $resultado['requerimientos'],
            'tipoCargos'     => $resultado['catalogos']['tipoCargos'],
            'cargos'         => $resultado['catalogos']['cargos'],
            'departamentos'  => $resultado['catalogos']['departamentos'],
            'provincias'     => $resultado['catalogos']['provincias'],
            'distritos'      => $resultado['catalogos']['distritos'],
            'sucursales'     => $resultado['catalogos']['sucursales'],
            'clientes'       => $resultado['catalogos']['clientes'],
            'stats'          => $resultado['stats'],
            'nivelEducativo' => $resultado['catalogos']['nivelEducativo'],
        ])->with([
            'tipoPersonal' => $resultado['catalogos']['tiposPersonal'],
            'estados'      => $resultado['catalogos']['estados'],
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
