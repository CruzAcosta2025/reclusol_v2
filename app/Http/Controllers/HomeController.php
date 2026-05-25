<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Postulante;
use App\Models\Departamento;
use App\Models\Catalogo;
use Carbon\Carbon;
use App\Models\Requerimiento;
use App\Models\Entrevista;
use App\Models\Cargo;

class HomeController extends Controller
{
    public function index()
    {
        /* ---------- Totales absolutos ---------- */
        // En tu controlador del dashboard (o donde generas la vista)
        $notificaciones = auth()->user()->unreadNotifications()->take(5)->get();

        $totalPostulantes   = Postulante::count();
        $totalRequerimientos = Requerimiento::count();

        /* ---------- Ventanas de tiempo ---------- */
        $hoy    = Carbon::today();
        $hace30 = $hoy->copy()->subDays(30);
        $hace60 = $hoy->copy()->subDays(60);

        /* ---------- Postulantes: variación ---------- */
        $post_ult30   = Postulante::whereBetween('created_at', [$hace30, $hoy])->count();
        $post_prev30  = Postulante::whereBetween('created_at', [$hace60, $hace30])->count();
        $variacionPostulantes = $post_prev30
            ? round((($post_ult30 - $post_prev30) / $post_prev30) * 100, 1)
            : 0;    // si antes no había registros

        /* ---------- Requerimientos: variación ---------- */
        $req_ult30   = Requerimiento::whereBetween('created_at', [$hace30, $hoy])->count();
        $req_prev30  = Requerimiento::whereBetween('created_at', [$hace60, $hace30])->count();
        $variacionRequerimientos = $req_prev30
            ? round((($req_ult30 - $req_prev30) / $req_prev30) * 100, 1)
            : 0;

        /* ---------- Postulantes agrupados por ciudad (sede) ---------- */
        $porSede = Postulante::select('departamento', DB::raw('COUNT(*) as total'))
            ->groupBy('departamento')
            ->orderByDesc('total')
            ->get();

        $maxTotalSede = $porSede->max('total');    // para calcular porcentajes

        
        $requerimientos = Requerimiento::orderByDesc('created_at')->get(); // Puedes agregar filtros si deseas solo los activos o validados

        $departamentos = Departamento::forSelectPadded();

        foreach ($porSede as $sede) {
            // normaliza el código del postulante a 2 dígitos
            $codigo = str_pad(ltrim((string)$sede->departamento, '0'), 2, '0', STR_PAD_LEFT);
            $sede->nombre_departamento = $departamentos->get($codigo, 'Sin nombre');
        }

        /* ---------- Estado de postulantes ---------- */
        $aptoBase = Postulante::query()
            ->where(function ($q) {
                $q->where('decision', 'apto')
                    ->orWhere(function ($q2) {
                        $q2->whereNull('decision')
                            ->where('estado', 2);
                    });
            });

        $aptoTotal = (clone $aptoBase)->count();
        $enEntrevista = (clone $aptoBase)->whereHas('entrevistas')->count();
        $aptoSinEntrevista = max($aptoTotal - $enEntrevista, 0);

        $pendiente = Postulante::query()
            ->whereNull('decision')
            ->where(function ($q) {
                $q->whereNull('estado')
                    ->orWhereNotIn('estado', [2, 3]);
            })
            ->count();

        $noApto = Postulante::query()
            ->where(function ($q) {
                $q->where('decision', 'no_apto')
                    ->orWhere(function ($q2) {
                        $q2->whereNull('decision')
                            ->where('estado', 3);
                    });
            })
            ->count();

        $estadoPostulantes = [
            'Apto' => $aptoSinEntrevista,
            'Pendiente' => $pendiente,
            'En entrevista' => $enEntrevista,
            'No Apto' => $noApto,
        ];

        /* ---------- Proximas entrevistas ---------- */
        $estadoEntrevistaNombre = DB::table('estado_entrevista')->pluck('nombre', 'id');
        $cargos = Cargo::forSelect();

        $departamentos_select = Departamento::forSelect();
        
        $proximasEntrevistas = Entrevista::with(['postulante', 'requerimiento', 'entrevistador'])
            ->whereNotNull('fecha_entrevista')
            ->whereDate('fecha_entrevista', '>=', $hoy)
            ->orderBy('fecha_entrevista')
            ->limit(6)
            ->get()
            ->map(function ($e) use ($estadoEntrevistaNombre, $cargos, $departamentos_select) {
                $postulante = $e->postulante;
                $nombre = trim(($postulante->nombres ?? '') . ' ' . ($postulante->apellidos ?? ''));

                $codigoCargo = null;
                if (!empty($postulante?->cargo)) {
                    $codigoCargo = str_pad((string)$postulante->cargo, 4, '0', STR_PAD_LEFT);
                } elseif (!empty($e->requerimiento?->cargo_solicitado)) {
                    $codigoCargo = str_pad((string)$e->requerimiento->cargo_solicitado, 4, '0', STR_PAD_LEFT);
                }

                $cargoNombre = $codigoCargo ? ($cargos->get($codigoCargo) ?? $codigoCargo) : 'N/A';

                $estadoNombre = $e->estado_entrevista_id
                    ? ($estadoEntrevistaNombre[$e->estado_entrevista_id] ?? 'Programada')
                    : 'Sin programar';

                // Departamento
                $departamentoCodigo = str_pad(ltrim((string)$postulante->departamento, '0'), 2, '0', STR_PAD_LEFT);
                $departamentoNombre = $departamentos_select->get($departamentoCodigo, 'N/A');

                // Entrevistador
                $entrevistadorNombre = $e->entrevistador?->name ?? '-';

                return [
                    'id' => $e->id,
                    'postulante_id' => $postulante->id,
                    'fecha' => optional($e->fecha_entrevista)->format('d/m/Y'),
                    'fecha_completa' => optional($e->fecha_entrevista)->format('Y-m-d H:i'),
                    'hora' => optional($e->fecha_entrevista)->format('H:i'),
                    'postulante' => $nombre !== '' ? $nombre : 'Sin nombre',
                    'dni' => $postulante->dni ?? '-',
                    'cargo' => $cargoNombre,
                    'departamento' => $departamentoNombre,
                    'estado' => $estadoNombre,
                    'entrevistador' => $entrevistadorNombre,
                    'resultado' => $e->resultado ?? null,
                ];
            })
            ->values()
            ->all();

        $entrevistasHoy = Entrevista::query()
            ->whereNotNull('fecha_entrevista')
            ->whereDate('fecha_entrevista', $hoy)
            ->count();

        // Personal Operativo y Administrativo
        $personalOperativo = Postulante::whereIn('tipo_personal_codigo', ['01', '03'])
            ->where('estado', 2)
            ->count();

        $personalAdministrativo = Postulante::where('tipo_personal_codigo', '02')
            ->where('estado', 2)
            ->count();

        /* ---------- ALERTAS ---------- */
        $alertas = $this->generarAlertas($hoy);

        /* ---------- Enviar a la vista ---------- */
        return view('dashboard', compact(
            'totalPostulantes',
            'variacionPostulantes',
            'totalRequerimientos',
            'variacionRequerimientos',
            'porSede',
            'maxTotalSede',
            'requerimientos',
            'notificaciones',
            'estadoPostulantes',
            'proximasEntrevistas',
            'entrevistasHoy',
            'personalOperativo',
            'personalAdministrativo',
            'alertas'
        ));
    }

