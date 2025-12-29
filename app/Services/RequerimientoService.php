<?php

namespace App\Services;

use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use App\Repositories\Interfaces\CargoRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Requerimiento;
use App\Notifications\NuevoRequerimientoCreado;
use App\Models\EstadoRequerimiento;
use App\Models\TipoPersonal;
use App\Models\PrioridadRequerimiento;
use Carbon\Carbon;

class RequerimientoService
{
    public function __construct(
        protected RequerimientosRepositoryInterface $requerimientoRepository,
        protected UbigeoService $ubigeoService,
        protected TipoCargoService $tipoCargoService,
        protected CatalogoService $catalogoService,
        protected CargoRepositoryInterface $cargoRepository,
    )
    {}

    /**
     * Aplica filtros, arma catálogos y mapea etiquetas legibles.
     */
    public function filtrar(array $params): array
    {
        $filters = [
            'tipo_cargo'   => $params['tipo_cargo'] ?? null,
            'cargo'        => $params['cargo'] ?? ($params['cargo_solicitado'] ?? null),
            'departamento' => $this->norm($params['departamento'] ?? null, 2),
            'provincia'    => $this->norm($params['provincia'] ?? null, 4),
            'distrito'     => $this->norm($params['distrito'] ?? null, 6),
            'sucursal'     => $this->norm($params['sucursal'] ?? null, 2),
            'cliente'      => $this->normDigits($params['cliente'] ?? null, 5),
        ];

        $resultado = $this->requerimientoRepository->listarPaginado($filters);

        $catalogos = $this->obtenerCatalogosFiltrar($resultado['requerimientos'], $filters);
        $this->mapearRequerimientosLegibles($resultado['requerimientos'], $catalogos);

        return [
            'requerimientos' => $resultado['requerimientos'],
            'stats'          => $resultado['stats'],
            'catalogos'      => $catalogos,
        ];
    }

    private function norm(?string $val, ?int $len = null): ?string
    {
        if ($val === null || $val === '') return null;
        $val = (string) $val;
        if (!ctype_digit($val) || $len === null) return $val;
        return str_pad($val, $len, '0', STR_PAD_LEFT);
    }

    private function normDigits(?string $val, int $len): ?string
    {
        $raw = preg_replace('/\D+/', '', (string)($val ?? ''));
        if ($raw === '') return null;
        return str_pad($raw, $len, '0', STR_PAD_LEFT);
    }

    /**
     * Obtiene catálogos requeridos por la vista de filtrado.
     */
    private function obtenerCatalogosFiltrar($requerimientos, array $filters): array
    {
        $departamentos = $this->catalogoService->obtenerDepartamentos();
        $sucursales    = $this->catalogoService->obtenerSucursales();
        $tipoCargos    = $this->catalogoService->obtenerTiposCargo();
        $cargos        = collect($this->catalogoService->obtenerCargos());

        // Clientes: para el filtro se muestran sólo si hay sucursal elegida
        $clientes = [];
        if (!empty($filters['sucursal'])) {
            $rows = $this->catalogoService->obtenerClientesPorSucursal((string) $filters['sucursal']);
            foreach ($rows as $row) {
                $codigo = $this->normDigits((string)($row['CODIGO_CLIENTE'] ?? $row['codigo_cliente'] ?? ''), 5);
                if ($codigo === null) continue;
                $clientes[$codigo] = (string)($row['NOMBRE_CLIENTE'] ?? $row['nombre_cliente'] ?? $codigo);
            }
        }

        // Clientes por sucursal: para etiquetar la tabla (cliente_nombre)
        $clientesPorSucursal = [];
        try {
            $items = collect($requerimientos instanceof \Illuminate\Pagination\LengthAwarePaginator ? $requerimientos->items() : $requerimientos);
            $sucursalesEnPagina = $items
                ->pluck('sucursal')
                ->filter(fn ($v) => $v !== null && $v !== '')
                ->map(fn ($v) => $this->normDigits((string) $v, 2))
                ->filter()
                ->unique()
                ->values();

            foreach ($sucursalesEnPagina as $sucu) {
                $map = [];
                $rows = $this->catalogoService->obtenerClientesPorSucursal((string) $sucu);
                foreach ($rows as $row) {
                    $codigo = $this->normDigits((string)($row['CODIGO_CLIENTE'] ?? $row['codigo_cliente'] ?? ''), 5);
                    if ($codigo === null) continue;
                    $map[$codigo] = (string)($row['NOMBRE_CLIENTE'] ?? $row['nombre_cliente'] ?? $codigo);
                }
                $clientesPorSucursal[(string) $sucu] = $map;
            }
        } catch (\Throwable $e) {
            Log::warning('No se pudo armar mapa de clientes por sucursal', [
                'error' => $e->getMessage(),
            ]);
        }

        $provinciasList = collect($this->catalogoService->obtenerProvincias())
            ->map(function ($p) {
                $p = (object)$p;
                $p->PROVI_CODIGO = $this->normDigits($p->PROVI_CODIGO ?? '', 4);
                $p->DEPA_CODIGO  = $this->normDigits($p->DEPA_CODIGO ?? '', 2);
                return $p;
            });
        $provinciasStr = $provinciasList->keyBy('PROVI_CODIGO');
        $provinciasNum = $provinciasList->keyBy(fn($p) => (int)$p->PROVI_CODIGO);

        $distritosList = collect($this->catalogoService->obtenerDistritos())
            ->map(function ($d) {
                $d = (object)$d;
                $d->DIST_CODIGO  = $this->normDigits($d->DIST_CODIGO ?? '', 6);
                $d->PROVI_CODIGO = $this->normDigits($d->PROVI_CODIGO ?? '', 4);
                return $d;
            });
        $distritosStr = $distritosList->keyBy('DIST_CODIGO');
        $distritosNum = $distritosList->keyBy(fn($d) => (int)$d->DIST_CODIGO);

        $tiposPersonal = TipoPersonal::forSelect();
        $estados = EstadoRequerimiento::all();

        $nivelEducativo = $this->requerimientoRepository->getNivelesEducativos();

        return [
            'departamentos' => $departamentos,
            'sucursales'    => $sucursales,
            'clientes'      => $clientes,
            'clientesPorSucursal' => $clientesPorSucursal,
            'tipoCargos'    => $tipoCargos,
            'cargos'        => $cargos,
            'provincias'    => $provinciasStr,
            'provinciasNum' => $provinciasNum,
            'distritos'     => $distritosStr,
            'distritosNum'  => $distritosNum,
            'tiposPersonal' => $tiposPersonal,
            'estados'       => $estados,
            'nivelEducativo'=> $nivelEducativo,
        ];
    }

