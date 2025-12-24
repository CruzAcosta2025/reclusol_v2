<?php

namespace App\Repositories\Implementations;

use Illuminate\Database\Eloquent\Collection;
use App\Models\User;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function paginadoConFiltros(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->model->newQuery();

        $query->with(['tipoCargo', 'cargoRelation']);

        if (!empty($filters['buscar'])) {
            $buscar = $filters['buscar'];
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                  ->orWhere('usuario', 'like', "%{$buscar}%");
            });
        }

        if (!empty($filters['cargo'])) {
            $query->where('cargo', $filters['cargo']);
        }

        if (!empty($filters['estado'])) {
            if ($filters['estado'] === 'habilitado') {
                $query->where('habilitado', 1);
            } elseif ($filters['estado'] === 'inhabilitado') {
                $query->where('habilitado', 0);
            }
        }

        $query->orderBy('name');

        return $query->paginate($perPage);
    }

    public function estadisticas(): array
    {
        $total = $this->model->newQuery()->count();
        $active = $this->model->newQuery()->where('habilitado', 1)->count();
        $inactive = $this->model->newQuery()->where('habilitado', 0)->count();
        $now = Carbon::now();
        $newThisMonth = $this->model->newQuery()
            ->whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'newThisMonth' => $newThisMonth,
        ];
    }

    public function buscarPorUsuario(string $usuario): ?User
    {
        return $this->model->newQuery()->where('usuario', $usuario)->first();
    }

    public function crearConRol(array $data, ?string $role = null): User
    {
        return $this->transaction(function () use ($data, $role) {
            $user = $this->create($data);
            if ($role && method_exists($user, 'assignRole')) {
                $user->assignRole($role);
            }
            return $user;
        });
    }

    public function actualizarConRol(int|string $id, array $data, ?string $role = null): User
    {
        return $this->transaction(function () use ($id, $data, $role) {
            $user = $this->update($id, $data);
            if ($role && method_exists($user, 'syncRoles')) {
                $user->syncRoles([$role]);
            }
            return $user;
        });
    }

    public function alternarHabilitado(int|string $id): bool
    {
        $user = $this->getById($id);
        if (!$user) return false;
        $user->habilitado = !$user->habilitado;
        $user->save();
        return (bool)$user->habilitado;
    }

    public function alternarVerificacionEmail(int|string $id): string
    {
        $user = $this->getById($id);
        if (!$user) return 'inexistente';
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $user->save();
            return 'inhabilitado';
        }
        $user->email_verified_at = Carbon::now();
        $user->save();
        return 'activo';
    }

    public function obtenerNombresPorIds(array $ids): array
    {
        return $this->model->newQuery()->whereIn('id', $ids)->pluck('name', 'id')->toArray();
    }

    public function buscarParaSelect(?string $q = null, int $limit = 50): Collection
    {
        $query = $this->model->newQuery()
            ->select(['id', 'name', 'usuario', 'cargo'])
            ->with(['tipoCargo', 'cargoRelation']); // Eager load para usar accessor cargo_descripcion
        
        if ($q) {
            $query->where(function($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('usuario', 'like', "%{$q}%");
            });
        }
        
        return $query->limit($limit)->get();
    }

    public function buscarPorIdsConRelaciones(array $ids, array $relations = []): Collection
    {
        if (empty($relations)) {
            $relations = ['tipoCargo', 'cargoRelation'];
        }
        
        return $this->model->newQuery()->with($relations)->whereIn('id', $ids)->get();
    }

    public function buscarPorIdConRelaciones(int|string $id, array $relations = []): ?User
    {
        // Si no se especifican relaciones, carga las de cargo por defecto
        if (empty($relations)) {
            $relations = ['tipoCargo', 'cargoRelation'];
        }
        
        return $this->getByIdWithRelations($id, $relations);
    }

    /**
     * Encuentra usuarios que tienen un cargo específico usando el helper del modelo
     */
    public function buscarPorCargo(string $codigoCargo): Collection
    {
        return $this->model->newQuery()
            ->with(['tipoCargo', 'cargoRelation'])
            ->where('cargo', $codigoCargo)
            ->get();
    }
}
