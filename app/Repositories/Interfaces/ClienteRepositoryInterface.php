<?php

namespace App\Repositories\Interfaces;

interface ClienteRepositoryInterface
{
    public function getPorSucursal(string $sucursal, ?string $buscar = null): array;

    public function getSedesPorCliente(string $codigo): array;
}