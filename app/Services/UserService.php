<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected UserRepositoryInterface $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function update(int|string $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int|string $id): bool
    {
        return $this->repository->delete($id);
    }

    public function listar(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginadoConFiltros($filters, $perPage);
    }

    public function crear(array $data, ?string $rol = null): User
    {
        // Reglas de negocio podrían ir aquí
        // ej: validar cargo, verificar permisos, etc.

        return $this->repository->crearConRol($data, $rol);
    }

    public function actualizar(int|string $id, array $data, ?string $rol = null): User
    {
        return $this->repository->actualizarConRol($id, $data, $rol);
    }

    public function alternarHabilitado(int|string $id): bool
    {
        return $this->repository->alternarHabilitado($id);
    }

    public function alternarVerificacionEmail(int|string $id): string
    {
        return $this->repository->alternarVerificacionEmail($id);
    }

    public function buscarPorUsuario(string $usuario): ?User
    {
        return $this->repository->buscarPorUsuario($usuario);
    }

    public function buscarParaSelect(?string $q = null, int $limit = 50): Collection
    {
        return $this->repository->buscarParaSelect($q, $limit);
    }

    public function obtenerPorIds(array $ids): Collection
    {
        return $this->repository->buscarPorIdsConRelaciones($ids);
    }

    public function obtenerPorId(int|string $id): ?User
    {
        return $this->repository->buscarPorIdConRelaciones($id);
    }

    public function obtenerEstadisticas(): array
    {
        return $this->repository->estadisticas();
    }

    public function esAdministrador(User $user): bool
    {
        return $user->tieneCargo('ADM');
    }
}