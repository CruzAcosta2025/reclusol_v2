<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Http\View\Composers\SidebarComposer;
use App\Repositories\BaseRepository;
use App\Repositories\RequerimientosRepository;
use App\Repositories\EntrevistaRepository;
use App\Repositories\Interfaces\BaseRepositoryInterface;
use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use App\Repositories\Interfaces\EntrevistaRepositoryInterface;

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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register sidebar composer to dynamically build sidebar items
        View::composer('components.sidebar', SidebarComposer::class);
        /*
        View::composer('postulantes.registroPrimario', function ($view) {
            $departamentos = Catalogo::obtenerDepartamentos();
            $provincias = Catalogo::obtenerTodasProvincias();
            $view->with(compact(
                'departamentos',
                'provincias',
            ));
        });

        View::composer('requerimientos.requerimiento', function ($view) {
            $sucursales = Catalogo::obtenerSucursal();
            $tipoCargos = Catalogo::obtenerTipoCargo();
            $cargos = Catalogo::obtenerCargo();
            $niveles = Catalogo::obtenerNivelEducativo();
            $departamentos = Catalogo::obtenerDepartamentos();
            $provincias = Catalogo::obtenerTodasProvincias();
            $distritos = Catalogo::obtenerDistritos();
            $view->with(compact('sucursales', 'tipoCargos', 'cargos','niveles','departamentos','provincias','distritos'));
        });
        */
    }
}
