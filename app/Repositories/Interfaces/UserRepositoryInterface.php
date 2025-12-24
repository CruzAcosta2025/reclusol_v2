<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Repositories\Interfaces\BaseRepositoryInterface;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function paginadoConFiltros(array $filters, int $perPage = 15): LengthAwarePaginator;

    public function estadisticas(): array;

    public function buscarPorUsuario(string $usuario): ?User;

    public function crearConRol(array $data, ?string $rol = null): User;

    public function actualizarConRol(int|string $id, array $data, ?string $rol = null): User;

    public function alternarHabilitado(int|string $id): bool;

    public function alternarVerificacionEmail(int|string $id): string;

    public function obtenerNombresPorIds(array $ids): array;

    public function buscarParaSelect(?string $q = null, int $limit = 50): Collection;

    public function buscarPorIdsConRelaciones(array $ids, array $relations = []): Collection;

    public function buscarPorIdConRelaciones(int|string $id, array $relations = []): ?User;

    public function buscarPorCargo(string $codigoCargo): Collection;
}