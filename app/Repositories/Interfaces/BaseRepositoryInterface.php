<?php

namespace App\Repositories\Interfaces;

interface BaseRepositoryInterface
{

    public function getAll(array $columns = ['*']);

    public function getById(int|string $id);

    public function getByIdWithRelations(int|string $id, array $relations = [], $columns = ['*']);

    public function create(array $data);

    public function update(int|string $id, array $data);
    
    public function delete(int|string $id);

    public function transaction(callable $callback);
}
