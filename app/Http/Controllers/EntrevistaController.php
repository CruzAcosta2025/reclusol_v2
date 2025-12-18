<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Distrito;
use App\Models\TipoCargo;
use App\Models\Entrevista;
use App\Services\EntrevistaService;

class EntrevistaController extends Controller
{

    protected EntrevistaService $entrevistaService;

    public function __construct(EntrevistaService $entrevistaService)
    {
        $this->entrevistaService = $entrevistaService;
    }

    public function listadoInicial(Request $request)
    {
        $data = $this->entrevistaService
            ->prepararListadoEntrevistas(
                $request->only('dni', 'nombre')
            );

        $listaNegra = $this->entrevistaService
            ->verificarListaNegra(
                $request->dni,
                $request->nombre
            );

        return view('entrevistas.listadoInicial', array_merge($data, [
            'listaNegra' => $listaNegra,
        ]));
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

    public function store(Request $request, Postulante $postulante)
    {
        return $this->guardarEvaluacion($request, $postulante);
    }

    public function guardarEvaluacion(Request $request, Postulante $postulante)
    {
        $esBorrador = $request->boolean('borrador');

        // Reglas base
        $rules = [
            'formacion'       => ['nullable', 'array'],
            'formacion.*'     => ['string', 'max:50'],

            'competencias'    => ['nullable', 'array'],
            'competencias.*'  => ['string', 'max:100'],

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


        $entrevista = Entrevista::updateOrCreate(
            ['postulante_id' => $postulante->id],
            [
                'requerimiento_id' => $postulante->requerimiento_id,
                'entrevistador_id' => Auth::id(),
                'fecha_entrevista' => now(),

                'formacion'     => $formacion,
                'otros_cursos'  => $data['otros_cursos'] ?? null,
                'competencias'  => $competencias,
                'fortalezas'    => $data['fortalezas'] ?? null,
                'oportunidades' => $data['oportunidades'] ?? null,

                'es_apto'           => $esApto,
                'otro_puesto'       => $data['otro_puesto_especifico'] ?? null,
                'comentario'        => $data['comentarios_evaluacion'] ?? null,
                'comentario_final'  => $data['comentarios_evaluacion'] ?? null,
                'resultado'       => $estadoEntrevista,
            ]
        );

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
}
