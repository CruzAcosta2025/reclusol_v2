<x-app-layout>
    <div class="min-h-screen gradient-bg">
        <!-- Header -->
        <header class="bg-white/10 border-b border-white/20 z-40">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- User Info - Moved to extreme left -->
                    <div class="flex items-center space-x-3 relative">
                        <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="text-white cursor-pointer" onclick="toggleUserDropdown()">
                            <h2 class="font-semibold text-sm"> {{ Auth::user()->name ?? 'INVITADO' }} </h2>
                            <p class="text-xs text-blue-100"> {{ Auth::user()->cargoInfo?->DESC_TIPO_CARG ?? 'Sin rol' }} </p>
                        </div>

                        <!-- User Dropdown -->
                        <div id="user-dropdown" class="absolute top-full left-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50">
                            <!-- Dropdown Header -->
                            <div class="p-4 border-b border-gray-200 bg-blue-50">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-blue-600 text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'INVITADO' }}</h3>
                                        <p class="text-sm text-gray-600"> {{ Auth::user()->cargoInfo?->DESC_TIPO_CARG ?? 'Sin rol' }} </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Notifications Section -->
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <h4 class="font-semibold text-gray-800 flex items-center">
                                        <i class="fas fa-bell text-yellow-500 mr-2"></i>
                                        Notificaciones
                                    </h4>
                                    <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">3</span>
                                </div>

                                <div class="space-y-3 max-h-60 overflow-y-auto">
                                    <!-- Notification 1 -->
                                    <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">Faltan validar 5 postulantes</p>
                                            <p class="text-xs text-gray-500">Hace 2 horas</p>
                                        </div>
                                    </div>

                                    <!-- Notification 2 -->
                                    <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-info-circle text-blue-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">Requerimiento urgente sin oficina</p>
                                            <p class="text-xs text-gray-500">Hace 4 horas</p>
                                        </div>
                                    </div>

                                    <!-- Notification 3 -->
                                    <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                        <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <i class="fas fa-clock text-orange-600 text-xs"></i>
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-800">Entrevista reprogramada</p>
                                            <p class="text-xs text-gray-500">Hace 6 horas</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Ver todas las notificaciones</a>
                                </div>
                            </div>

                            <!-- Dropdown Footer -->
                            <div class="p-4 border-t border-gray-200 bg-gray-50">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center space-x-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Cerrar Sesión</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Center Title -->
                    <div class="hidden md:block text-center text-white flex-1">
                        <h1 class="text-lg font-semibold">Resumen general del sistema de reclutamiento</h1>
                    </div>

                    <!-- Right Section -->
                    <div class="flex items-center space-x-4 text-white">
                        <div class="hidden sm:flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-yellow-300"></i>
                            <span class="text-sm">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                        </div>
                        <!-- Removed the logout button from here since it's now in the dropdown -->
                    </div>
                </div>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white/10 backdrop-blur-md min-h-screen border-r border-white/20">
                <nav class="p-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 bg-blue-600 text-white px-4 py-3 rounded-lg font-medium">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>DASHBOARD</span>
                    </a>
                    <a href="{{ route('requerimientos.requerimiento') }}" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-file-alt"></i>
                        <span>REQUERIMIENTOS</span>
                    </a>

                    <a href="{{ route('postulantes.registroSecundario') }}" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-users"></i>
                        <span>POSTULANTES</span>
                    </a>

                    <a href="#" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-calendar-check"></i>
                        <span>ENTREVISTAS</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-handshake"></i>
                        <span>CONTRATACIONES</span>
                    </a>
                    <a href="{{ route('afiches.index') }}" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-image"></i>
                        <span>AFICHES</span>
                    </a>
                    <a href="#" class="flex items-center space-x-3 text-white/80 hover:text-white hover:bg-white/10 px-4 py-3 rounded-lg transition-colors">
                        <i class="fas fa-cog"></i>
                        <span>CONFIGURACIÓN</span>
                    </a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-6">
                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
                    <!-- Total Postulantes -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
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
                    </div>

                    <!-- Requerimientos Activos -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-briefcase text-orange-600 text-xl"></i>
                            </div>
                            <span class="text-green-500 text-sm font-medium">{{ $variacionRequerimientos > 0 ? '+' : '' }}{{ $variacionRequerimientos }}%</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalRequerimientos }}</h3>
                        <p class="text-gray-600 text-sm">Requerimientos activos</p>
                        <p class="text-xs text-gray-500 mt-1">Vacantes abiertas</p>
                    </div>

                    <!-- Entrevistas Programadas -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-alt text-green-600 text-xl"></i>
                            </div>
                            <span class="text-blue-500 text-sm font-medium">Hoy</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-1">6</h3>
                        <p class="text-gray-600 text-sm">Entrevistas programadas hoy</p>
                        <p class="text-xs text-gray-500 mt-1">entrevistas</p>
                    </div>

                    <!-- Contrataciones -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                            </div>
                            <span class="text-green-500 text-sm font-medium">+5</span>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-1">18</h3>
                        <p class="text-gray-600 text-sm">Contrataciones este mes</p>
                        <p class="text-xs text-gray-500 mt-1">contratados</p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover text-white">
                        <div class="text-center">
                            <i class="fas fa-plus-circle text-3xl mb-3"></i>
                            <h3 class="font-semibold mb-2">Acciones Rápidas</h3>
                            <button class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition-colors">
                                Nuevo Requerimiento
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid lg:grid-cols-2 gap-6 mb-8">
                    <!-- Postulaciones por Sede -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Postulaciones por Sede</h3>
                            <i class="fas fa-chart-bar text-blue-500"></i>
                        </div>

                        <div class="space-y-4">
                            @php
                            /* Colores tailwind para las barras (cicla si hay más sedes) */
                            $colores = ['blue-500', 'green-500', 'yellow-500', 'red-500', 'purple-500', 'orange-500'];
                            @endphp

                            @foreach ($porSede as $idx => $sede)
                            @php
                            $porcentaje = $maxTotalSede
                            ? round(($sede->total / $maxTotalSede) * 100)
                            : 0;
                            $color = $colores[$idx % count($colores)];
                            @endphp

                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 capitalize">
                                    {{ strtolower($sede->ciudad) }}
                                </span>

                                <div class="flex items-center space-x-3 flex-1 mx-4">
                                    <div class="flex-1 bg-gray-200 rounded-full h-3 overflow-hidden">
                                        <div class="bg-{{ $color }} h-3" style="width: {{ $porcentaje }}%"></div>
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
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Estado de Postulantes</h3>
                            <i class="fas fa-user-check text-green-500"></i>
                        </div>
                        <div class="space-y-4">
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
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Próximas Entrevistas Programadas</h3>
                            <i class="fas fa-calendar-check text-blue-500"></i>
                        </div>
                        <div class="overflow-x-auto">
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
                                            <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">Pendiente</span>
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
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Alertas Recientes</h3>
                            <i class="fas fa-bell text-yellow-500"></i>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-start space-x-3 p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Faltan validar 5 postulantes</p>
                                    <p class="text-xs text-gray-600">Hace 2 horas</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                                <i class="fas fa-info-circle text-blue-500 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Requerimiento urgente sin oficina</p>
                                    <p class="text-xs text-gray-600">Hace 4 horas</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3 p-3 bg-orange-50 rounded-lg border-l-4 border-orange-400">
                                <i class="fas fa-clock text-orange-500 mt-1"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Postulante con entrevista reprogramada</p>
                                    <p class="text-xs text-gray-600">Hace 6 horas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        // Toggle user dropdown
        function toggleUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('user-dropdown');
            const userInfo = event.target.closest('[onclick="toggleUserDropdown()"]');

            if (!userInfo && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Add some interactivity
        document.addEventListener('DOMContentLoaded', function() {
            // Animate progress bars
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
</x-app-layout>