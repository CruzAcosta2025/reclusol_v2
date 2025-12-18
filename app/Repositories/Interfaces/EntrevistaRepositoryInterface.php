<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface EntrevistaRepositoryInterface
{
    public function obtenerAptosParaEntrevista(array $filtros = []): LengthAwarePaginator;
    public function consultarPersonalCesado(?string $dni, ?string $nombre): Collection;

}
