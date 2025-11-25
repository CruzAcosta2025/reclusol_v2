<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use App\Models\Cargo;
use Illuminate\Support\Str;
use App\Models\Sucursal;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\TipoCargo;
use App\Models\Entrevista;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;


class EntrevistaController extends Controller
{

    public function listadoInicial(Request $request)
    {
        // SOLO postulantes aptos para entrevista
        $query = Postulante::where('estado', 2)      // código de "apto"
            ->where('decision', 'apto');

        // Filtro por DNI
        if ($request->filled('dni')) {
            $query->where('dni', 'like', '%' . $request->dni . '%');
        }

        // Filtro por nombre o apellido
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

        // --- Lista negra (igual que antes) ---
        $listaNegra = collect();
        if ($request->filled('dni') || $request->filled('nombre')) {
            $dni    = $request->dni ?? null;
            $nombre = $request->nombre ?? null;

            $listaNegra = collect(DB::select(
                'EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre',
                ['dni' => $dni, 'nombre' => $nombre]
            ));
        }

        // --- Catálogos para mostrar nombres en vez de códigos ---
        $cargos        = Cargo::forSelect();        // ['0008' => 'AGENTE DE PROTECCIÓN', ...]
        $departamentos = Departamento::forSelect(); // ['12'   => 'JUNÍN', ...]
        $provincias    = Provincia::forSelect();
        $distritos     = Distrito::forSelect();

        // Decorar cada postulante con los nombres legibles
        foreach ($postulantes as $p) {
            // ajusta el padding según cómo guardas los códigos en la tabla de cargos
            $codigoCargo = str_pad($p->cargo, 4, '0', STR_PAD_LEFT);

            $p->cargo_nombre        = $cargos->get($codigoCargo) ?? $p->cargo;
            $p->departamento_nombre = $departamentos->get($p->departamento) ?? $p->departamento;
            $p->provincia_nombre    = $provincias->get($p->provincia) ?? $p->provincia;
            $p->distrito_nombre     = $distritos->get($p->distrito) ?? $p->distrito;
        }

        return view('entrevistas.listadoInicial', compact('postulantes', 'listaNegra'));
    }

