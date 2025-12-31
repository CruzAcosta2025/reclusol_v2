<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RECLUSOL') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_app.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN (funciona siempre, incluso si Vite no) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Alpine.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>

    <!-- En la sección <head> de tu layout, o antes del cierre de </body> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 768,
        isMobile: window.innerWidth < 768
    }"
        @resize.window="isMobile = window.innerWidth < 768; if(isMobile && !sidebarOpen) sidebarOpen = false;"
        @close-parent-sidebar.window="sidebarOpen = false" class="flex h-screen w-screen overflow-hidden">

        @include('components.sidebar', [
            'logo' => 'RECLUSOL',
            'subtitle' => 'Sistema de Gestión',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'icon' => 'fa-tachometer-alt'],
                ['label' => 'Solicitudes', 'href' => route('requerimientos.filtrar'), 'icon' => 'fa-file-alt'],
                ['label' => 'Postulantes', 'href' => route('postulantes.filtrar'), 'icon' => 'fa-users'],
                [
                    'label' => 'Afiches',
                    'icon' => 'fa-images',
                    'subitems' => [
                        ['label' => 'Crear Afiche', 'href' => route('afiches.index'), 'icon' => 'fa-plus-circle'],
/*                         ['label' => 'Historial', 'href' => route('afiches.historial'), 'icon' => 'fa-history'],
 */                        ['label' => 'Recursos', 'href' => route('afiches.assets.form'), 'icon' => 'fa-folder-open'],
                    ],
                ],
                ['label' => 'Entrevistas', 'href' => route('entrevistas.index'), 'icon' => 'fa-calendar-check'],
                ['label' => 'Usuarios', 'href' => route('usuarios.index'), 'icon' => 'fa-users-cog'],
                ['label' => 'Configuración', 'href' => route('configuracion.index'), 'icon' => 'fa-cog'],
            ],
            'logout' => [
                'label' => 'Logout',
                'href' => route('logout'),
                'icon' => 'fa-sign-out-alt',
            ],
        ])

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white/10 border-b border-white/20 z-40">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <!-- Left: Sidebar button + Date -->
                        <div class="flex items-center space-x-3">
                            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-neutral focus:outline-none">
                                <svg class="w-6 h-6 text-M2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"></path>
                                </svg>
                            </button>

                            <div class="hidden sm:flex items-center space-x-2 text-M2">
                                <i class="fas fa-calendar-alt text-accent"></i>
                                <span class="text-sm">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                            </div>
                        </div>

                        <!-- Center Title -->
                        <div class="hidden lg:block text-center flex-1">
                            <h1 class="text-base text-M2 font-semibold">Sistema de reclutamiento - RECLUSOL </h1>
                        </div>

                        <!-- Right: User Info -->
                        <div class="flex items-center space-x-3 relative">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-M2 text-sm"></i>
                            </div>
                            <div id="user-trigger" class="text-white cursor-pointer" onclick="toggleUserDropdown()">
                                <h2 class=" text-sm font-semibold text-M3"> {{ Auth::user()->name ?? 'INVITADO' }} </h2>
                                <p class="text-xs text-M3"> {{ Auth::user()->rol ?? 'Sin rol' }}
                                </p>
                            </div>

                            <!-- User Dropdown -->
                            <div id="user-dropdown"
                                class="absolute top-full right-0 left-auto mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 hidden z-50">
                                <!-- Dropdown Header -->
                                <div class="p-4 border-b border-gray-200 bg-blue-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-user text-M2 text-lg"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xs font-semibold text-M2">{{ Auth::user()->name ?? 'INVITADO' }}</h3>
                                            <p class="text-xs text-M3">
                                                {{ Auth::user()->rol ?? 'Sin rol' }} </p>
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
                                        <span class="bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                                            {{ ($notificaciones ?? collect())->count() }}
                                        </span>
                                    </div>

                                    <div class="space-y-3 max-h-60 overflow-y-auto">
                                        @forelse ($notificaciones ?? collect() as $noti)
                                            <div
                                                class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg cursor-pointer">
                                                {{-- Ícono y color según tipo de notificación --}}
                                                <div
                                                    class="w-8 h-8
@if ($noti->type == 'App\\Notifications\\PostulanteEnListaNegra') bg-red-100
@elseif($noti->type == 'App\\Notifications\\NuevoRequerimientoCreado')
    bg-green-100
@elseif(str_contains($noti->data['mensaje'] ?? '', 'reprogramada'))
    bg-orange-100
@elseif(str_contains($noti->data['mensaje'] ?? '', 'urgente'))
    bg-blue-100
@else
    bg-yellow-100 @endif
rounded-full flex items-center justify-center flex-shrink-0">

                                                    @if ($noti->type == 'App\\Notifications\\PostulanteEnListaNegra')
                                                        <i class="fas fa-user-slash text-red-600 text-xs"></i>
                                                    @elseif($noti->type == 'App\\Notifications\\NuevoRequerimientoCreado')
                                                        <i class="fas fa-users text-green-600 text-xs"></i>
                                                    @elseif(str_contains($noti->data['mensaje'] ?? '', 'reprogramada'))
                                                        <i class="fas fa-clock text-orange-600 text-xs"></i>
                                                    @elseif(str_contains($noti->data['mensaje'] ?? '', 'urgente'))
                                                        <i class="fas fa-info-circle text-blue-600 text-xs"></i>
                                                    @else
                                                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xs"></i>
                                                    @endif
                                                </div>

                                                <div class="flex-1">
                                                    <p class="text-sm font-medium text-gray-800">
                                                        {{ $noti->data['mensaje'] ?? 'Tienes una nueva notificación.' }}
                                                        @if (!empty($noti->data['nombre']))
                                                            <br>
                                                            <span class="text-xs text-gray-500">
                                                                {{ $noti->data['nombre'] }}
                                                            </span>
                                                        @endif
                                                    </p>
                                                    <p class="text-xs text-gray-500">{{ $noti->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center text-gray-400 text-sm py-4">
                                                No tienes notificaciones nuevas.
                                            </div>
                                        @endforelse
                                    </div>

                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                        <a href="#" class="text-sm text-M4 hover:text-M2 font-medium">Ver
                                            todas las notificaciones</a>
                                    </div>
                                </div>

                                <!-- Dropdown Footer -->
                                <div class="p-4 border-t border-gray-200 bg-gray-50">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full flex items-center justify-center space-x-2 bg-red-500 hover:bg-red-600 text-neutral-light px-4 py-2 rounded-lg transition-colors">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span class="text-sm">Cerrar Sesión</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- Page Content -->
            <main class="flex-1 p-7 bg-gray-50 overflow-y-auto overflow-x-hidden" style="min-width: 0;">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Toggle user dropdown visibility
        function toggleUserDropdown() {
            var dd = document.getElementById('user-dropdown');
            if (!dd) return;
            dd.classList.toggle('hidden');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            var dd = document.getElementById('user-dropdown');
            var trigger = document.getElementById('user-trigger');
            if (!dd || !trigger) return;
            var target = e.target;
            if (dd.classList.contains('hidden')) return; // already closed
            if (trigger.contains(target) || dd.contains(target)) return; // click inside
            dd.classList.add('hidden');
        });

        // Close on ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var dd = document.getElementById('user-dropdown');
                if (dd && !dd.classList.contains('hidden')) dd.classList.add('hidden');
            }
        });
    </script>
</body>
</html>