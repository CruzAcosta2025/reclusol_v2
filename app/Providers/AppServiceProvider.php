<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Catalogo;
use Illuminate\Support\Facades\View;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
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
    }
}
