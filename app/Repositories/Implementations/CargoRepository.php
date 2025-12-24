<?php

namespace App\Repositories\Implementations;

use App\Models\Cargo;
use App\Repositories\Interfaces\CargoRepositoryInterface;

class CargoRepository implements CargoRepositoryInterface
{
    public function forSelectByTipo($tipoCodigo)
    {
        return Cargo::forSelectByTipo($tipoCodigo);
    }

    public function forSelectByTipo(string $tipoCodigo): array
    {
        return Cargo::vigentes()
            ->porTipo($tipoCodigo)
            ->pluck('DESC_CARGO', 'CODI_CARG')
            ->toArray();
    }

    public function forSelect(): array
    {
        return Cargo::vigentes()->pluck('DESC_CARGO', 'CODI_CARG')->toArray();
    }
}