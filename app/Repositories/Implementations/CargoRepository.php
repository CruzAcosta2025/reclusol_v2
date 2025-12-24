<?php

namespace App\Repositories\Implementations;

use Illuminate\Support\Facades\DB;
use App\Models\Cargo;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Interfaces\CargoRepositoryInterface;

class CargoRepository extends BaseRepository implements CargoRepositoryInterface
{

    public function __construct(Cargo $model)
    {
        parent::__construct($model);
    }

    public function forSelectByTipo(?string $tipoCodigo): array
    {
        return $this->model->vigentes()
            ->porTipo($tipoCodigo)
            ->pluck('DESC_CARGO', 'CODI_CARG')
            ->toArray();
    }

    public function forSelect(): array
    {
        return $this->model->vigentes()->pluck('DESC_CARGO', 'CODI_CARG')->toArray();
    }

    public function obtenerPorTipoPersonalYTipoCargo(string $tipoPersonal, string $tipoCargo): array
    {
        if (!$tipoPersonal || !$tipoCargo)
            return [];

        return DB::connection('sqlsrv')->select(
            'EXEC dbo.REC_CARGOS_POR_TIPO ?, ?',
            [$tipoPersonal, $tipoCargo]
        );
    }

    public function obtenerTipoCargo(string $codigoCargo): ?string
    {
        return $this->model->where('CODI_CARG', $codigoCargo)->value('CARGO_TIPO');
    }
}