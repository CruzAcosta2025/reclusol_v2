<?php

namespace App\Repositories\Implementations;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\Interfaces\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{

    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll(array $columns = ['*'])
    {
        return $this->model->newQuery()->get($columns);
    }

    public function getById(int|string $id)
    {
        return $this->model->find($id);
    }

    public function getByIdWithRelations(int|string $id, array $relations = [], $columns = ['*'])
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int|string $id, array $data)
    {
        $model = $this->model->findOrFail($id);
        $model->update($data);
        return $model;
    }

    public function delete(int|string $id)
    {
        return $this->model->destroy($id);
    }

    public function transaction(callable $callback)
    {
        return DB::transaction($callback);
    }
}
