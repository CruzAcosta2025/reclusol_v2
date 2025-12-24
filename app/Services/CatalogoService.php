<?php

namespace App\Services;

use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\Provincia;
use App\Models\Sucursal;
use App\Repositories\Implementations\ClienteRepository;
use App\Repositories\Implementations\CargoRepository;
use App\Repositories\Implementations\TipoCargoRepository;

class CatalogoService
{
   public function __construct(
        protected CargoRepository $cargoRepository,
        protected TipoCargoRepository $tipoCargoRepository,
        protected ClienteRepository $clienteRepository,
        protected Departamento $departamentoModel,
        protected Provincia $provinciaModel,
        protected Distrito $distritoModel,
        protected Sucursal $sucursalModel,
    ) {}

    public function requerimiento(): array
    {
        return [
            'cargos'        => $this->cargoRepository->forSelect(),
            'tipos_cargo'   => $this->tipoCargoRepository->forSelect(),
            'departamentos' => $this->departamentoModel->forSelect(),
            'provincias'    => $this->provinciaModel->forSelect(),
            'distritos'     => $this->distritoModel->forSelect(),
            'clientes'      => $this->clienteRepository->getPorSucursal(''),
            'sucursales'    => $this->sucursalModel->forSelect(),
        ];
    }

    public function obtenerCargos(): array
    {
        return $this->cargoRepository->forSelect();
    }

    public function obtenerPorTipoPersonalYTipoCargo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->cargoRepository->obtenerPorTipoPersonalYTipoCargo($tipoPersonal, $tipoCargo);
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
        return $this->departamentoModel->forSelect();
    }

    public function obtenerProvincias()
    {
        return $this->provinciaModel->forSelect();
    }

    public function obtenerDistritos()
    {
        return $this->distritoModel->forSelect();
    }

    public function obtenerClientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        return $this->clienteRepository->getPorSucursal($sucursal, $buscar);
    }

    public function obtenerSedesPorCliente(string $codigo): array
    {
        return $this->clienteRepository->getSedesPorCliente($codigo);
    }
}