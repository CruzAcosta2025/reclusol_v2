<?php

namespace App\Repositories\Implementations;

use Illuminate\Support\Facades\DB;
use App\Models\TipoCargo;
use App\Repositories\Interfaces\TipoCargoRepositoryInterface;

class TipoCargoRepository implements TipoCargoRepositoryInterface
{

    protected TipoCargo $model;

    public function __construct(TipoCargo $model)
    {
        $this->model = $model;
    }

    public static function forSelect(): array
    {
        return TipoCargo::orderBy('DESC_TIPO_CARG')->pluck('DESC_TIPO_CARG', 'CODI_TIPO_CARG')->toArray();
    }

    public function obtenerPorTipoPersonal(string $tipoPersonal): array
    {
        if (!$tipoPersonal)
            return [];

        return DB::connection('sqlsrv')->select('EXEC dbo.REC_TIPOS_CARGO_POR_TIPO_PERSONAL ?', [$tipoPersonal]);
    }
}