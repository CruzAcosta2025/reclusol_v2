@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Postulantes -->
            <x-block class="flex flex-col justify-start">
                <div class="mb-1">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-users text-blue-950 text-xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm">Total de postulantes</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalPostulantes }}</h3>
                <div class="bg-green-100 border border-green-200 px-1 self-start">
                    <span class="text-success-dark text-xs font-medium">
                        {{ $variacionPostulantes > 0 ? '+' : '' }}{{ $variacionPostulantes }}%
                    </span>
                </div>
            </x-block>

            <!-- Requerimientos Activos -->
            <x-block class="flex flex-col justify-start">
                <div class="mb-1">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-briefcase text-blue-950 text-xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm">Requerimientos activos</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">{{ $totalRequerimientos }}</h3>
                <div class="bg-green-100 border border-green-200 px-1 self-start">
                    <span class="text-success-dark text-xs font-medium">
                        {{ $variacionRequerimientos > 0 ? '+' : '' }}{{ $variacionRequerimientos }}%
                    </span>
                </div>
            </x-block>

            <!-- Entrevistas Programadas -->
            <x-block class="flex flex-col justify-start">
                <div class="mb-1">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-blue-950 text-xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm">Entrevistas programadas</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">6</h3>
                <div class="bg-green-100 border border-green-200 px-1 self-start">
                    <span class="text-success-dark text-xs font-medium">Hoy</span>
                </div>
            </x-block>

            <!-- Contrataciones -->
            <x-block class="flex flex-col justify-start">
                <div class="mb-1">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-blue-950 text-xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm">Personal Operativo</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">18</h3>
                <div class="bg-green-100 border border-green-200 px-1 self-start">
                    <span class="text-success-dark text-xs font-medium">+5</span>
                </div>
            </x-block>

            <x-block class="flex flex-col justify-start">
                <div class="mb-1">
                    <div class="w-10 h-10 flex items-center justify-center">
                        <i class="fa-solid fa-user-tie text-blue-950 text-xl"></i>
                    </div>
                    <p class="text-gray-600 text-sm">Personal Administrativo</p>
                </div>
                <h3 class="text-3xl font-bold text-gray-800">18</h3>
                <div class="bg-green-100 border border-green-200 px-1 self-start">
                    <span class="text-success-dark text-xs font-medium">+5</span>
                </div>
            </x-block>
        </div>

        <!-- Charts Section -->
        <div class="grid lg:grid-cols-2 gap-6 mb-8">
            <!-- Postulaciones por Sede -->
            <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                @php
                    $totalPorSede = collect($porSede)->sum('total');
                    $topShare = $totalPorSede ? round(($maxTotalSede / $totalPorSede) * 100, 1) : 0;
                @endphp

                <div class="flex items-center justify-between w-full">
                    <h3 class="text-md font-semibold text-M2">Postulaciones por Sede</h3>
                </div>

                <div class="mt-2 flex items-end gap-3">
                    <h4 class="text-3xl font-bold text-gray-800">{{ number_format($totalPorSede) }}</h4>
                    <div class="bg-success-light border border-green-300 px-1 self-end">
                        <span class="text-success-dark text-xs font-semibold">Top {{ $topShare }}%</span>
                    </div>
                </div>

                <div class="mt-6 space-y-3 w-full">
                    @foreach ($porSede as $idx => $sede)
                        @php
                            $porcentaje = $totalPorSede ? round(($sede->total / $totalPorSede) * 100) : 0;
                            $idxMod = $idx % 6;
                        @endphp

                        <div class="space-y-1">
                            <div class="flex items-center justify-between">
                                <div class="min-w-0">
                                    <span class="text-sm text-gray-700 capitalize truncate">
                                        {{ strtolower($sede->nombre_departamento) }}
                                    </span>
                                </div>
                                <span class="text-sm text-gray-500 font-medium">{{ $porcentaje }}%</span>
                            </div>

                            <div class="w-full h-1.5 bg-neutral overflow-hidden">
                                <div
                                    @class([
                                        'h-1.5',
                                        'bg-info' => $idxMod === 0,
                                        'bg-success' => $idxMod === 1,
                                        'bg-warning' => $idxMod === 2,
                                        'bg-paused' => $idxMod === 3,
                                        'bg-error' => $idxMod === 4,
                                        'bg-accent' => $idxMod === 5,
                                    ])
                                    style="width: {{ $porcentaje }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Estado de Postulantes -->
            <div
                class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                <div class="flex items-center justify-between w-full">
                    <h3 class="text-md font-semibold text-M2">Estado de Postulantes</h3>
                </div>

                <div class="mt-4 w-full">
                    <div class="relative w-full flex justify-center" style="height: 170px;">
                        <svg class="absolute top-0" width="320" height="170" viewBox="0 0 100 60" fill="none" aria-hidden="true">
                            <path d="M 10 50 A 40 40 0 0 1 90 50" pathLength="100" stroke="rgba(0,0,0,0.08)" stroke-width="8" stroke-linecap="butt" />

                            <path d="M 10 50 A 40 40 0 0 1 90 50" pathLength="100"
                                class="stroke-success" stroke-width="8" stroke-linecap="butt"
                                stroke-dasharray="{{ $estadoGauge['aptoPct'] }} {{ 100 - $estadoGauge['aptoPct'] }}" stroke-dashoffset="-{{ $estadoGauge['offsetApto'] }}" />

                            <path d="M 10 50 A 40 40 0 0 1 90 50" pathLength="100"
                                class="stroke-warning" stroke-width="8" stroke-linecap="butt"
                                stroke-dasharray="{{ $estadoGauge['pendientePct'] }} {{ 100 - $estadoGauge['pendientePct'] }}" stroke-dashoffset="-{{ $estadoGauge['offsetPendiente'] }}" />

                            <path d="M 10 50 A 40 40 0 0 1 90 50" pathLength="100"
                                class="stroke-info" stroke-width="8" stroke-linecap="butt"
                                stroke-dasharray="{{ $estadoGauge['entrevistaPct'] }} {{ 100 - $estadoGauge['entrevistaPct'] }}" stroke-dashoffset="-{{ $estadoGauge['offsetEntrevista'] }}" />

                            <path d="M 10 50 A 40 40 0 0 1 90 50" pathLength="100"
                                class="stroke-error" stroke-width="8" stroke-linecap="butt"
                                stroke-dasharray="{{ $estadoGauge['noAptoPct'] }} {{ 100 - $estadoGauge['noAptoPct'] }}" stroke-dashoffset="-{{ $estadoGauge['offsetNoApto'] }}" />
                        </svg>

                        <div class="absolute inset-0 flex flex-col items-center justify-center" style="padding-top: 22px;">
                            <div class="text-4xl font-bold text-gray-800">{{ number_format($estadoPostulantes['total']) }}</div>
                            <div class="text-sm text-gray-500">Estado de postulantes</div>
                        </div>
                    </div>

                    <div class="mt-2 space-y-3 w-full">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-success flex-shrink-0"></span>
                                <span class="text-sm text-gray-700 truncate">Apto</span>
                            </div>
                            <span class="text-sm text-gray-700 font-medium">{{ number_format($estadoPostulantes['apto']) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-warning flex-shrink-0"></span>
                                <span class="text-sm text-gray-700 truncate">Pendiente</span>
                            </div>
                            <span class="text-sm text-gray-700 font-medium">{{ number_format($estadoPostulantes['pendiente']) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-info flex-shrink-0"></span>
                                <span class="text-sm text-gray-700 truncate">En entrevista</span>
                            </div>
                            <span class="text-sm text-gray-700 font-medium">{{ number_format($estadoPostulantes['entrevista']) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="w-2 h-2 rounded-full bg-error flex-shrink-0"></span>
                                <span class="text-sm text-gray-700 truncate">No apto</span>
                            </div>
                            <span class="text-sm text-gray-700 font-medium">{{ number_format($estadoPostulantes['no_apto']) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="grid lg:grid-cols-2 gap-6">
            <!-- Próximas Entrevistas -->
            <div
                class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                <div class="flex items-center justify-between mb-6 w-full">
                    <h3 class="text-md font-semibold text-M2">Próximas Entrevistas Programadas</h3>
                </div>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-200">
                                <th class="text-left py-3 text-gray-600">Fecha</th>
                                <th class="text-left py-3 text-gray-600">Hora</th>
                                <th class="text-left py-3 text-gray-600">Postulante</th>
                                <th class="text-left py-3 text-gray-600">Cargo</th>
                                <th class="text-left py-3 text-gray-600">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="space-y-2">
                            <tr class="border-b border-gray-100">
                                <td class="py-3">2024-01-15</td>
                                <td class="py-3">09:00 am</td>
                                <td class="py-3 font-medium">Ruben Cruz</td>
                                <td class="py-3">Analista de datos</td>
                                <td class="py-3">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completo</span>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-100">
                                <td class="py-3">2025-01-22</td>
                                <td class="py-3">10:00 am</td>
                                <td class="py-3 font-medium">Emma Acosta</td>
                                <td class="py-3">Secretaria</td>
                                <td class="py-3">
                                    <span
                                        class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pendiente</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-3">2025-06-22</td>
                                <td class="py-3">10:30 am</td>
                                <td class="py-3 font-medium">Bryan Arteaga</td>
                                <td class="py-3">Analista Programador</td>
                                <td class="py-3">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completo</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Alertas Recientes -->
            <div
                class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                <div class="flex items-center justify-between mb-6 w-full">
                    <h3 class="text-md font-semibold text-M2">Alertas Recientes</h3>
                </div>
                <div class="space-y-3 w-full">
                    <!-- Alert row (yellow) -->
                    <div class="flex items-center justify-between bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="h-12 w-1 rounded-l-full bg-yellow-400"></div>
                            <div class="flex items-center space-x-3 px-4 py-3">
                                <div class="w-10 h-10 bg-yellow-50 rounded-md flex items-center justify-center">
                                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Faltan validar 5 postulantes</p>
                                    <p class="text-xs text-gray-600">Hace 2 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert row (blue) -->
                    <div class="flex items-center justify-between bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="h-12 w-1 rounded-l-full bg-blue-400"></div>
                            <div class="flex items-center space-x-3 px-4 py-3">
                                <div class="w-10 h-10 bg-blue-50 rounded-md flex items-center justify-center">
                                    <i class="fas fa-info-circle text-blue-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Requerimiento urgente sin oficina</p>
                                    <p class="text-xs text-gray-600">Hace 4 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alert row (orange) -->
                    <div class="flex items-center justify-between bg-white rounded-lg shadow-sm">
                        <div class="flex items-center">
                            <div class="h-12 w-1 rounded-l-full bg-orange-400"></div>
                            <div class="flex items-center space-x-3 px-4 py-3">
                                <div class="w-10 h-10 bg-orange-50 rounded-md flex items-center justify-center">
                                    <i class="fas fa-clock text-orange-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Postulante con entrevista reprogramada</p>
                                    <p class="text-xs text-gray-600">Hace 6 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const progressBars = document.querySelectorAll('[style*="width:"]');
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                setTimeout(() => {
                    bar.style.transition = 'width 1s ease-in-out';
                    bar.style.width = width;
                }, 500);
            });
        });
    </script>
@endsection