    /**
     * Añade etiquetas legibles al paginador usando los catálogos cargados.
     */
    private function mapearRequerimientosLegibles($requerimientos, array $catalogos): void
    {
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

        foreach ($requerimientos as $r) {
            $dep  = $this->normDigits($r->departamento ?? '', 2);
            $prov = $this->normDigits($r->provincia    ?? '', 4);
            $dist = $this->normDigits($r->distrito     ?? '', 6);

            $r->cargo_nombre = $catalogos['cargos']->get((string) $r->cargo_solicitado) ?? $r->cargo_solicitado;

            $r->sucursal_nombre = $catalogos['sucursales'][$this->normDigits($r->sucursal, 2)] ?? $r->sucursal;
            $sucu = $this->normDigits($r->sucursal ?? '', 2);
            $cli  = $this->normDigits($r->cliente ?? '', 5);
            $r->cliente_nombre  = ($sucu && $cli && !empty($catalogos['clientesPorSucursal'][$sucu][$cli]))
                ? $catalogos['clientesPorSucursal'][$sucu][$cli]
                : $r->cliente;

            $r->departamento_nombre = $catalogos['departamentos'][$dep] ?? $r->departamento;
            $r->provincia_nombre    = $label2($catalogos['provincias'], $catalogos['provinciasNum'], $prov, 'PROVI_DESCRIPCION');
            $r->distrito_nombre     = $label2($catalogos['distritos'],  $catalogos['distritosNum'],  $dist, 'DIST_DESCRIPCION');

            $cod = $this->normDigits($r->tipo_personal ?? '', 2);
            $r->tipo_personal_nombre = $catalogos['tiposPersonal'][$cod] ?? $r->tipo_personal;

            $estadoMap = [1 => 'En proceso', 2 => 'Cubierto', 3 => 'Cancelado', 4 => 'Vencido'];
            $r->estado_nombre = $estadoMap[(int)$r->estado] ?? null;
        }
    }

    public function getDetalleConCatalogos(mixed $id): ?array
    {
        $detalle = $this->getDetalleCompleto($id);
        if (!$detalle) return null;
        
        $catalogos = $this->getCatalogos();
        return array_merge($detalle, $catalogos);
    }

