<?php

namespace App\Repositories\Interfaces;

interface CargoRepositoryInterface extends BaseRepositoryInterface
{
    public function forSelect(): array;

    public function forSelectByTipo(?string $tipoCodigo): array;

    public function obtenerPorTipoPersonalYTipoCargo(string $tipoPersonal, string $tipoCargo): array;

    public function obtenerTipoCargo(string $codigoCargo): ?string;
}