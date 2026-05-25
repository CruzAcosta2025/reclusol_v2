<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Requerimiento;
use App\Models\Entrevista;
use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;


class DashboardController extends Controller
{
    public function index()
    {
        // ==================== KPI CARDS ====================
        
        // Total Postulantes
        $totalPostulantes = Postulante::count();
        
        // Total Requerimientos (abiertos/activos)
        $totalRequerimientos = Requerimiento::where('estado', 'activo')->count();
        
        // Entrevistas programadas hoy
        $entrevistasHoy = Entrevista::whereDate('fecha_entrevista', today())->count();
        
        // Personal Operativo (tipo_personal_codigo = '01' o '03')
        $personalOperativo = Postulante::whereIn('tipo_personal_codigo', ['01', '03'])
            ->where('estado', 2) // apto
            ->count();
        
        // Personal Administrativo (tipo_personal_codigo = '02')
        $personalAdministrativo = Postulante::where('tipo_personal_codigo', '02')
            ->where('estado', 2) // apto
            ->count();

        // ==================== ESTADO DE POSTULANTES ====================
        
        // Contar postulantes por estado/decision
        $estadoPostulantes = [
            'Apto' => Postulante::where('estado', 2)->where('decision', 'apto')->count(),
            'Pendiente' => Postulante::where('estado', 1)->count(),
            'En entrevista' => Entrevista::whereNull('resultado')
                ->orWhere('resultado', '!=', 'EVALUADO')
                ->distinct('postulante_id')
                ->count('postulante_id'),
            'No Apto' => Postulante::where('estado', 3)->orWhere('decision', 'no_apto')->count(),
        ];

        // ==================== PRÓXIMAS ENTREVISTAS ====================
        
        $proximasEntrevistas = Entrevista::with(['postulante', 'requerimiento'])
            ->where('fecha_entrevista', '>=', now())
            ->orderBy('fecha_entrevista', 'asc')
            ->limit(5)
            ->get()
            ->map(function ($entrevista) {
                $cargo = $this->obtenerNombreCargo($entrevista->postulante);
                
                return [
                    'fecha' => $entrevista->fecha_entrevista?->format('d/m/Y') ?? '-',
                    'hora' => $entrevista->fecha_entrevista?->format('H:i') ?? '-',
                    'postulante' => $entrevista->postulante->nombres . ' ' . $entrevista->postulante->apellidos,
                    'cargo' => $cargo,
                    'estado' => $this->mapearEstadoEntrevista($entrevista),
                ];
            })
            ->toArray();

        // ==================== ALERTAS RECIENTES ====================
        
        $alertas = $this->generarAlertas();

        // ==================== POSTULACIONES POR DEPARTAMENTO ====================
        
        $departamentos = Departamento::forSelect(); // ['01' => 'Lima', ...]
        
        $porSede = Postulante::select('departamento', DB::raw('count(*) as total'))
            ->groupBy('departamento')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(function ($item) use ($departamentos) {
                return (object) [
                    'nombre_departamento' => $departamentos->get($item->departamento) ?? $item->departamento,
                    'total' => $item->total,
                ];
            });

        return view('dashboard', compact(
            'totalPostulantes',
            'totalRequerimientos',
            'entrevistasHoy',
            'personalOperativo',
            'personalAdministrativo',
            'estadoPostulantes',
            'proximasEntrevistas',
            'alertas',
            'porSede'
        ));
    }

    /**
     * Obtener nombre del cargo de un postulante
     */
    private function obtenerNombreCargo($postulante)
    {
        $cargos = Cargo::forSelect(); // ['0001' => 'AGENTE...', ...]
        
        if (!empty($postulante->cargo)) {
            $codigoCargo = str_pad($postulante->cargo, 4, '0', STR_PAD_LEFT);
            return $cargos->get($codigoCargo) ?? $postulante->cargo;
        }
        
        return 'N/A';
    }

    /**
     * Mapear estado de entrevista al texto a mostrar
     */
    private function mapearEstadoEntrevista($entrevista)
    {
        $estadoCodigoPorId = DB::table('estado_entrevista')
            ->pluck('codigo', 'id');
        
        if ($entrevista->estado_entrevista_id) {
            $codigo = $estadoCodigoPorId->get($entrevista->estado_entrevista_id);
            
            return match ($codigo) {
                'PROGRAMADA' => 'Programada',
                'REPROGRAMADA' => 'Reprogramada',
                'ENTREVISTADA' => 'Entrevistada',
                'NO_ASISTIO' => 'No asistió',
                'CANCELADA' => 'Cancelada',
                'CERRADA' => 'Cerrada',
                default => 'Pendiente',
            };
        }
        
        return 'Pendiente';
    }

    /**
     * Generar alertas basadas en el estado del sistema
     */
    private function generarAlertas()
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
                'cuando' => 'Hace poco',
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
                'cuando' => 'Sistema',
            ];
        }
        */

        // Alerta 3: Requerimientos sin llenar
        $requerimientosSinLlenar = Requerimiento::where('estado', 'activo')
            ->where(function ($q) {
                $q->whereNull('cantidad_solicitada')
                  ->orWhere('cantidad_solicitada', 0);
            })
            ->count();

        if ($requerimientosSinLlenar > 0) {
            $alertas[] = [
                'tipo' => 'info',
                'titulo' => 'Requerimientos incompletos',
                'detalle' => $requerimientosSinLlenar . ' requerimiento' . ($requerimientosSinLlenar > 1 ? 's' : '') . ' sin cantidad definida',
                'cuando' => 'Requiere atención',
            ];
        }

        // Alerta 4: Entrevistas próximas (en las próximas 24 horas)
        $entrevistasProximas = Entrevista::whereBetween('fecha_entrevista', [
            now(),
            now()->addDay()
        ])->count();

        if ($entrevistasProximas > 0) {
            $alertas[] = [
                'tipo' => 'success',
                'titulo' => 'Entrevistas programadas',
                'detalle' => $entrevistasProximas . ' entrevista' . ($entrevistasProximas > 1 ? 's' : '') . ' en las próximas 24 horas',
                'cuando' => 'Próximo evento',
            ];
        }

        return count($alertas) > 0 ? $alertas : null;
    }
}
