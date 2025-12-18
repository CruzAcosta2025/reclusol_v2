<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use Exception;

class BaseRepository implements BaseRepositoryInterface
{

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function get(array $conditions = [], $columns = ['*'])
    {
        return $this->model->where($conditions)->get($columns);
    }

    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function getBy(string $field, $value)
    {
        return $this->model->where($field, $value)->get();
    }

    public function getByIdWithRelations(int $id, array $relations = [], $columns = ['*'])
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * @throws \Throwable
     */
    public function transaction(callable $callback)
    {
        DB::beginTransaction();
        try {
            $result = $callback();
            DB::commit();
            return $result;
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Transaction error: " . $e->getMessage());
            throw $e;
        }
    }
}
