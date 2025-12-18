<?php

namespace App\Services;

use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Requerimiento;
use App\Notifications\NuevoRequerimientoCreado;
use Carbon\Carbon;

class RequerimientoService
{
    protected $repo;

    public function __construct(RequerimientosRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(): Collection
    {
        return $this->repo->getAll();
    }

    public function getById(mixed $id): Requerimiento
    {
        return $this->repo->getById($id);
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
        $model = $this->repo->getById($id);
        $data = $this->repo->getByIdWithRelations($id);

        if (!$data) return null;
    
        // Fechas (presentación) + valores raw para formularios
        $data['fecha_solicitud'] = $model && $model->fecha_solicitud ? $model->fecha_solicitud->format('d/m/Y') : '—';
        $data['fecha_inicio'] = $model && $model->fecha_inicio ? $model->fecha_inicio->format('d/m/Y') : '—';
        $data['fecha_fin'] = $model && $model->fecha_fin ? $model->fecha_fin->format('d/m/Y') : '—';
        $data['fecha_limite_raw'] = $model && $model->fecha_limite ? $model->fecha_limite->format('Y-m-d') : null;
        $data['fecha_limite'] = $model && $model->fecha_limite ? $model->fecha_limite->format('d/m/Y') : '—';

        // tipo_personal_nombre
        $tipos = $this->repo->getTipoPersonal();
        if ($model && !empty($model->tipo_personal)) {
            $data['tipo_personal_nombre'] = $tipos[$model->tipo_personal] ?? $model->tipo_personal;
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

    /* public function store(array $validated, $request)
    {
        
        $validated['user_id'] = Auth::id();
        $validated['fecha_solicitud'] = now();
        $validated['cargo_usuario'] = Auth::user()->cargo ?? null;

        $validated['requiere_sucamec'] = $request->boolean('requiere_sucamec');
        $validated['validado_rrhh']   = $request->boolean('validado_rrhh');

        $data['urgencia'] = $this->calcularUrgencia(
            $data['fecha_inicio'],
            $data['fecha_fin']
        );

        try {

            $requerimiento = DB::transaction(function () use ($validated) {
                return $this->repo->create($validated);
            });

            $usuarios = User::all();
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevoRequerimientoCreado($requerimiento));
            }

            Log::info('Requerimiento guardado', $requerimiento->toArray());

            return [
                'status' => true,
                'requerimiento' => $requerimiento
            ];

        } catch (\Throwable $e) {

            Log::error('Error al guardar requerimiento', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'error'  => $e->getMessage()
            ];
        }
    }
 */
    public function getCatalogos(): array
    {
        return [
            'estados'        => $this->repo->getEstados(),
            'prioridades'    => $this->repo->getPrioridades(),
            'sucursales'     => $this->repo->getSucursales(),
            'tipoCargos'     => $this->repo->getTipoCargos(),
            'cargos'         => $this->repo->getCargos(),
            'tipoPersonal'   => $this->repo->getTipoPersonal(),
            'clientes'       => $this->repo->getClientes(),
            'beneficios'     => $this->getBeneficios(),
            'departamentos'  => $this->repo->getDepartamentos(),
            'provincias'     => $this->repo->getProvincias(),
            'distritos'      => $this->repo->getDistritos(),
            'nivelEducativo' => $this->repo->getNivelesEducativos(),
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
        return $this->repo->getClientesPorSucursal($sucursal, $buscar);
    }

    public function sedesPorCliente(string $codigo): array
    {
        return $this->repo->getSedesPorCliente($codigo);
    }

    public function tiposPorTipoPersonal(string $tipoPersonal): array
    {
        return $this->repo->getTiposPorTipoPersonal($tipoPersonal);
    }

    public function cargosPorTipo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->repo->getCargosPorTipo($tipoPersonal, $tipoCargo);
    }

    public function cargoTipo(string $codiCarg): ?string
    {
        $tipo = $this->repo->getTipoCargo($codiCarg);
        return $tipo ? str_pad($tipo, 2, '0', STR_PAD_LEFT) : null;
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
