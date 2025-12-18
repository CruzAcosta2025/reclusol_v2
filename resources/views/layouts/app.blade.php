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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <!-- En la sección <head> de tu layout, o antes del cierre de </body> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    @php
        /**
         * Para resaltar el módulo activo en el sidebar, en cada vista agrega:
         * @section('module', 'dashboard|solicitudes|postulantes|afiches|entrevistas|usuarios|configuracion')
         */
        $module = trim($__env->yieldContent('module', 'dashboard'));

        $titles = [
            'dashboard' => 'Resumen general del sistema de reclutamiento',
            'solicitudes' => 'Módulo: Solicitudes',
            'postulantes' => 'Módulo: Postulantes',
            'afiches' => 'Módulo: Afiches',
            'entrevistas' => 'Módulo: Entrevistas',
            'usuarios' => 'Módulo: Usuarios',
        ];
        $pageTitle = $titles[$module] ?? $titles['dashboard'];

        // Evita error si una vista no manda notificaciones
        $notificaciones = $notificaciones ?? collect();
        $notiCount = method_exists($notificaciones, 'count') ? $notificaciones->count() : 0;

        // Abrir submenús según módulo
        $openSolicitudes = $module === 'solicitudes';
        $openPostulantes = $module === 'postulantes';
        $openAfiches = $module === 'afiches';
    @endphp

    <!-- Custom Styles for RECLUSOL -->
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Fondo profesional (glass + gradientes suaves) */
        .reclusol-bg {
            background:
                radial-gradient(1200px circle at 10% 10%, rgba(56, 189, 248, .18), transparent 45%),
                radial-gradient(900px circle at 90% 20%, rgba(99, 102, 241, .16), transparent 42%),
                radial-gradient(1100px circle at 50% 95%, rgba(34, 197, 94, .12), transparent 40%),
                linear-gradient(180deg, #0b1220 0%, #0a1020 55%, #070b14 100%);
        }

        /* Mantengo tu clase gradient-bg por compatibilidad, pero mejorada */
        .gradient-bg {
            background: transparent;
        }

        .glass {
            background: rgba(255, 255, 255, .08);
            border: 1px solid rgba(255, 255, 255, .12);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        .glass-strong {
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .16);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .shadow-soft {
            box-shadow: 0 10px 35px rgba(0, 0, 0, .35);
        }

        .card {
            border-radius: 1.25rem;
        }

        .pill {
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .08);
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: .75rem;
            width: 100%;
            border-radius: .9rem;
            padding: .75rem 1rem;
            font-size: .9rem;
            font-weight: 700;
            line-height: 1.25rem;
            transition: background-color .15s ease, color .15s ease, border-color .15s ease, transform .15s ease;
            text-decoration: none;
        }

        .nav-item:hover {
            background: rgba(255, 255, 255, .08)
        }

        .nav-item-active {
            background: linear-gradient(135deg, rgba(56, 189, 248, .28), rgba(99, 102, 241, .22));
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .18);
        }
    </style>
</head>