    /**
     * Generar alertas basadas en el estado del sistema
     */
    private function generarAlertas($hoy)
    {
        $alertas = [];

        // Alerta 1: Entrevistas sin programar (aptos que no tienen entrevista)
        $entrevistacionPendiente = Postulante::where('estado', 2)
            ->where('decision', 'apto')
            ->whereDoesntHave('entrevistas')
            ->count();

        if ($entrevistacionPendiente > 0) {
            $alertas[] = [
                'tipo' => 'warning',
                'titulo' => 'Entrevistas pendientes',
                'detalle' => $entrevistacionPendiente . ' postulante' . ($entrevistacionPendiente > 1 ? 's' : '') . ' apto' . ($entrevistacionPendiente > 1 ? 's' : '') . ' sin programar entrevista',
                'cuando' => 'Requiere atención',
            ];
        }

        // Alerta 2: Postulantes en lista negra (si tienes ese campo)
        /*
        $enListaNegra = Postulante::where('lista_negra', true)->count();
        if ($enListaNegra > 0) {
            $alertas[] = [
                'tipo' => 'danger',
                'titulo' => 'Postulantes en lista negra',
                'detalle' => $enListaNegra . ' postulante' . ($enListaNegra > 1 ? 's' : '') . ' registrado' . ($enListaNegra > 1 ? 's' : '') . ' en lista negra',
                'cuando' => 'Información',
            ];
        }
        */

        // Alerta 3: Entrevistas próximas (en las próximas 24 horas)
        $entrevistasProximas = Entrevista::whereBetween('fecha_entrevista', [
            $hoy,
            $hoy->copy()->addDay()
        ])->count();

        if ($entrevistasProximas > 0) {
            $alertas[] = [
                'tipo' => 'success',
                'titulo' => 'Entrevistas programadas',
                'detalle' => $entrevistasProximas . ' entrevista' . ($entrevistasProximas > 1 ? 's' : '') . ' en las próximas 24 horas',
                'cuando' => 'Próximo evento',
            ];
        }

        // Alerta 4: Requerimientos sin cubrir
        $requerimientosSinCubrir = Requerimiento::where('estado', '1')
            ->whereDoesntHave('postulantes', function ($query) {
                $query->where('decision', 'apto');
            })
            ->count();

        if ($requerimientosSinCubrir > 0) {
            $alertas[] = [
                'tipo' => 'info',
                'titulo' => 'Requerimientos sin cubrir',
                'detalle' => $requerimientosSinCubrir . ' requerimiento' . ($requerimientosSinCubrir > 1 ? 's' : '') . ' activo' . ($requerimientosSinCubrir > 1 ? 's' : '') . ' esperando candidatos aptos',
                'cuando' => 'En revisión',
            ];
        }

        return count($alertas) > 0 ? $alertas : null;
    }
}
