@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <!-- Total Postulantes -->
            <x-block class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>

                    {{-- porcentaje de cambio --}}
                    <span class="text-green-500 text-sm font-medium">
                        {{ $variacionPostulantes > 0 ? '+' : '' }}{{ $variacionPostulantes }}%
                    </span>
                </div>
                {{-- total real de postulantes --}}
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalPostulantes }}</h3>
                <p class="text-gray-600 text-sm">Total de postulantes registrados</p>
            </x-block>

            <!-- Requerimientos Activos -->
            <x-block class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-orange-600 text-xl"></i>
                    </div>
                    <span
                        class="text-green-500 text-sm font-medium">{{ $variacionRequerimientos > 0 ? '+' : '' }}{{ $variacionRequerimientos }}%</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalRequerimientos }}</h3>
                <p class="text-gray-600 text-sm">Requerimientos activos</p>
                <p class="text-xs text-gray-500 mt-1">Vacantes abiertas</p>
            </x-block>

            <!-- Entrevistas Programadas -->
            <x-block class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                    </div>
                    <span class="text-blue-500 text-sm font-medium">Hoy</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">6</h3>
                <p class="text-gray-600 text-sm">Entrevistas programadas hoy</p>
                <p class="text-xs text-gray-500 mt-1">entrevistas</p>
            </x-block>

            <!-- Contrataciones -->
            <x-block class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user-shield text-purple-600 text-x1"></i>
                    </div>
                    <span class="text-green-500 text-sm font-medium">+5</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">18</h3>
                <p class="text-gray-600 text-sm">Personal Operativo</p>
            </x-block>

            <x-block class="flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fa-solid fa-user-tie text-purple-600 text-x1"></i>
                    </div>
                    <span class="text-green-500 text-sm font-medium">+5</span>
                </div>
                <h3 class="text-3xl font-bold text-gray-800 mb-1">18</h3>
                <p class="text-gray-600 text-sm">Personal Administrativo</p>
            </x-block>
        </div>

        <!-- Charts Section -->
        <div class="grid lg:grid-cols-2 gap-6 mb-8">
            <!-- Postulaciones por Sede -->
            <div
                class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                <div class="flex items-center justify-between mb-6 w-full">
                    <h3 class="text-lg font-bold text-M2">Postulaciones por Sede</h3>
                    <i class="fas fa-chart-bar text-M2"></i>
                </div>

                <div class="space-y-4 w-full">
                    @php
                        /* Colores tailwind para las barras (cicla si hay más sedes) */
                        $colores = ['blue-500', 'green-500', 'yellow-500', 'red-500', 'purple-500', 'orange-500'];
                    @endphp

                    @foreach ($porSede as $idx => $sede)
                        @php
                            $porcentaje = $maxTotalSede ? round(($sede->total / $maxTotalSede) * 100) : 0;
                            $color = $colores[$idx % count($colores)];
                        @endphp

                        <div class="flex items-center justify-between">
                            <span class="text-gray-600 capitalize">
                                {{ strtolower($sede->nombre_departamento) }}
                            </span>

                            <div class="flex items-center space-x-3 flex-1 mx-4">
                                <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                                    <div class="bg-{{ $color }} h-3" style="width: {{ $porcentaje }}%">
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-gray-800 w-8">
                                    {{ $sede->total }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Estado de Postulantes -->
            <div
                class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                <div class="flex items-center justify-between mb-6 w-full">
                    <h3 class="text-lg font-bold text-M2">Estado de Postulantes</h3>
                    <i class="fas fa-user-check text-M2"></i>
                </div>
                <div class="space-y-4 w-full">
                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                            <span class="text-gray-700">Apto</span>
                        </div>
                        <span class="font-bold text-green-600">140</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            <span class="text-gray-700">Pendiente</span>
                        </div>
                        <span class="font-bold text-yellow-600">40</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            <span class="text-gray-700">En entrevista</span>
                        </div>
                        <span class="font-bold text-blue-600">53</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            <span class="text-gray-700">No Apto</span>
                        </div>
                        <span class="font-bold text-red-600">15</span>
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
                    <h3 class="text-lg font-bold text-M2">Próximas Entrevistas Programadas</h3>
                    <i class="fas fa-calendar-check text-M2"></i>
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
                                    <span
                                        class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Completo</span>
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
                    <h3 class="text-lg font-bold text-M2">Alertas Recientes</h3>
                    <i class="fas fa-bell text-M2"></i>
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
        document.addEventListener('DOMContentLoaded', function() {
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
