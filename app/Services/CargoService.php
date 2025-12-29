<?php

namespace App\Services;

use App\Repositories\Interfaces\CargoRepositoryInterface;

class CargoService
{
    protected CargoRepositoryInterface $repository;

    public function __construct(CargoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function find(int $id)
    {
        return $this->repository->getById($id);
    }

    public function forSelectByTipo(?string $tipoCodigo): array
    {
        return $this->repository->forSelectByTipo($tipoCodigo);
    }
    
    public function forSelect(): array
    {
        return $this->repository->forSelect();
    }

    public function obtenerPorTipoPersonalYTipoCargo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->repository->obtenerPorTipoPersonalYTipoCargo($tipoPersonal, $tipoCargo);
    }

}