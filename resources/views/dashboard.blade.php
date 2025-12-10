@extends('layouts.app')

@section('module', 'dashboard')

@section('content')
@php
// =========================
// Preparación de datos
// =========================

// Postulaciones por sede (desde tu controller: $porSede, $maxTotalSede)
$labelsSede = collect($porSede ?? [])->map(function ($s) {
return ucwords(strtolower($s->nombre_departamento ?? 'Sin sede'));
})->values();

$totalesSede = collect($porSede ?? [])->map(function ($s) {
return (int) ($s->total ?? 0);
})->values();

// Estado de postulantes (ideal: pásalo desde controller como array asociativo)
// Ejemplo esperado:
// $estadoPostulantes = ['Apto'=>140,'Pendiente'=>40,'En entrevista'=>53,'No Apto'=>15];
$estadoPostulantes = $estadoPostulantes ?? null;

$estadoLabels = is_array($estadoPostulantes) ? array_keys($estadoPostulantes) : [];
$estadoValues = is_array($estadoPostulantes) ? array_values($estadoPostulantes) : [];

// Próximas entrevistas (ideal: pásalo desde controller)
// Ejemplo: [['fecha'=>'2025-01-22','hora'=>'10:00','postulante'=>'...','cargo'=>'...','estado'=>'Pendiente'], ...]
$proximasEntrevistas = $proximasEntrevistas ?? null;

// Alertas (ideal: pásalo desde controller)
$alertas = $alertas ?? null;
@endphp

<!-- =========================
         KPIs
    ========================== -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">
    <!-- Total Postulantes -->
    <div class="card bg-white text-gray-900 p-5 shadow-soft">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-xs font-semibold text-gray-500">Postulantes</div>
                <div class="mt-1 text-3xl font-extrabold">{{ $totalPostulantes ?? 0 }}</div>
                <div class="mt-1 text-sm text-gray-600">Total registrados</div>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center" style="background:#eff6ff;">
                <i class="fas fa-users text-blue-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-500">Variación</span>
            <span class="text-sm font-semibold {{ ($variacionPostulantes ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ ($variacionPostulantes ?? 0) > 0 ? '+' : '' }}{{ $variacionPostulantes ?? 0 }}%
            </span>
        </div>
    </div>

    <!-- Requerimientos -->
    <div class="card bg-white text-gray-900 p-5 shadow-soft">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-xs font-semibold text-gray-500">Requerimientos</div>
                <div class="mt-1 text-3xl font-extrabold">{{ $totalRequerimientos ?? 0 }}</div>
                <div class="mt-1 text-sm text-gray-600">Vacantes abiertas</div>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center" style="background:#fff7ed;">
                <i class="fas fa-briefcase text-yellow-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-500">Variación</span>
            <span class="text-sm font-semibold {{ ($variacionRequerimientos ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ ($variacionRequerimientos ?? 0) > 0 ? '+' : '' }}{{ $variacionRequerimientos ?? 0 }}%
            </span>
        </div>
    </div>

    <!-- Entrevistas -->
    <div class="card bg-white text-gray-900 p-5 shadow-soft">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-xs font-semibold text-gray-500">Entrevistas</div>
                <div class="mt-1 text-3xl font-extrabold">{{ $entrevistasHoy ?? 0 }}</div>
                <div class="mt-1 text-sm text-gray-600">Programadas hoy</div>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center" style="background:#ecfdf5;">
                <i class="fas fa-calendar-day text-green-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 text-xs text-gray-500">Indicador diario</div>
    </div>

    <!-- Operativo -->
    <div class="card bg-white text-gray-900 p-5 shadow-soft">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-xs font-semibold text-gray-500">Operativo</div>
                <div class="mt-1 text-3xl font-extrabold">{{ $personalOperativo ?? 0 }}</div>
                <div class="mt-1 text-sm text-gray-600">Personal contratado</div>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center" style="background:#eef2ff;">
                <i class="fas fa-user-shield text-indigo-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-500">Nuevos</span>
            <span class="text-sm font-semibold text-green-600">+{{ $operativoNuevos ?? 0 }}</span>
        </div>
    </div>

    <!-- Administrativo -->
    <div class="card bg-white text-gray-900 p-5 shadow-soft">
        <div class="flex items-start justify-between">
            <div>
                <div class="text-xs font-semibold text-gray-500">Administrativo</div>
                <div class="mt-1 text-3xl font-extrabold">{{ $personalAdministrativo ?? 0 }}</div>
                <div class="mt-1 text-sm text-gray-600">Personal contratado</div>
            </div>
            <div class="h-12 w-12 rounded-2xl flex items-center justify-center" style="background:#f5f3ff;">
                <i class="fas fa-user-tie text-purple-600 text-lg"></i>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between">
            <span class="text-xs text-gray-500">Nuevos</span>
            <span class="text-sm font-semibold text-green-600">+{{ $administrativoNuevos ?? 0 }}</span>
        </div>
    </div>
