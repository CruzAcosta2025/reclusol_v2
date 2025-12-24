<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use App\Models\Entrevista;
use App\Models\Postulante;
use App\Repositories\Implementations\BaseRepository;
use App\Repositories\Interfaces\EntrevistaRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class EntrevistaRepository extends BaseRepository implements EntrevistaRepositoryInterface
{
    public function __construct(Entrevista $modelo)
    {
        parent::__construct($modelo);
    }

    public function obtenerAptosParaEntrevista(array $filtros = []): LengthAwarePaginator
    {
        // Esta consulta debe ejecutarse sobre el modelo Postulante (igual que en el controlador)
        $query = Postulante::with([
            'requerimiento',
            'entrevistas.entrevistador',
        ])
            ->where('estado', 2)
            ->where('decision', 'apto');

        if (!empty($filtros['dni'])) {
            $query->where('dni', 'like', '%' . $filtros['dni'] . '%');
        }

        if (!empty($filtros['nombre'])) {
            $nombre = $filtros['nombre'];
            $query->where(function ($q) use ($nombre) {
                $q->where('nombres', 'like', "%{$nombre}%")
                    ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
        }

        return $query
            ->orderBy('fecha_postula')
            ->paginate(15)
            ->withQueryString();
    }

    public function consultarPersonalCesado(?string $dni, ?string $nombre): Collection
    {
        if (!$dni && !$nombre) {
            return collect();
        }

        return collect(DB::select(
            'EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre',
            ['dni' => $dni, 'nombre' => $nombre]
        ));
    }
}
