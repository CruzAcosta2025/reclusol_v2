<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{

    public function all($columns = ['*']);

    public function get(array $conditions = [], $columns = ['*']);

    public function getById($id);

    public function getBy(string $field, $value);

    public function getByIdWithRelations(int $id, array $relations = [], $columns = ['*']);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function transaction(callable $callback);
}