    public function evaluar(Request $request, Postulante $postulante)
    {
        // Solo permitir evaluar a aptos
        if ((int)$postulante->estado !== 2 || $postulante->decision !== 'apto') {
            return redirect()
                ->route('entrevistas.index')
                ->with('error', 'El postulante no está apto para entrevista.');
        }

        // Catálogos para nombres legibles
        $tipoCargos    = TipoCargo::forSelect();
        $cargos        = Cargo::forSelect();
        $departamentos = Departamento::forSelect();
        $distritos = Distrito::forSelect();

        $tipo  = str_pad((string)$postulante->tipo_cargo,   2, '0', STR_PAD_LEFT);
        $cargo = str_pad((string)$postulante->cargo,        4, '0', STR_PAD_LEFT);
        $depa  = str_pad((string)$postulante->departamento, 2, '0', STR_PAD_LEFT);
        $disti = str_pad((string)$postulante->distrito, 6, '0', STR_PAD_LEFT);

        $postulante->tipo_cargo_nombre   = $tipoCargos->get($tipo) ?? $postulante->tipo_cargo;
        $postulante->cargo_nombre        = $cargos->get($cargo) ?? $postulante->cargo;
        $postulante->departamento_nombre = $departamentos->get($depa) ?? $postulante->departamento;
        $postulante->distrito_nombre = $distritos->get($disti) ?? $postulante->distrito;

        // Determinar si es operativo o administrativo (para luego incluir el parcial adecuado)
        $esOperativo = $postulante->tipo_personal_codigo === '01'
            || strtoupper($postulante->tipo_personal) === 'OPERATIVO';

        // Si quieres, podrías cargar la última entrevista para prefillear
        $entrevista = $postulante->entrevistas()->latest('fecha_entrevista')->first();

        return view('entrevistas.evaluar', compact('postulante', 'esOperativo', 'entrevista'));
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


    /*
    public function guardarEvaluacion(Request $request, Postulante $postulante)
    {
        $esBorrador = $request->boolean('borrador');

        // Determinar tipo de personal (por si luego añades campos específicos de operativo/administrativo)
        $esOperativo = $postulante->tipo_personal_codigo === '01'
            || strtoupper($postulante->tipo_personal) === 'OPERATIVO';

        // Reglas de validación
        $rules = [
            'sueldo_basico'           => ['nullable', 'numeric', 'min:0'],
            'bonificaciones'          => ['nullable', 'numeric', 'min:0'],
            'beneficios'              => ['nullable', 'string'],
            'comentarios_evaluacion'  => ['nullable', 'string'],

            'experiencia_previa'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_inmediata'  => ['nullable', Rule::in(['si', 'no'])],
            'horarios_rotativos'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_viajes'     => ['nullable', Rule::in(['si', 'no'])],
            'herramientas_tecnologicas' => ['nullable', Rule::in(['si', 'no'])],
            'referencias_laborales'     => ['nullable', Rule::in(['si', 'no'])],
        ];

        // Para envío final, "apto_puesto" es obligatorio; para borrador no
        $rules['apto_puesto'] = $esBorrador
            ? ['nullable', Rule::in(['si', 'no', 'otro_puesto'])]
            : ['required', Rule::in(['si', 'no', 'otro_puesto'])];

        if (!$esBorrador) {
            // si marca "otro_puesto" debe especificar cuál
            $rules['otro_puesto_especifico'] = ['nullable', 'string', 'max:150'];
        } else {
            $rules['otro_puesto_especifico'] = ['nullable', 'string', 'max:150'];
        }

        $data = $request->validate($rules);

        // Armar estructura JSON para la columna "preguntas"
        $preguntas = [
            'tipo_formato' => $esOperativo ? 'operativo' : 'administrativo',
            'esquema_remunerativo' => [
                'sueldo_basico'  => $data['sueldo_basico']  ?? null,
                'bonificaciones' => $data['bonificaciones'] ?? null,
                'beneficios'     => $data['beneficios']     ?? null,
            ],
            'evaluacion_aptitud' => [
                'apto_puesto'           => $data['apto_puesto']           ?? null,
                'otro_puesto_especifico' => $data['otro_puesto_especifico'] ?? null,
                'comentarios'           => $data['comentarios_evaluacion'] ?? null,
            ],
            'preguntas_adicionales' => [
                'experiencia_previa'        => $data['experiencia_previa']        ?? null,
                'disponibilidad_inmediata'  => $data['disponibilidad_inmediata']  ?? null,
                'horarios_rotativos'        => $data['horarios_rotativos']        ?? null,
                'disponibilidad_viajes'     => $data['disponibilidad_viajes']     ?? null,
                'herramientas_tecnologicas' => $data['herramientas_tecnologicas'] ?? null,
                'referencias_laborales'     => $data['referencias_laborales']     ?? null,
            ],
        ];

        // Determinar resultado "global" para la columna resultado
        $resultado = null;
        if (!empty($data['apto_puesto'])) {
            switch ($data['apto_puesto']) {
                case 'si':
                    $resultado = 'apto';
                    break;
                case 'no':
                    $resultado = 'no_apto';
                    break;
                case 'otro_puesto':
                    $resultado = 'apto_otro_puesto';
                    break;
            }
        }

        // Crear o actualizar la entrevista de ese postulante
        $entrevista = Entrevista::updateOrCreate(
            [
                'postulante_id'  => $postulante->id,
                // Si luego tienes requerimiento_id lo puedes pasar desde el form
                'requerimiento_id' => $request->input('requerimiento_id'),
            ],
            [
                'entrevistador_id' => Auth::id(),
                'fecha_entrevista' => now(),
                'preguntas'        => $preguntas,
                'comentario_final' => $data['comentarios_evaluacion'] ?? null,
                'resultado'        => $resultado,
            ]
        );

        // Respuesta si viene como "borrador" (AJAX)
        if ($esBorrador) {
            return response()->json([
                'success' => true,
                'entrevista_id' => $entrevista->id,
            ]);
        }

        // Respuesta normal (submit final)
        return redirect()
            ->route('entrevistas.index')
            ->with('success', 'Evaluación registrada correctamente.');
    }
    */

    public function guardarEvaluacion(Request $request, Postulante $postulante)
    {
        $esBorrador = $request->boolean('borrador');

        // Reglas base
        $rules = [
            // Formación / cursos (checkboxes → array)
            'formacion'       => ['nullable', 'array'],
            'formacion.*'     => ['string', 'max:50'],

            'otros_cursos'    => ['nullable', 'string', 'max:255'],

            // Textareas del formato
            'fortalezas'      => ['nullable', 'string'],
            'oportunidades'   => ['nullable', 'string'],

            // Comentarios generales de la evaluación
            'comentarios_evaluacion' => ['nullable', 'string'],

            // Preguntas adicionales (las de tu bloque final)
            'experiencia_previa'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_inmediata'  => ['nullable', Rule::in(['si', 'no'])],
            'horarios_rotativos'        => ['nullable', Rule::in(['si', 'no'])],
            'disponibilidad_viajes'     => ['nullable', Rule::in(['si', 'no'])],
            'herramientas_tecnologicas' => ['nullable', Rule::in(['si', 'no'])],
            'referencias_laborales'     => ['nullable', Rule::in(['si', 'no'])],
        ];

        // Apto para el puesto (radio)
        $rules['apto_puesto'] = $esBorrador
            ? ['nullable', Rule::in(['si', 'no', 'otro_puesto'])]
            : ['required', Rule::in(['si', 'no', 'otro_puesto'])];

        $rules['otro_puesto_especifico'] = ['nullable', 'string', 'max:150'];

        $data = $request->validate($rules);

        // ------------ ARMANDO LOS CAMPOS PARA LA BD ------------

        // Formación: guardamos como array (Eloquent lo serializa a JSON)
        $formacion = $data['formacion'] ?? [];

        // Competencias y preguntas adicionales juntas en un JSON
        $competencias = [
            'experiencia_previa'        => $data['experiencia_previa']        ?? null,
            'disponibilidad_inmediata'  => $data['disponibilidad_inmediata']  ?? null,
            'horarios_rotativos'        => $data['horarios_rotativos']        ?? null,
            'disponibilidad_viajes'     => $data['disponibilidad_viajes']     ?? null,
            'herramientas_tecnologicas' => $data['herramientas_tecnologicas'] ?? null,
            'referencias_laborales'     => $data['referencias_laborales']     ?? null,
        ];

        // es_apto: guardamos el mismo valor del radio (si / no / otro_puesto)
        $esApto = $data['apto_puesto'] ?? null;

        // ------------ CREAR / ACTUALIZAR ENTREVISTA ------------

        $entrevista = Entrevista::updateOrCreate(
            [
                'postulante_id'   => $postulante->id,
                'requerimiento_id' => $request->input('requerimiento_id'), // si aún no lo usas, puede ir null
            ],
            [
                'entrevistador_id' => Auth::id(),
                'fecha_entrevista' => now(),

                'formacion'     => $formacion,
                'otros_cursos'  => $data['otros_cursos'] ?? null,
                'competencias'  => $competencias,
                'fortalezas'    => $data['fortalezas'] ?? null,
                'oportunidades' => $data['oportunidades'] ?? null,

                'es_apto'       => $esApto,
                'otro_puesto'   => $data['otro_puesto_especifico'] ?? null,
                'comentario'    => $data['comentarios_evaluacion'] ?? null,
            ]
        );

        // ------------ RESPUESTA SEGÚN SI ES BORRADOR O FINAL ------------

        if ($esBorrador) {
            // Para tu fetch() en "Guardar Borrador"
            return response()->json([
                'success'       => true,
                'entrevista_id' => $entrevista->id,
            ]);
        }

        // Submit normal del formulario (Finalizar Evaluación)
        return redirect()
            ->route('entrevistas.index')
            ->with('success', 'Evaluación registrada correctamente.');
    }
}