</div>

<!-- =========================
         Acciones rápidas
    ========================== -->
<div class="card glass-strong p-5 shadow-soft mb-6">
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <div class="text-sm font-semibold">Acciones rápidas</div>
            <div class="text-xs" style="opacity:.75;">
                Atajos para el flujo de reclutamiento
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('requerimientos.requerimiento') }}" class="px-4 py-2 rounded-xl font-semibold text-sm"
                style="background:linear-gradient(135deg,#22c55e,#16a34a);">
                <i class="fas fa-plus mr-2"></i>Crear Solicitud
            </a>
            <a href="{{ route('postulantes.formInterno') }}" class="px-4 py-2 rounded-xl font-semibold text-sm"
                style="background:linear-gradient(135deg,#3b82f6,#4f46e5);">
                <i class="fas fa-user-plus mr-2"></i>Crear Postulante
            </a>
            <a href="{{ route('requerimientos.filtrar') }}" class="px-4 py-2 rounded-xl font-semibold text-sm bg-white bg-opacity-10 hover:bg-opacity-15">
                <i class="fas fa-list mr-2"></i>Ver Solicitudes
            </a>
            <a href="{{ route('postulantes.filtrar') }}" class="px-4 py-2 rounded-xl font-semibold text-sm bg-white bg-opacity-10 hover:bg-opacity-15">
                <i class="fas fa-users mr-2"></i>Ver Postulantes
            </a>
        </div>
    </div>
</div>

