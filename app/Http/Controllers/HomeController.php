<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Postulante;
use App\Models\Departamento;
use Carbon\Carbon;
use App\Models\Requerimiento;

class HomeController extends Controller
{

    protected Departamento $departamentoModel;

    public function __construct(Departamento $departamentoModel)
    {
        $this->departamentoModel = $departamentoModel;
    }

    public function index()
    {
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

        $departamentos = $this->departamentoModel->forSelectPadded();

        foreach ($porSede as $sede) {
            // normaliza el código del postulante a 2 dígitos
            $codigo = str_pad(ltrim((string)$sede->departamento, '0'), 2, '0', STR_PAD_LEFT);
            $sede->nombre_departamento = $departamentos->get($codigo, 'Sin nombre');
        }

        [$estadoPostulantes, $estadoGauge] = $this->calcularEstadoPostulantes();

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
            'estadoGauge'
        ));
    }


    protected function calcularEstadoPostulantes(): array
    {
        $total = Postulante::count();

        $noApto = Postulante::where('decision', 'no_apto')->count();

        $enEntrevista = Postulante::whereHas('entrevistas')
            ->where(function ($q) {
                $q->whereNull('decision')->orWhere('decision', '!=', 'no_apto');
            })
            ->count();

        $apto = Postulante::where('decision', 'apto')
            ->whereDoesntHave('entrevistas')
            ->count();

        $pendiente = max(0, $total - ($noApto + $enEntrevista + $apto));

        $pct = function (int $n) use ($total): float {
            return $total ? round(($n / $total) * 100, 2) : 0.0;
        };

        $aptoPct = $pct($apto);
        $pendientePct = $pct($pendiente);
        $entrevistaPct = $pct($enEntrevista);
        $noAptoPct = max(0.0, round(100 - ($aptoPct + $pendientePct + $entrevistaPct), 2));

        $offsetApto = 0.0;
        $offsetPendiente = round($offsetApto + $aptoPct, 2);
        $offsetEntrevista = round($offsetPendiente + $pendientePct, 2);
        $offsetNoApto = round($offsetEntrevista + $entrevistaPct, 2);

        return [
            [
                'total' => $total,
                'apto' => $apto,
                'pendiente' => $pendiente,
                'entrevista' => $enEntrevista,
                'no_apto' => $noApto,
            ],
            [
                'aptoPct' => $aptoPct,
                'pendientePct' => $pendientePct,
                'entrevistaPct' => $entrevistaPct,
                'noAptoPct' => $noAptoPct,
                'offsetApto' => $offsetApto,
                'offsetPendiente' => $offsetPendiente,
                'offsetEntrevista' => $offsetEntrevista,
                'offsetNoApto' => $offsetNoApto,
            ],
        ];
    }
}
