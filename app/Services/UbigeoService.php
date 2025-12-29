<?php

namespace App\Services;

use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;

class UbigeoService
{
    
    public function __construct(
        protected Departamento $departamentoModel,
        protected Provincia $provinciaModel,
        protected Distrito $distritoModel)
    {}

    public function getDepartamentos(): array
    {
        return $this->departamentoModel->forSelectPadded()->toArray();
    }

    public function getProvincias(?string $depaCodigo = null): array
    {
        $q = $this->provinciaModel->newQuery()->vigentes();
        if ($depaCodigo !== null && $depaCodigo !== '') {
            $q->where('DEPA_CODIGO', $depaCodigo);
        }

        return $q->get(['PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO'])
            ->map(function ($p) {
                return [
                    'PROVI_CODIGO' => (string) $p->PROVI_CODIGO,
                    'PROVI_DESCRIPCION' => (string) $p->PROVI_DESCRIPCION,
                    'DEPA_CODIGO' => (string) $p->DEPA_CODIGO,
                ];
            })
            ->values()
            ->all();
    }

    public function getDistritos(?string $provCodigo = null): array
    {
        $q = $this->distritoModel->newQuery()->vigentes();
        if ($provCodigo !== null && $provCodigo !== '') {
            $q->where('PROVI_CODIGO', $provCodigo);
        }

        return $q->get(['DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO'])
            ->map(function ($d) {
                return [
                    'DIST_CODIGO' => (string) $d->DIST_CODIGO,
                    'DIST_DESCRIPCION' => (string) $d->DIST_DESCRIPCION,
                    'PROVI_CODIGO' => (string) $d->PROVI_CODIGO,
                ];
            })
            ->values()
            ->all();
    }
}