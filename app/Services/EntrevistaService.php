<?php

namespace App\Services;

use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Repositories\Interfaces\EntrevistaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EntrevistaService
{
    protected EntrevistaRepositoryInterface $repo;

    public function __construct(EntrevistaRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function prepararListadoEntrevistas(array $filtros = []): array
    {
        $postulantes = $this->repo->obtenerAptosParaEntrevista($filtros); //ver si se pasa por repo o como se llama al repository

        // Catálogos
        $cargos        = Cargo::forSelect();
        $departamentos = Departamento::forSelect();
        $provincias    = Provincia::forSelect();
        $distritos     = Distrito::forSelect();

        $entrevistados = 0;
        $pendientes    = 0;
        $cancelados    = 0;

        foreach ($postulantes as $p) {

            $codigoCargo = $p->cargo
                ? str_pad($p->cargo, 4, '0', STR_PAD_LEFT)
                : optional($p->requerimiento)->cargo_solicitado;

            $codigoCargo = $codigoCargo
                ? str_pad($codigoCargo, 4, '0', STR_PAD_LEFT)
                : null;

            $p->cargo_nombre = $codigoCargo
                ? ($cargos->get($codigoCargo) ?? $codigoCargo)
                : 'N/A';

            $p->departamento_nombre = $departamentos->get($p->departamento) ?? $p->departamento;
            $p->provincia_nombre    = $provincias->get($p->provincia) ?? $p->provincia;
            $p->distrito_nombre     = $distritos->get($p->distrito) ?? $p->distrito;

            $ultimaEntrevista = $p->entrevistas
                ->sortByDesc('fecha_entrevista')
                ->first();

            if (!$ultimaEntrevista) {
                $p->evaluado_por      = 'Aún no evaluado';
                $p->estado_entrevista = 'No evaluado';
                $pendientes++;
                continue;
            }

            $p->evaluado_por = optional($ultimaEntrevista->entrevistador)->name ?? 'Sin nombre';

            if ($ultimaEntrevista->resultado === 'BORRADOR') {
                $p->estado_entrevista = 'Borrador';
                $pendientes++;
            } else {
                $p->estado_entrevista = 'Evaluado';
                $entrevistados++;
            }
        }

        return compact(
            'postulantes',
            'entrevistados',
            'pendientes',
            'cancelados'
        );
    }

    public function obtenerAptosParaEntrevista(array $filtros = []): LengthAwarePaginator
    {
        return $this->repo->obtenerAptosParaEntrevista($filtros);
    }

    public function verificarListaNegra(?string $dni, ?string $nombre): Collection
    {
        return $this->repo->consultarPersonalCesado($dni, $nombre);
    }


}
