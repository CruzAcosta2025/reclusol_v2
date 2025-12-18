<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    protected User $modelo;

    public function __construct(User $modelo)
    {
        $this->modelo = $modelo;
    }
}
