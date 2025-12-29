<?php

namespace App\Services;

use App\Services\UbigeoService;
use App\Models\Sucursal;
use App\Repositories\Implementations\TipoCargoRepository;

class CatalogoService
{
   public function __construct(
        protected CargoService $cargoService,
        protected TipoCargoRepository $tipoCargoRepository,
        protected ClienteService $clienteService,
        protected UbigeoService $ubigeoService,
        protected Sucursal $sucursalModel,
    ) {}

    public function requerimiento(): array
    {
        return [
            'cargos'        => $this->cargoService->forSelect(),
            'tipos_cargo'   => $this->tipoCargoRepository->forSelect(),
            'departamentos' => $this->ubigeoService->getDepartamentos(),
            'provincias'    => $this->ubigeoService->getProvincias(),
            'distritos'     => $this->ubigeoService->getDistritos(),
            'clientes'      => $this->clienteService->obtenerClientesPorSucursal(''),
            'sucursales'    => $this->sucursalModel->forSelect(),
        ];
    }

    public function obtenerCargos(): array
    {
        return $this->cargoService->forSelect();
    }

    public function obtenerPorTipoPersonalYTipoCargo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->cargoService->obtenerPorTipoPersonalYTipoCargo($tipoPersonal, $tipoCargo);
    }

    public function obtenerTiposCargo(): array
    {
        return $this->tipoCargoRepository->forSelect();
    }

    public function obtenerSucursales()
    {
        return $this->sucursalModel->forSelect();
    }

    public function obtenerDepartamentos()
    {
        return $this->ubigeoService->getDepartamentos();
    }

    public function obtenerProvincias()
    {
        return $this->ubigeoService->getProvincias();
    }

    public function obtenerDistritos()
    {
        return $this->ubigeoService->getDistritos();
    }

    public function obtenerClientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        return $this->clienteService->obtenerClientesPorSucursal($sucursal, $buscar);
    }

    public function obtenerSedesPorCliente(string $codigo): array
    {
        return $this->clienteService->obtenerSedesPorCliente($codigo);
    }
}