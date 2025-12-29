<?php

namespace App\Services;

use App\Repositories\Interfaces\TipoCargoRepositoryInterface;

class TipoCargoService
{
    protected TipoCargoRepositoryInterface $repository;

    public function __construct(TipoCargoRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function forSelect(): array
    {
        return $this->repository->forSelect();
    }

    public function obtenerPorTipoPersonal(string $tipoPersonal): array
    {
        return $this->repository->obtenerPorTipoPersonal($tipoPersonal);
    }

}