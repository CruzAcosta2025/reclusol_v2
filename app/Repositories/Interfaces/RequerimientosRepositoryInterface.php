<?php

namespace App\Repositories\Interfaces;

use App\Models\Requerimiento;
use Illuminate\Database\Eloquent\Collection;

interface RequerimientosRepositoryInterface
{
    public function getAll(): Collection;
    public function getById(mixed $id): ?Requerimiento;
    public function getByIdWithRelations(mixed $id): ?array;
    public function store(array $data);
    public function update($id, $data);
    public function destroy($id); //cambiar
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
