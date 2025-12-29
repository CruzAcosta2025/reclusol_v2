<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\SidebarComposer;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Implementations\RequerimientosRepository;
use App\Repositories\Implementations\EntrevistaRepository;
use App\Repositories\Implementations\CargoRepository;
use App\Repositories\Implementations\ClienteRepository;
use App\Repositories\Implementations\TipoCargoRepository;
use App\Repositories\Implementations\UserRepository;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use App\Repositories\Interfaces\EntrevistaRepositoryInterface;
use App\Repositories\Interfaces\CargoRepositoryInterface;
use App\Repositories\Interfaces\ClienteRepositoryInterface;
use App\Repositories\Interfaces\TipoCargoRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RequerimientosRepositoryInterface::class, RequerimientosRepository::class);
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);
        $this->app->bind(EntrevistaRepositoryInterface::class, EntrevistaRepository::class);
        $this->app->bind(CargoRepositoryInterface::class, CargoRepository::class);
        $this->app->bind(ClienteRepositoryInterface::class, ClienteRepository::class);
        $this->app->bind(TipoCargoRepositoryInterface::class, TipoCargoRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register sidebar composer to dynamically build sidebar items
        View::composer('components.sidebar', SidebarComposer::class);
    }
}