    public function getDetalleCompleto(mixed $id): ?array
    {
        $model = Requerimiento::query()->find($id);
        
        if (!$model) return null;
        
        $data = $this->transformarRequerimiento($model);
    
        // Fechas (presentación) + valores raw para formularios
        $data['fecha_solicitud'] = $model && $model->fecha_solicitud ? Carbon::parse($model->fecha_solicitud)->format('d/m/Y') : '—';
        $data['fecha_inicio'] = $model && $model->fecha_inicio ? Carbon::parse($model->fecha_inicio)->format('d/m/Y') : '—';
        $data['fecha_fin'] = $model && $model->fecha_fin ? Carbon::parse($model->fecha_fin)->format('d/m/Y') : '—';
        $data['fecha_limite_raw'] = $model && $model->fecha_limite ? Carbon::parse($model->fecha_limite)->format('Y-m-d') : null;
        $data['fecha_limite'] = $model && $model->fecha_limite ? Carbon::parse($model->fecha_limite)->format('d/m/Y') : '—';

        // tipo_personal_nombre
        $tipos = TipoPersonal::forSelect();
        if ($model && !empty($model->tipo_personal)) {
            $cod = $this->normDigits((string) $model->tipo_personal, 2);
            $data['tipo_personal_nombre'] = $tipos[$cod] ?? $model->tipo_personal;
        } else {
            $data['tipo_personal_nombre'] = $data['tipo_personal'] ?? '—';
        }

        // cantidad_requerida
        $data['cantidad_requerida'] = $model && !empty($model->cantidad_requerida) ? $model->cantidad_requerida : '—';

        // experiencia_minima
        $rawExp = $model && !empty($model->experiencia_minima) ? (string)$model->experiencia_minima : '';
        $data['experiencia_minima_raw'] = $rawExp;
        if ($rawExp === '') {
            $data['experiencia_minima'] = '—';
        } else {
            $exp = str_replace('_', ' ', $rawExp);
            $exp = str_ireplace(['anos', 'anios', 'anio'], ['años', 'años', 'año'], $exp);
            $data['experiencia_minima'] = $exp;
        }

        // grado_academico
        $rawGrado = $model && !empty($model->grado_academico) ? (string)$model->grado_academico : '';
        $data['grado_academico_raw'] = $rawGrado;
        if ($rawGrado === '') {
            $data['grado_academico'] = '—';
        } else {
            $g = trim($rawGrado);
            if (strpos($g, '_') !== false) {
                $val = preg_replace('/_+/', ' ', $g);
                $val = preg_replace('/\s+/', ' ', $val);
                $data['grado_academico'] = mb_strtoupper($val);
            } elseif (strpos($g, '-') !== false) {
                $val = preg_replace('/-+/', ' ', $g);
                $val = preg_replace('/\s+/', ' ', $val);
                $data['grado_academico'] = mb_convert_case(mb_strtolower($val), MB_CASE_TITLE, "UTF-8");
            } else {
                $data['grado_academico'] = mb_convert_case(mb_strtolower($g), MB_CASE_TITLE, "UTF-8");
            }
        }

        // beneficios
        $beneficios = $this->getBeneficios();
        $rawBenef = $model && !empty($model->beneficios) ? (string)$model->beneficios : ($data['beneficios'] ?? '');
        $data['beneficios'] = $rawBenef;
        $data['beneficios_nombre'] = $beneficios[$rawBenef] ?? ($data['beneficios'] ?? '—');
        return $data;
    }

    public function getCatalogos(): array
    {
        $catalogos = $this->catalogoService->requerimiento();

        return [
            'estados'        => EstadoRequerimiento::all(),
            'prioridades'    => PrioridadRequerimiento::all(),
            'sucursales'     => $catalogos['sucursales'] ?? [],
            'tipoCargos'     => $catalogos['tipos_cargo'] ?? [],
            'cargos'         => $catalogos['cargos'] ?? [],
            'tipoPersonal'   => TipoPersonal::forSelect(),
            'clientes'       => [],
            'beneficios'     => $this->getBeneficios(),
            'departamentos'  => $catalogos['departamentos'] ?? [],
            'provincias'     => $catalogos['provincias'] ?? [],
            'distritos'      => $catalogos['distritos'] ?? [],
            'nivelEducativo' => $this->requerimientoRepository->getNivelesEducativos(),
        ];
    }

    public function getBeneficios(): array
    {
        return [
            'escala_a' => 'Seguro de Salud',
            'escala_b' => 'CTS',
            'escala_c' => 'Vacaciones',
            'escala_d' => 'Asignación familiar',
            'escala_e' => 'Utilidades',
        ];
    }

