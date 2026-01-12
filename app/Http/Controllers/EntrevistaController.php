<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use App\Models\Cargo;
use App\Models\Cliente;
use Illuminate\Support\Str;
use App\Models\Sucursal;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\TipoCargo;
use App\Models\Entrevista;
use App\Services\WhatsappService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class EntrevistaController extends Controller
{

    public function listadoInicial(Request $request)
    {
        // Cargamos requerimiento + entrevistas con su entrevistador
        $query = Postulante::with([
            'requerimiento',
            'entrevistas.entrevistador', // 👈 relación en Entrevista
        ])
            ->where('estado', 2)      // apto
            ->where('decision', 'apto');

        // filtros
        if ($request->filled('dni')) {
            $query->where('dni', 'like', '%' . $request->dni . '%');
        }

        if ($request->filled('nombre')) {
            $nombre = $request->nombre;
            $query->where(function ($q) use ($nombre) {
                $q->where('nombres', 'like', "%{$nombre}%")
                    ->orWhere('apellidos', 'like', "%{$nombre}%");
            });
        }

        $postulantes = $query->orderBy('fecha_postula', 'asc')
            ->paginate(15)
            ->withQueryString();

        // catálogos
        $cargos        = Cargo::forSelect();        // ['0008' => 'AGENTE...', ...]
        $departamentos = Departamento::forSelect();
        $provincias    = Provincia::forSelect();
        $distritos     = Distrito::forSelect();

        // contadores para las tarjetas
        $entrevistados = 0;
        $pendientes    = 0;
        $cancelados    = 0; // por ahora 0

        $estadoCodigoPorId = DB::table('estado_entrevista')->pluck('codigo', 'id'); // [id => codigo]
        $estadoNombrePorId = DB::table('estado_entrevista')->pluck('nombre', 'id'); // [id => nombre]
        $estadoIdPorCodigo = DB::table('estado_entrevista')->pluck('id', 'codigo'); // [codigo => id]


        foreach ($postulantes as $p) {

            // === CARGO DESDE EL REQUERIMIENTO (simple) ===
            $codigoCargo = null;

            if (!empty($p->cargo)) {
                // compatibilidad con registros viejos
                $codigoCargo = str_pad($p->cargo, 4, '0', STR_PAD_LEFT);
            } elseif ($p->requerimiento) {
                // nuevos registros: lo sacamos del requerimiento
                $codigoCargo = str_pad($p->requerimiento->cargo_solicitado, 4, '0', STR_PAD_LEFT);
            }

            if ($codigoCargo) {
                $p->cargo_nombre = $cargos->get($codigoCargo) ?? $codigoCargo;
            } else {
                $p->cargo_nombre = 'N/A';
            }

            // Ubigeo
            $p->departamento_nombre = $departamentos->get($p->departamento) ?? $p->departamento;
            $p->provincia_nombre    = $provincias->get($p->provincia) ?? $p->provincia;
            $p->distrito_nombre     = $distritos->get($p->distrito) ?? $p->distrito;

            // === INFORMACIÓN DE ENTREVISTA (evaluado por / estado) ===
            $ultima = $p->entrevistas
                ->sortByDesc(function ($e) {
                    // si solo manejas 1 entrevista por postulante igual funciona,
                    // pero esto elige la "más reciente" por update/fecha
                    return $e->updated_at ?? $e->created_at ?? $e->fecha_entrevista;
                })
                ->first();

            $p->entrevista_id    = $ultima?->id;
            $p->fecha_entrevista = $ultima?->fecha_entrevista;

            // ===== ESTADO AGENDA (por estado_entrevista_id) =====
            $agendaId = $ultima?->estado_entrevista_id ?? ($estadoIdPorCodigo['SIN_PROGRAMAR'] ?? null);
            $p->estado_agenda_id     = $agendaId;
            $p->estado_agenda_codigo = $agendaId ? ($estadoCodigoPorId[$agendaId] ?? null) : 'SIN_PROGRAMAR';
            $p->estado_agenda_nombre = $agendaId ? ($estadoNombrePorId[$agendaId] ?? 'Sin programar') : 'Sin programar';

            // ===== ESTADO EVALUACIÓN (por resultado) =====
            $res = strtoupper(trim((string)($ultima?->resultado ?? '')));

            if ($res === 'EVALUADO') {
                $p->estado_entrevista = 'Evaluado';
                $p->evaluado_por      = optional($ultima->entrevistador)->name ?? 'Sin nombre';
            } elseif ($res === 'BORRADOR') {
                $p->estado_entrevista = 'Borrador';
                $p->evaluado_por      = optional($ultima->entrevistador)->name ?? 'Sin nombre';
            } else {
                $p->estado_entrevista = 'No evaluado';
                $p->evaluado_por      = 'Aún no evaluado';
            }

            // ===== CONTADORES (Cancelados/Cerrados no deben sumar pendientes) =====
            $agendaCodigo = $p->estado_agenda_codigo ?? 'SIN_PROGRAMAR';

            if (in_array($agendaCodigo, ['CANCELADA', 'CERRADA'], true)) {
                $cancelados++;
            } elseif ($p->estado_entrevista === 'Evaluado') {
                $entrevistados++;
            } else {
                $pendientes++;
            }
        }

        // lista negra igual que ya tenías
        $listaNegra = collect();
        if ($request->filled('dni') || $request->filled('nombre')) {
            $dni    = $request->dni ?? null;
            $nombre = $request->nombre ?? null;

            $listaNegra = collect(DB::select(
                'EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre',
                ['dni' => $dni, 'nombre' => $nombre]
            ));
        }

        return view('entrevistas.listadoInicial', compact(
            'postulantes',
            'listaNegra',
            'entrevistados',
            'cancelados',
            'pendientes'
        ));
    }



    public function evaluar(Request $request, Postulante $postulante)
    {
        // Solo permitir evaluar a aptos
        if ((int)$postulante->estado !== 2 || $postulante->decision !== 'apto') {
            return redirect()
                ->route('entrevistas.index')
                ->with('error', 'El postulante no está apto para entrevista.');
        }

        // Cargar el requerimiento asociado
        $postulante->load('requerimiento');

        // Catálogos
        $tipoCargos    = TipoCargo::forSelect();
        $cargos        = Cargo::forSelect();
        $departamentos = Departamento::forSelect();
        $distritos     = Distrito::forSelect();

        $tipo  = str_pad((string)$postulante->tipo_cargo,   2, '0', STR_PAD_LEFT);
        $depa  = str_pad((string)$postulante->departamento, 2, '0', STR_PAD_LEFT);
        $disti = str_pad((string)$postulante->distrito,     6, '0', STR_PAD_LEFT);

        $postulante->tipo_cargo_nombre   = $tipoCargos->get($tipo) ?? $postulante->tipo_cargo;
        $postulante->departamento_nombre = $departamentos->get($depa) ?? $postulante->departamento;
        $postulante->distrito_nombre     = $distritos->get($disti) ?? $postulante->distrito;

        // === AQUÍ LO SIMPLE PARA EL PUESTO ===
        if ($postulante->requerimiento) {
            // tomamos el código de cargo del requerimiento
            $codCargo = str_pad(
                (string)$postulante->requerimiento->cargo_solicitado,
                4,
                '0',
                STR_PAD_LEFT
            );

            // nombre legible del cargo
            $cargoNombre = $cargos->get($codCargo)
                ?? $postulante->requerimiento->cargo_solicitado;

            // Si quieres, puedes concatenar sucursal/cliente:
            // $sucursal = $postulante->requerimiento->sucursal_nombre ?? '';
            // $cliente  = $postulante->requerimiento->cliente_nombre  ?? '';
            // $postulante->puesto_postula = trim("$cargoNombre - $sucursal - $cliente", ' -');

            $postulante->puesto_postula = $cargoNombre;
        } else {
            // Fallback para postulantes viejos sin requerimiento_id
            $codCargo = str_pad((string)$postulante->cargo, 4, '0', STR_PAD_LEFT);
            $postulante->puesto_postula = $cargos->get($codCargo) ?? $postulante->cargo ?? 'N/A';
        }

        // Operativo / administrativo
        $esOperativo = $postulante->tipo_personal_codigo === '01'
            || strtoupper($postulante->tipo_personal) === 'OPERATIVO';

        $esAdministrativo = $postulante->tipo_personal_codigo === '02'
            || strtoupper($postulante->tipo_personal) === 'ADMINISTRATIVO';
        // Última entrevista, si existe
        $entrevista = $postulante->entrevistas()->latest('fecha_entrevista')->first();

        return view('entrevistas.evaluar', compact('postulante', 'esOperativo', 'esAdministrativo', 'entrevista'));
    }




    public function verArchivo(Postulante $postulante, string $tipo): StreamedResponse
    {

        $path = $postulante->{$tipo};
        $disk = config('filesystems.default', 'local');

        abort_if(!$path || !Storage::disk($disk)->exists($path), 404);

        return Storage::disk($disk)->response($path, basename($path), [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);

        /*
        abort_unless(in_array($tipo, ['cv', 'cul'], true), 404);
        $rel = $postulante->{$tipo};
        Log::info('verArchivo', [
            'postulante_id' => $postulante->id,
            'tipo'          => $tipo,
            'rel'           => $rel,
        ]);

        abort_if(empty($rel), 404);
        abort_if(!Storage::disk('postulantes')->exists($rel), 404);
        // Si quieres forzar PDF:
        // $ext = strtolower(pathinfo($rel, PATHINFO_EXTENSION));
        // abort_if($ext !== 'pdf', 404);
        return Storage::disk('postulantes')->response($rel, basename($rel), [
            'Content-Type'  => 'application/pdf',
            'Cache-Control' => 'private, max-age=3600',
        ]);
        */
    }

    public function descargarArchivo(Postulante $postulante, string $tipo)
    {
        abort_unless(in_array($tipo, ['cv', 'cul'], true), 404);

        $rel = $postulante->{$tipo};
        abort_if(empty($rel), 404);
        abort_if(!Storage::disk('postulantes')->exists($rel), 404);

        return Storage::disk('postulantes')->download($rel, basename($rel));
    }


    public function guardarEvaluacion(Request $request, Postulante $postulante)
    {
        $esBorrador = $request->boolean('borrador');

        // Reglas base
        $rules = [
            'formacion'       => ['nullable', 'array'],
            'formacion.*'     => ['string', 'max:50'],

            'competencias'    => ['nullable', 'array'],          // 👈 NUEVO
            'competencias.*'  => ['string', 'max:100'],          // 👈 NUEVO

            'otros_cursos'    => ['nullable', 'string', 'max:255'],
            'fortalezas'      => ['nullable', 'string'],
            'oportunidades'   => ['nullable', 'string'],
            'comentarios_evaluacion' => ['nullable', 'string'],

            'experiencia_previa'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_inmediata'  => ['nullable', Rule::in(['si', 'no'])],
            'horarios_rotativos'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_viajes'     => ['nullable', Rule::in(['si', 'no'])],
            'herramientas_tecnologicas' => ['nullable', Rule::in(['si', 'no'])],
            'referencias_laborales'     => ['nullable', Rule::in(['si', 'no'])],
        ];

        $rules['apto_puesto'] = $esBorrador
            ? ['nullable', Rule::in(['si', 'no', 'otro_puesto'])]
            : ['required', Rule::in(['si', 'no', 'otro_puesto'])];

        $rules['otro_puesto_especifico'] = ['nullable', 'string', 'max:150'];

        $data = $request->validate($rules);

        // ------------ ARMANDO LOS CAMPOS PARA LA BD ------------

        $formacion    = $data['formacion']    ?? [];
        $habilidades  = $data['competencias'] ?? [];

        $competencias = [
            'habilidades'              => $habilidades,                           // 👈 AQUÍ guardamos los checks
            'experiencia_previa'       => $data['experiencia_previa']        ?? null,
            'disponibilidad_inmediata' => $data['disponibilidad_inmediata']  ?? null,
            'horarios_rotativos'       => $data['horarios_rotativos']        ?? null,
            'disponibilidad_viajes'    => $data['disponibilidad_viajes']     ?? null,
            'herramientas_tecnologicas' => $data['herramientas_tecnologicas'] ?? null,
            'referencias_laborales'    => $data['referencias_laborales']     ?? null,
        ];

        $esApto = $data['apto_puesto'] ?? null;

        // Estado de la entrevista (para la lista)
        $estadoEntrevista = $esBorrador ? 'BORRADOR' : 'EVALUADO';


        // IDs de estados (por código)
        $estadoEntrevistadaId = DB::table('estado_entrevista')->where('codigo', 'ENTREVISTADA')->value('id');
        $estadoProgramadaId   = DB::table('estado_entrevista')->where('codigo', 'PROGRAMADA')->value('id');

        $entrevista = Entrevista::firstOrNew(['postulante_id' => $postulante->id]);

        $entrevista->requerimiento_id = $postulante->requerimiento_id;
        $entrevista->entrevistador_id = Auth::id();

        // ✅ NO pisar la fecha si ya estaba programada
        if (empty($entrevista->fecha_entrevista)) {
            // entrevista "mismo día" (si no estaba programada)
            $entrevista->fecha_entrevista = now();
        }

        // ✅ si ya estás guardando evaluación (borrador o final), lo más lógico es marcarla como ENTREVISTADA
        // si no existe ENTREVISTADA aún, al menos deja PROGRAMADA si está null
        if (!empty($estadoEntrevistadaId)) {
            $entrevista->estado_entrevista_id = $estadoEntrevistadaId;
        } elseif (empty($entrevista->estado_entrevista_id) && !empty($estadoProgramadaId)) {
            $entrevista->estado_entrevista_id = $estadoProgramadaId;
        }

        $entrevista->formacion     = $formacion;
        $entrevista->otros_cursos  = $data['otros_cursos'] ?? null;
        $entrevista->competencias  = $competencias;
        $entrevista->fortalezas    = $data['fortalezas'] ?? null;
        $entrevista->oportunidades = $data['oportunidades'] ?? null;

        $entrevista->es_apto          = $esApto;
        $entrevista->otro_puesto      = $data['otro_puesto_especifico'] ?? null;
        $entrevista->comentario       = $data['comentarios_evaluacion'] ?? null;
        $entrevista->comentario_final = $data['comentarios_evaluacion'] ?? null;
        $entrevista->resultado        = $estadoEntrevista;

        $entrevista->save();


        if ($esBorrador) {
            return response()->json([
                'success'       => true,
                'entrevista_id' => $entrevista->id,
            ]);
        }

        return redirect()
            ->route('entrevistas.index')
            ->with('success', 'Evaluación registrada correctamente.');
    }

    public function destroy(Entrevista $entrevista)
    {
        // ✅ Solo permitir eliminar borradores
        if ($entrevista->resultado !== 'BORRADOR') {
            return response()->json([
                'success' => false,
                'message' => 'Solo se puede eliminar una entrevista en borrador.',
            ], 422);
        }

        $entrevista->delete();

        return response()->json(['success' => true]);
    }

    public function programar(Request $request, Postulante $postulante)
    {
        $data = $request->validate([
            'fecha_entrevista' => ['required', 'date_format:Y-m-d\TH:i'],
        ]);

        $idProgramada   = DB::table('estado_entrevista')->where('codigo', 'PROGRAMADA')->value('id');
        $idReprogramada = DB::table('estado_entrevista')->where('codigo', 'REPROGRAMADA')->value('id');

        $entrevista = Entrevista::firstOrNew(['postulante_id' => $postulante->id]);

        $teniaFecha = !empty($entrevista->fecha_entrevista);

        $entrevista->requerimiento_id = $postulante->requerimiento_id;
        $entrevista->entrevistador_id = Auth::id();
        $entrevista->fecha_entrevista = $data['fecha_entrevista'];

        // si ya tenía fecha → reprogramada
        $entrevista->estado_entrevista_id = $teniaFecha ? ($idReprogramada ?? $idProgramada) : $idProgramada;

        $entrevista->save();

        return response()->json(['success' => true]);
    }


    public function cambiarEstado(Request $request, Postulante $postulante)
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(['SIN_PROGRAMAR', 'NO_ASISTIO', 'CANCELADA', 'CERRADA'])],
        ]);

        $estadoId = DB::table('estado_entrevista')->where('codigo', $data['estado'])->value('id');

        if (!$estadoId) {
            return response()->json(['success' => false, 'message' => 'Estado no encontrado.'], 422);
        }

        $entrevista = Entrevista::firstOrNew(['postulante_id' => $postulante->id]);
        $entrevista->requerimiento_id = $postulante->requerimiento_id;
        $entrevista->entrevistador_id = Auth::id();
        $entrevista->estado_entrevista_id = $estadoId;

        if ($data['estado'] === 'SIN_PROGRAMAR') {
            $entrevista->fecha_entrevista = null;
        }

        $entrevista->save();

        return response()->json(['success' => true]);
    }

}