<body class="font-sans antialiased reclusol-bg text-white">
    <div x-data>
        <!-- Header -->
        <header class="sticky top-0 z-40">
            <div class="glass-strong border-b border-white/10">
                <div class="px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between gap-4">

                        <!-- Left: toggle + brand -->
                        <div class="flex items-center gap-3">
                            <button type="button"
                                class="h-10 w-10 rounded-xl pill grid place-items-center hover:bg-white/10 transition"
                                @click="$store.ui.toggleSidebar()" title="Ocultar/Mostrar menú">
                                <i class="fas fa-bars"></i>
                            </button>

                            <div class="hidden sm:flex items-center gap-3">
                                <div class="h-10 w-10 rounded-2xl grid place-items-center shadow-soft"
                                    style="background: linear-gradient(135deg, rgba(56,189,248,.9), rgba(99,102,241,.9));">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div class="leading-tight">
                                    <div class="font-semibold tracking-wide">RECLUSOL</div>
                                    <div class="text-xs text-white/70">Plataforma de reclutamiento</div>
                                </div>
                            </div>
                        </div>

                        <!-- Center Title -->
                        <div class="hidden md:block flex-1 text-center">
                            <h1 class="text-sm sm:text-base font-semibold tracking-wide text-white">
                                {{ $pageTitle }}
                            </h1>
                        </div>

                        <!-- Right -->
                        <div class="flex items-center gap-3">
                            <div class="hidden lg:flex items-center gap-2 text-white/80 pill rounded-xl px-3 py-1.5">
                                <i class="fas fa-calendar-alt text-white/80"></i>
                                <span
                                    class="text-sm">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                            </div>

                            <!-- User Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open=!open"
                                    class="flex items-center gap-3 rounded-2xl px-3 py-2 pill hover:bg-white/10 transition">
                                    <div class="h-9 w-9 rounded-xl grid place-items-center bg-white/10">
                                        <i class="fas fa-user text-sm"></i>
                                    </div>
                                    <div class="hidden sm:block text-left leading-tight">
                                        <div class="text-sm font-semibold">{{ Auth::user()->name ?? 'INVITADO' }}</div>
                                        <div class="text-xs text-white/70">{{ Auth::user()->rol ?? 'Sin rol' }}</div>
                                    </div>
                                    <div class="hidden sm:grid place-items-center h-7 w-7 rounded-lg bg-white/10">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </button>

                                <div x-show="open" x-cloak @click.away="open=false" x-transition
                                    class="absolute right-0 mt-3 w-[22rem] sm:w-96 overflow-hidden rounded-2xl bg-white text-gray-800 shadow-soft border border-gray-200 z-50">
                                    <!-- Dropdown Header -->
                                    <div class="p-4 bg-gradient-to-r from-sky-50 to-indigo-50 border-b border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 rounded-2xl grid place-items-center bg-white shadow">
                                                <i class="fas fa-user text-indigo-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ Auth::user()->name ?? 'INVITADO' }}</div>
                                                <div class="text-sm text-gray-600">{{ Auth::user()->rol ?? 'Sin rol' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Notifications -->
                                    <div class="p-4">
                                        <div class="flex items-center justify-between mb-3">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-bell text-amber-500"></i>
                                                <div class="font-semibold">Notificaciones</div>
                                            </div>
                                            <span class="text-xs font-semibold text-white px-2 py-1 rounded-full"
                                                style="background: linear-gradient(135deg,#ef4444,#f97316);">
                                                {{ $notiCount }}
                                            </span>
                                        </div>

                                        <div class="space-y-2 max-h-60 overflow-y-auto">
                                            @forelse ($notificaciones as $noti)
                                                @php
                                                    $data = $noti->data ?? [];
                                                    $titulo = $data['titulo'] ?? null;
                                                    $mensaje = $data['mensaje'] ?? 'Tienes una nueva notificación.';
                                                    $nivel = $data['nivel'] ?? 'info'; // info|alerta|urgente
                                                    $url = $data['url'] ?? null;
                                                    $icono = $data['icono'] ?? null; // ej: user-plus, shield-alert, clock, etc.

                                                    // Estilos por nivel
                                                    $bg = match ($nivel) {
                                                        'urgente' => 'bg-red-100',
                                                        'alerta' => 'bg-amber-100',
                                                        default => 'bg-sky-100',
                                                    };

                                                    $text = match ($nivel) {
                                                        'urgente' => 'text-red-600',
                                                        'alerta' => 'text-amber-600',
                                                        default => 'text-sky-600',
                                                    };

                                                    // Fallback de iconos si aún no mandas icono en data
                                                    if (!$icono) {
                                                        $icono = match ($nivel) {
                                                            'urgente' => 'exclamation-triangle',
                                                            'alerta' => 'bell',
                                                            default => 'info-circle',
                                                        };
                                                    }

                                                    $isUnread = is_null($noti->read_at);
                                                @endphp

                                                <a href="{{ $url ?: '#' }}" class="block"
                                                    @if (!$url) role="button" @endif>
                                                    <div
                                                        class="flex items-start gap-3 p-2 rounded-xl hover:bg-gray-50 transition cursor-pointer {{ $isUnread ? 'ring-1 ring-indigo-200 bg-indigo-50/40' : '' }}">

                                                        <div
                                                            class="h-9 w-9 rounded-xl grid place-items-center flex-shrink-0 {{ $bg }}">
                                                            <i
                                                                class="fas fa-{{ $icono }} {{ $text }} text-xs"></i>
                                                        </div>

                                                        <div class="min-w-0 flex-1">
                                                            @if ($titulo)
                                                                <div
                                                                    class="text-sm font-semibold text-gray-900 break-words">
                                                                    {{ $titulo }}
                                                                    @if ($isUnread)
                                                                        <span
                                                                            class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white">
                                                                            NUEVA
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                                <div class="text-sm text-gray-700 break-words mt-0.5">
                                                                    {{ $mensaje }}
                                                                </div>
                                                            @else
                                                                {{-- Compatibilidad con tus notificaciones antiguas (solo mensaje) --}}
                                                                <div
                                                                    class="text-sm font-medium text-gray-900 break-words">
                                                                    {{ $mensaje }}
                                                                </div>
                                                            @endif

                                                            @if (!empty($data['nombre']))
                                                                <div class="text-xs text-gray-500 mt-1">
                                                                    {{ $data['nombre'] }}</div>
                                                            @endif

                                                            <div class="text-xs text-gray-500 mt-1">
                                                                {{ $noti->created_at->diffForHumans() }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            @empty
                                                <div class="text-center text-gray-400 text-sm py-6">
                                                    No tienes notificaciones nuevas.
                                                </div>
                                            @endforelse
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <a href="#"
                                                class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                                                Ver todas las notificaciones
                                            </a>
                                        </div>
                                    </div>

                                    <!-- Footer -->
                                    <div class="p-4 bg-gray-50 border-t border-gray-200">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit"
                                                class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold text-white transition"
                                                style="background: linear-gradient(135deg,#ef4444,#dc2626);">
                                                <i class="fas fa-sign-out-alt"></i>
                                                <span>Cerrar Sesión</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Mobile menu button -->
                            <button type="button"
                                class="sm:hidden h-10 w-10 rounded-xl pill grid place-items-center hover:bg-white/10 transition"
                                @click="$store.ui.mobileMenuOpen = !$store.ui.mobileMenuOpen" title="Menú">
                                <i class="fas"
                                    :class="$store.ui.mobileMenuOpen ? 'fa-xmark' : 'fa-ellipsis-vertical'"></i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside
                class="hidden sm:flex flex-col glass border-r border-white/10 min-h-[calc(100vh-4rem)] transition-all duration-300"
                :class="$store.ui.sidebarOpen ? 'w-72' : 'w-20'">
                <nav class="p-4 space-y-1">
                    <a href="{{ url('/dashboard') }}"
                        class="nav-item {{ $module === 'dashboard' ? 'nav-item-active text-white' : 'text-white/80' }}">
                        <i class="fas fa-gauge-high text-white/90"></i>
                        <span x-show="$store.ui.sidebarOpen" x-transition>Dashboard</span>
                    </a>

                    <div class="my-3 border-t border-white/10"></div>

                    <!-- SOLICITUDES -->
                    <div x-data="{ open: {{ $openSolicitudes ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="nav-item w-full justify-between {{ $module === 'solicitudes' ? 'nav-item-active text-white' : 'text-white/80' }}">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-file-lines"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Solicitudes</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition class="mt-2 ml-2 space-y-1">
                            <a href="{{ route('requerimientos.requerimiento') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-plus text-xs"></i> Crear Solicitud
                            </a>
                            <a href="{{ route('requerimientos.filtrar') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-list text-xs"></i> Ver Solicitudes
                            </a>
                        </div>
                    </div>

                    <!-- POSTULANTES -->
                    <div x-data="{ open: {{ $openPostulantes ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="nav-item w-full justify-between {{ $module === 'postulantes' ? 'nav-item-active text-white' : 'text-white/80' }}">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-users"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Postulantes</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition class="mt-2 ml-2 space-y-1">
                            <a href="{{ route('postulantes.formInterno') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-plus text-xs"></i> Crear Postulante
                            </a>
                            <a href="{{ route('postulantes.filtrar') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-list text-xs"></i> Ver Postulantes
                            </a>
                        </div>
                    </div>

                    <!-- AFICHES -->
                    <div x-data="{ open: {{ $openAfiches ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                            class="nav-item w-full justify-between {{ $module === 'afiches' ? 'nav-item-active text-white' : 'text-white/80' }}">
                            <span class="flex items-center gap-3">
                                <i class="fa-regular fa-images"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Afiches</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition class="mt-2 ml-2 space-y-1">
                            <a href="{{ route('afiches.index') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-image text-xs"></i> Generar Afiche
                            </a>
                            <a href="{{ route('afiches.assets.upload') }}"
                                class="flex items-center gap-2 rounded-xl px-3 py-2 text-sm text-white/85 hover:text-white hover:bg-white/10 transition">
                                <i class="fas fa-plus text-xs"></i> Añadir recursos
                            </a>
                        </div>
                    </div>

                    <!-- ENTREVISTAS -->
                    <a href="{{ route('entrevistas.index') }}"
                        class="nav-item {{ $module === 'entrevistas' ? 'nav-item-active text-white' : 'text-white/80' }}">
                        <i class="fas fa-calendar-check"></i>
                        <span x-show="$store.ui.sidebarOpen" x-transition>Entrevistas</span>
                    </a>

                    <!-- USUARIOS -->
                    @role('ADMINISTRADOR')
                        <a href="{{ route('usuarios.index') }}"
                            class="nav-item {{ $module === 'usuarios' ? 'nav-item-active text-white' : 'text-white/80' }}">
                            <i class="fas fa-users-gear"></i>
                            <span x-show="$store.ui.sidebarOpen" x-transition>Usuarios</span>
                        </a>
                    @endrole

                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Alpine store: Sidebar + menú móvil (persistente)
        document.addEventListener('alpine:init', () => {
            Alpine.store('ui', {
                sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
                mobileMenuOpen: false,
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
                },
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
