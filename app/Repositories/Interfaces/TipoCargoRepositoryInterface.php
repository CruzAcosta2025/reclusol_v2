<?php

namespace App\Repositories\Interfaces;

interface TipoCargoRepositoryInterface
{
    public static function forSelect(): array;

    public function obtenerPorTipoPersonal(string $tipoPersonal): array;
}