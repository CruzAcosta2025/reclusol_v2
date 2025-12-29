<?php

namespace App\Repositories\Interfaces;

use App\Models\Requerimiento;
use App\Repositories\Interfaces\BaseRepositoryInterface;

interface RequerimientosRepositoryInterface extends BaseRepositoryInterface
{
	public function listarPaginado(array $filters, int $perPage = 15): array;
	public function obtenerStats(array $filters): array;
	public function obtenerFiltros(array $filters);
	public function getNivelesEducativos(): array;
}
