<?php

namespace App\Repositories\Interfaces;

use App\Models\Requerimiento;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RequerimientosRepositoryInterface extends BaseRepositoryInterface
{
    public function getByIdWithRelations(int|string $id, array $relations = [], $columns = ['*']);
    public function getEstados(): array;
    public function getPrioridades(): array;
    public function getSucursales(): array;
    public function getTipoCargos(): array;
    public function getCargos(): array;
    public function getTipoPersonal(): array;
    public function getClientes(): array;
    public function getClientesPorSucursal(string $sucursal, ?string $buscar = null): array;
    public function getSedesPorCliente(string $codigo): array;
    public function getTiposPorTipoPersonal(string $tipoPersonal): array;
    public function getCargosPorTipo(string $tipoPersonal, string $tipoCargo): array;
    public function getTipoCargo(string $codiCarg): ?string;
    public function getDepartamentos(): array;
    public function getProvincias(): array;
    public function getDistritos(): array;
    public function getNivelesEducativos(): array;
}
