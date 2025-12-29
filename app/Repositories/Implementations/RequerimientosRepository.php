<?php

namespace App\Repositories\Implementations;

use Illuminate\Support\Facades\DB;
use App\Models\Requerimiento;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Interfaces\RequerimientosRepositoryInterface;

class RequerimientosRepository extends BaseRepository implements RequerimientosRepositoryInterface
{
    protected Requerimiento $modelo;

    public function __construct(Requerimiento $modelo)
    {
        parent::__construct($modelo);
    }

    public function listarPaginado(array $filters, int $perPage = 15): array
    {
        $query = $this->obtenerFiltros($filters);

        $stats = $this->obtenerStats($filters);

        $query->orderBy('created_at', 'desc');
        $requerimientos = $query->paginate($perPage)->withQueryString();

        return [
            'requerimientos' => $requerimientos,
            'stats'          => $stats,
        ];
    }

    public function obtenerStats(array $filters): array
    {
        $base = $this->obtenerFiltros($filters);

        return [
            'total'         => (clone $base)->count(),
            'en_validacion' => (clone $base)->where('estado', '1')->count(),
            'aprobado'      => (clone $base)->where('estado', '2')->count(),
            'cancelado'     => (clone $base)->where('estado', '3')->count(),
            'cerrado'       => (clone $base)->where('estado', '4')->count(),
        ];
    }

    public function obtenerFiltros(array $filters)
    {
        $query = $this->model->newQuery();

        $query->when(!empty($filters['tipo_cargo']), fn($q) => $q->where('tipo_cargo', $filters['tipo_cargo']));
        $query->when(!empty($filters['cargo']), fn($q) => $q->where('cargo_solicitado', $filters['cargo']));
        $query->when(!empty($filters['departamento']), fn($q) => $q->where('departamento', $filters['departamento']));
        $query->when(!empty($filters['provincia']), fn($q) => $q->where('provincia', $filters['provincia']));
        $query->when(!empty($filters['distrito']), fn($q) => $q->where('distrito', $filters['distrito']));
        $query->when(!empty($filters['sucursal']), fn($q) => $q->where('sucursal', $filters['sucursal']));
        $query->when(!empty($filters['cliente']), fn($q) => $q->where('cliente', $filters['cliente']));

        return $query;
    }

    //Acceso de solo lectura
    public function getNivelesEducativos(): array
    {
        return DB::connection('sqlsrv')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get()
            ->map(function($item) {
                return (object)$item;
            })
            ->toArray();
    }
}