    public function clientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        return $this->catalogoService->obtenerClientesPorSucursal($sucursal, $buscar);
    }

    public function sedesPorCliente(string $codigo): array
    {
        return $this->catalogoService->obtenerSedesPorCliente($codigo);
    }

    public function tiposPorTipoPersonal(string $tipoPersonal): array
    {
        return $this->tipoCargoService->obtenerPorTipoPersonal($tipoPersonal);
    }

    public function cargosPorTipo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->catalogoService->obtenerPorTipoPersonalYTipoCargo($tipoPersonal, $tipoCargo);
    }

    public function cargoTipo(string $codiCarg): ?string
    {
        $tipo = $this->cargoRepository->obtenerTipoCargo($codiCarg);
        return $tipo ? str_pad($tipo, 2, '0', STR_PAD_LEFT) : null;
    }

    /**
     * Transforma un modelo de Requerimiento a array con campos adicionales.
     * La lógica de transformación corresponde a la capa Service, no al Repository.
     */
    private function transformarRequerimiento(Requerimiento $model): array
    {
        $data = $model->toArray();

        // Cargar usuario
        if (!empty($model->user_id)) {
            $usuario = DB::table('users')->where('id', $model->user_id)->first();
            $data['usuario_nombre'] = $usuario ? $usuario->name : 'Desconocido';
        }

        // Cargar estado
        if (!empty($model->estado)) {
            // Evita depender de una tabla catalogo que puede no existir en el mismo origen.
            $estadoMap = [1 => 'En proceso', 2 => 'Cubierto', 3 => 'Cancelado', 4 => 'Vencido'];
            $data['estado_nombre'] = $estadoMap[(int) $model->estado] ?? null;
        }

        // Cargar cargo desde SQL Server
        if (!empty($model->cargo_solicitado)) {
            $cargo = DB::connection('si_solmar')
                ->table('dbo.CARGOS')
                ->where('CODI_CARG', $model->cargo_solicitado)
                ->value('DESC_CARGO');
            $data['cargo_solicitado_nombre'] = $cargo ?? $model->cargo_solicitado;
        }

        // Cargar sucursal desde SQL Server
        if (!empty($model->sucursal)) {
            $sucursal = DB::connection('si_solmar')
                ->table('dbo.SISO_SUCURSAL')
                ->where('SUCU_CODIGO', $model->sucursal)
                ->value('SUCU_DESCRIPCION');
            $data['sucursal_nombre'] = $sucursal ?? $model->sucursal;
        }

        // Cargar cliente desde SQL Server
        if (!empty($model->cliente)) {
            $clienteCodigo = trim((string) $model->cliente);
            if ($clienteCodigo !== '' && ctype_digit($clienteCodigo)) {
                $clienteCodigo = str_pad(ltrim($clienteCodigo, '0'), 5, '0', STR_PAD_LEFT);
            }

            $clienteNombre = null;
            if (!empty($model->sucursal)) {
                $sucu = trim((string) $model->sucursal);
                if ($sucu !== '' && ctype_digit($sucu)) {
                    $sucu = str_pad(ltrim($sucu, '0'), 2, '0', STR_PAD_LEFT);
                }

                try {
                    $clientes = $this->catalogoService->obtenerClientesPorSucursal($sucu);
                    $match = collect($clientes)->firstWhere('CODIGO_CLIENTE', $clienteCodigo);
                    $clienteNombre = is_array($match) ? ($match['NOMBRE_CLIENTE'] ?? null) : null;
                } catch (\Throwable $e) {
                    // Si falla el catlogo, degradar a cigo.
                    $clienteNombre = null;
                }
            }

            $data['cliente_nombre'] = $clienteNombre ?: ($clienteCodigo !== '' ? $clienteCodigo : $model->cliente);
        }

        // Alias para la vista (modal espera `cargo_nombre`)
        if (!empty($data['cargo_solicitado_nombre']) && empty($data['cargo_nombre'])) {
            $data['cargo_nombre'] = $data['cargo_solicitado_nombre'];
        }
        
        return $data;
    }

    public function calcularUrgencia($fechaInicio, $fechaFin)
    {
        if (!$fechaInicio || !$fechaFin) {
            return ['valor' => null, 'texto' => 'NO SE SELECCIONÓ LA FECHA'];
        }

        $inicio = Carbon::parse($fechaInicio);
        $fin = Carbon::parse($fechaFin);
        $diffDias = $inicio->diffInDays($fin, false);

        if ($diffDias < 0) {
            return ['valor' => 'Invalida', 'texto' => '¡Fechas inválidas!'];
        } elseif ($diffDias <= 7) {
            return ['valor' => 'Alta', 'texto' => 'Nivel de urgencia: Alta (1 semana)'];
        } elseif ($diffDias <= 14) {
            return ['valor' => 'Media', 'texto' => 'Nivel de urgencia: Media (2 semanas)'];
        } elseif ($diffDias <= 31) {
            return ['valor' => 'Baja', 'texto' => 'Nivel de urgencia: Baja (1 mes)'];
        } else {
            return ['valor' => 'Mayor', 'texto' => 'Plazo mayor a 1 mes'];
        }
    }
}