<?php

namespace App\Services;

use App\Repositories\Interfaces\ClienteRepositoryInterface;

class ClienteService
{
    protected ClienteRepositoryInterface $repository;

    public function __construct(ClienteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function obtenerClientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        return $this->repository->getPorSucursal($sucursal, $buscar);
    }

    public function obtenerSedesPorCliente(string $codigo): array
    {
        return $this->repository->getSedesPorCliente($codigo);
    }
}