<!-- =========================
         Gráficos
    ========================== -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
    <!-- Bar chart: Sedes -->
    <div class="card glass-strong p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-semibold">Postulaciones por Sede</div>
                <div class="text-xs" style="opacity:.75;">Comparativo por ubicación</div>
            </div>
            <div class="px-3 py-1.5 rounded-xl text-xs bg-white bg-opacity-10">
                <i class="fas fa-chart-bar mr-2"></i>Chart.js
            </div>
        </div>

        <div class="bg-white rounded-2xl p-4">
            @if ($labelsSede->count() > 0)
            <canvas id="chartSede" height="140"></canvas>
            @else
            <div class="text-gray-500 text-sm text-center py-12">
                No hay datos para mostrar aún (porSede vacío).
            </div>
            @endif
        </div>
    </div>

    <!-- Donut: Estado -->
    <div class="card glass-strong p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-semibold">Estado de Postulantes</div>
                <div class="text-xs" style="opacity:.75;">Distribución del pipeline</div>
            </div>
            <div class="px-3 py-1.5 rounded-xl text-xs bg-white bg-opacity-10">
                <i class="fas fa-chart-pie mr-2"></i>Chart.js
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
            <div class="md:col-span-2 bg-white rounded-2xl p-4">
                @if (!empty($estadoLabels))
                <canvas id="chartEstado" height="160"></canvas>
                @else
                <div class="text-gray-500 text-sm text-center py-12">
                    Pasa <b>$estadoPostulantes</b> desde el controller para ver este gráfico.
                </div>
                @endif
            </div>

            <div class="md:col-span-3 space-y-3">
                @if (is_array($estadoPostulantes))
                @php
                $pillMap = [
                'Apto' => ['dot' => '#16a34a', 'text' => 'text-green-700'],
                'Pendiente' => ['dot' => '#ca8a04', 'text' => 'text-yellow-700'],
                'En entrevista' => ['dot' => '#2563eb', 'text' => 'text-blue-700'],
                'No Apto' => ['dot' => '#dc2626', 'text' => 'text-red-700'],
                ];
                @endphp
                @foreach ($estadoPostulantes as $k => $v)
                @php $pill = $pillMap[$k] ?? ['dot' => '#4f46e5', 'text' => 'text-indigo-700']; @endphp
                <div class="bg-white rounded-2xl p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="inline-block h-3 w-3 rounded-full" style="background:{{ $pill['dot'] }};"></span>
                        <div class="text-sm font-semibold text-gray-800">{{ $k }}</div>
                    </div>
                    <div class="text-sm font-extrabold {{ $pill['text'] }}">{{ (int) $v }}</div>
                </div>
                @endforeach
                @else
                <div class="bg-white rounded-2xl p-4 text-gray-600 text-sm">
                    Tip: en tu controller define <b>$estadoPostulantes</b> como array asociativo y pásalo a la vista.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- =========================
         Tablas inferiores
    ========================== -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Próximas entrevistas -->
    <div class="card glass-strong p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-semibold">Próximas Entrevistas</div>
                <div class="text-xs" style="opacity:.75;">Agenda programada</div>
            </div>
            <a href="{{ route('entrevistas.index') }}" class="text-xs font-semibold bg-white bg-opacity-10 hover:bg-opacity-15 px-3 py-2 rounded-xl">
                Ver entrevistas <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="bg-white rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-gray-700">
                    <thead class="bg-gray-50 text-gray-600">
                        <tr>
                            <th class="text-left px-4 py-3">Fecha</th>
                            <th class="text-left px-4 py-3">Hora</th>
                            <th class="text-left px-4 py-3">Postulante</th>
                            <th class="text-left px-4 py-3">Cargo</th>
                            <th class="text-left px-4 py-3">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @if (is_array($proximasEntrevistas) && count($proximasEntrevistas))
                        @foreach ($proximasEntrevistas as $row)
                        <tr>
                            <td class="px-4 py-3">{{ $row['fecha'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['hora'] ?? '-' }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-900">{{ $row['postulante'] ?? '-' }}</td>
                            <td class="px-4 py-3">{{ $row['cargo'] ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @php
                                $estado = strtolower($row['estado'] ?? '');
                                $badge = 'background:#eef2ff;color:#3730a3;';
                                if (str_contains($estado, 'pend')) $badge = 'background:#fffbeb;color:#92400e;';
                                if (str_contains($estado, 'comp')) $badge = 'background:#ecfdf5;color:#166534;';
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold" style="{{ $badge }}">
                                    {{ $row['estado'] ?? '—' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                Sin entrevistas programadas (o aún no estás enviando <b>$proximasEntrevistas</b>).
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Alertas -->
    <div class="card glass-strong p-6 shadow-soft">
        <div class="flex items-center justify-between mb-4">
            <div>
                <div class="text-sm font-semibold">Alertas Recientes</div>
                <div class="text-xs" style="opacity:.75;">Eventos a revisar</div>
            </div>
            <div class="px-3 py-1.5 rounded-xl text-xs bg-white bg-opacity-10">
                <i class="fas fa-bell mr-2"></i>Live
            </div>
        </div>

        <div class="space-y-3">
            @if (is_array($alertas) && count($alertas))
            @foreach ($alertas as $a)
            @php
            $type = strtolower($a['tipo'] ?? 'info');
            $left = '#2563eb';
            $icon = 'fa-info-circle';
            if ($type === 'warning') { $left='#f59e0b'; $icon='fa-exclamation-triangle'; }
            if ($type === 'danger') { $left='#ef4444'; $icon='fa-triangle-exclamation'; }
            if ($type === 'success') { $left='#22c55e'; $icon='fa-circle-check'; }
            @endphp
            <div class="bg-white rounded-2xl p-4 border-l-4" style="border-left-color:{{ $left }};">
                <div class="flex items-start gap-3">
                    <i class="fas {{ $icon }} mt-0.5" style="color:{{ $left }};"></i>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-gray-900">{{ $a['titulo'] ?? 'Alerta' }}</div>
                        <div class="text-xs text-gray-600 mt-1">{{ $a['detalle'] ?? '' }}</div>
                        <div class="text-xs text-gray-500 mt-2">{{ $a['cuando'] ?? '' }}</div>
                    </div>
                </div>
            </div>
            @endforeach
            @else
            <div class="bg-white rounded-2xl p-4 text-gray-600 text-sm">
                No hay alertas (o aún no estás enviando <b>$alertas</b>).
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js (CDN) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labelsSede = @json($labelsSede);
        const totalsSede = @json($totalesSede);

        const estadoLabels = @json($estadoLabels);
        const estadoValues = @json($estadoValues);

        // Colores
        const colors = ['#2563eb', '#16a34a', '#ca8a04', '#dc2626', '#4f46e5', '#9333ea', '#0891b2', '#0f766e'];

        // Bar: Sedes
        const ctxSede = document.getElementById('chartSede');
        if (ctxSede && labelsSede.length) {
            new Chart(ctxSede, {
                type: 'bar',
                data: {
                    labels: labelsSede,
                    datasets: [{
                        label: 'Postulantes',
                        data: totalsSede,
                        borderWidth: 0,
                        backgroundColor: labelsSede.map((_, i) => colors[i % colors.length]),
                        borderRadius: 10,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.raw} postulantes`
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#334155',
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(15, 23, 42, .08)'
                            },
                            ticks: {
                                color: '#334155'
                            }
                        }
                    }
                }
            });
        }

        // Donut: Estado
        const ctxEstado = document.getElementById('chartEstado');
        if (ctxEstado && estadoLabels.length) {
            new Chart(ctxEstado, {
                type: 'doughnut',
                data: {
                    labels: estadoLabels,
                    datasets: [{
                        data: estadoValues,
                        borderWidth: 0,
                        backgroundColor: ['#16a34a', '#ca8a04', '#2563eb', '#dc2626'],
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => ` ${ctx.label}: ${ctx.raw}`
                            }
                        }
                    },
                    cutout: '70%'
                }
            });
        }
    });
</script>
@endpush