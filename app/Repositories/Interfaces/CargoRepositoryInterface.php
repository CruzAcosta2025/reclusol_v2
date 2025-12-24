<?php

namespace App\Repositories\Interfaces;

interface CargoRepositoryInterface
{
    public function forSelectByTipo($tipoCodigo);

    public function forSelect();
}