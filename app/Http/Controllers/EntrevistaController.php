<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;

class EntrevistaController extends Controller
{
    /* 
    public function listadoInicial()
    {
        $postulantes = Postulante::where('estado', 1) // 1 = EN PROCESO
            ->orderBy('fecha_postula', 'asc')
            ->paginate(15);

        return view('entrevistas.listadoInicial', compact('postulantes'));
    }
    */

    public function listadoInicial(Request $request)
    {
        // Filtra siempre por estado = 1 (EN PROCESO)
        $query = Postulante::where('estado', 1);

        // Filtro por DNI si lo ingresaron
        if ($request->filled('dni')) {
            $query->where('dni', 'like', '%' . $request->dni . '%');
        }

        // Filtro por nombre o apellido si lo ingresaron
        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->where(function ($q) use ($nombre) {
                $q->where('nombres', 'like', "%{$nombre}%")
                    ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
        }

        $postulantes = $query->orderBy('fecha_postula', 'asc')->paginate(15)->withQueryString();

        $listaNegra = collect();
        if ($request->filled('dni') || $request->filled('nombre')) {
            // Supón que tu SP recibe @dni y @nombre (ajusta si tiene otros parámetros)
            $dni = $request->dni ?? null;
            $nombre = $request->nombre ?? null;

            // Puedes ajustar los nombres de los parámetros según el SP que creaste
            $listaNegra = collect(DB::select('EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre', [
                'dni' => $dni,
                'nombre' => $nombre
            ]));
        }

        return view('entrevistas.listadoInicial', compact('postulantes','listaNegra'));
    }
}
