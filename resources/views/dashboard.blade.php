@extends('layouts.app')

@section('content')
    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Fondo y “glass” profesional (sin tocar tu lógica) */
        .reclusol-bg {
            background:
                radial-gradient(1200px circle at 10% 10%, rgba(56, 189, 248, .18), transparent 45%),
                radial-gradient(900px circle at 90% 20%, rgba(99, 102, 241, .16), transparent 42%),
                radial-gradient(1100px circle at 50% 95%, rgba(34, 197, 94, .12), transparent 40%),
                linear-gradient(180deg, #0b1220 0%, #0a1020 55%, #070b14 100%);
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

        .btn-primary{
            display:inline-flex;align-items:center;gap:.5rem;
            border-radius: .9rem;
            padding:.55rem 1rem;
            font-size:.875rem;line-height:1.25rem;
            font-weight:700;
            color:#fff;
            transition:filter .15s ease, transform .15s ease, box-shadow .15s ease;
            background: linear-gradient(135deg, rgba(56, 189, 248, .95), rgba(99, 102, 241, .95));
            box-shadow: 0 10px 25px rgba(56,189,248,.18);
            text-decoration:none;
        }
        .btn-primary:hover{filter:brightness(1.05);transform:translateY(-1px)}
        .btn-primary:active{transform:translateY(0)}
        .nav-item{
            display:flex;align-items:center;gap:.75rem;
            width:100%;
            border-radius: .9rem;
            padding:.75rem 1rem;
            font-size:.9rem;
            font-weight:700;
            line-height:1.25rem;
            transition:background-color .15s ease, color .15s ease, border-color .15s ease, transform .15s ease;
            text-decoration:none;
        }
        .nav-item:hover{background: rgba(255, 255, 255, .08)}
        .nav-item-active{
            background: linear-gradient(135deg, rgba(56, 189, 248, .28), rgba(99, 102, 241, .22));
            border: 1px solid rgba(255, 255, 255, .10);
        box-shadow: 0 10px 25px rgba(0,0,0,.18);
            }

        .pill {
            border: 1px solid rgba(255, 255, 255, .14);
            background: rgba(255, 255, 255, .08);
        }
    </style>

    @php
        // ====== Datos para gráficos (sin inventar números nuevos) ======
        $labelsSede = collect($porSede ?? [])->map(function ($s) {
            return ucwords(strtolower($s->nombre_departamento ?? 'Sin sede'));
        })->values();

        $totalesSede = collect($porSede ?? [])->map(function ($s) {
            return (int) ($s->total ?? 0);
        })->values();

        // Si luego lo pasas desde el controller, reemplazas $estadoPostulantes.
        $estadoPostulantes = $estadoPostulantes ?? [
            'Apto' => 140,
            'Pendiente' => 40,
            'En entrevista' => 53,
            'No Apto' => 15,
        ];

        $estadoLabels = array_keys($estadoPostulantes);
        $estadoValues = array_values($estadoPostulantes);

        $notiCount = ($notificaciones ?? collect())->count();
    @endphp

    <div class="min-h-screen reclusol-bg text-white" x-data x-cloak>
        <!-- Topbar -->
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
                                <div
                                    class="h-10 w-10 rounded-2xl grid place-items-center shadow-soft"
                                    style="background: linear-gradient(135deg, rgba(56,189,248,.9), rgba(99,102,241,.9));">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                <div class="leading-tight">
                                    <div class="font-semibold tracking-wide">RECLUSOL</div>
                                    <div class="text-xs text-white/70">Recruiting Dashboard</div>
                                </div>
                            </div>
                        </div>

                        <!-- Center: dynamic title -->
                        <div class="hidden md:block flex-1 text-center">
                            <h1 class="text-sm sm:text-base font-semibold tracking-wide text-white"
                                x-text="({
                                    dashboard: 'Resumen general del sistema de reclutamiento',
                                    solicitudes: 'Módulo: Solicitudes',
                                    postulantes: 'Módulo: Postulantes',
                                    afiches: 'Módulo: Afiches',
                                    entrevistas: 'Módulo: Entrevistas',
                                    usuarios: 'Módulo: Usuarios',
                                    configuracion: 'Módulo: Configuración'
                                }[$store.ui.currentModule] || 'Resumen general del sistema de reclutamiento')">
                            </h1>
                        </div>

                        <!-- Right: date + user -->
                        <div class="flex items-center gap-3">
                            <div class="hidden lg:flex items-center gap-2 text-white/80 pill rounded-xl px-3 py-1.5">
                                <i class="fas fa-calendar-alt text-white/80"></i>
                                <span class="text-sm">{{ now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</span>
                            </div>

                            <!-- User dropdown (Alpine, sin onclick) -->
                            <div class="relative" x-data="{ open:false }">
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
                                    <!-- Header -->
                                    <div class="p-4 bg-gradient-to-r from-sky-50 to-indigo-50 border-b border-gray-200">
                                        <div class="flex items-center gap-3">
                                            <div class="h-12 w-12 rounded-2xl grid place-items-center bg-white shadow">
                                                <i class="fas fa-user text-indigo-600 text-lg"></i>
                                            </div>
                                            <div>
                                                <div class="font-semibold">{{ Auth::user()->name ?? 'INVITADO' }}</div>
                                                <div class="text-sm text-gray-600">{{ Auth::user()->rol ?? 'Sin rol' }}</div>
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
                                            @forelse (($notificaciones ?? collect()) as $noti)
                                                <div class="flex items-start gap-3 p-2 rounded-xl hover:bg-gray-50 transition cursor-pointer">
                                                    <div class="h-9 w-9 rounded-xl grid place-items-center flex-shrink-0
                                                        @if ($noti->type == 'App\Notifications\PostulanteEnListaNegra') bg-red-100
                                                        @elseif($noti->type == 'App\Notifications\NuevoRequerimientoCreado') bg-green-100
                                                        @elseif(str_contains($noti->data['mensaje'] ?? '', 'reprogramada')) bg-orange-100
                                                        @elseif(str_contains($noti->data['mensaje'] ?? '', 'urgente')) bg-blue-100
                                                        @else bg-amber-100 @endif">
                                                        @if ($noti->type == 'App\Notifications\PostulanteEnListaNegra')
                                                            <i class="fas fa-user-slash text-red-600 text-xs"></i>
                                                        @elseif($noti->type == 'App\Notifications\NuevoRequerimientoCreado')
                                                            <i class="fas fa-users text-green-600 text-xs"></i>
                                                        @elseif(str_contains($noti->data['mensaje'] ?? '', 'reprogramada'))
                                                            <i class="fas fa-clock text-orange-600 text-xs"></i>
                                                        @elseif(str_contains($noti->data['mensaje'] ?? '', 'urgente'))
                                                            <i class="fas fa-info-circle text-blue-600 text-xs"></i>
                                                        @else
                                                            <i class="fas fa-exclamation-triangle text-amber-600 text-xs"></i>
                                                        @endif
                                                    </div>

                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 break-words">
                                                            {{ $noti->data['mensaje'] ?? 'Tienes una nueva notificación.' }}
                                                            @if (!empty($noti->data['nombre']))
                                                                <div class="text-xs text-gray-500">{{ $noti->data['nombre'] }}</div>
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            {{ $noti->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="text-center text-gray-400 text-sm py-6">
                                                    No tienes notificaciones nuevas.
                                                </div>
                                            @endforelse
                                        </div>

                                        <div class="mt-3 pt-3 border-t border-gray-200">
                                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
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

                            <!-- Mobile menu button (opcional) -->
                            <button type="button" class="sm:hidden h-10 w-10 rounded-xl pill grid place-items-center hover:bg-white/10 transition"
                                @click="$store.ui.mobileMenuOpen = !$store.ui.mobileMenuOpen" title="Menú">
                                <i class="fas" :class="$store.ui.mobileMenuOpen ? 'fa-xmark' : 'fa-ellipsis-vertical'"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex">
            <!-- Sidebar -->
            <aside class="hidden sm:flex flex-col glass border-r border-white/10 min-h-[calc(100vh-4rem)] transition-all duration-300"
                :class="$store.ui.sidebarOpen ? 'w-72' : 'w-20'">
                <nav class="p-4 space-y-1">
                    <!-- DASHBOARD -->
                    <a href="#" @click.prevent="$store.ui.setModule('dashboard')"
                        class="nav-item text-white"
                        :class="$store.ui.currentModule === 'dashboard' ? 'nav-item-active' : 'text-white/80'">
                        <i class="fas fa-gauge-high text-white/90"></i>
                        <span x-show="$store.ui.sidebarOpen" x-transition>Dashboard</span>
                    </a>

                    <div class="my-3 border-t border-white/10"></div>

                    <!-- SOLICITUDES -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open; $store.ui.setModule('solicitudes')"
                            class="nav-item w-full justify-between"
                            :class="$store.ui.currentModule === 'solicitudes' ? 'nav-item-active text-white' : 'text-white/80'">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-file-lines"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Solicitudes</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>

                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition @click.away="open=false"
                            class="mt-2 ml-2 space-y-1">
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
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open; $store.ui.setModule('postulantes')"
                            class="nav-item w-full justify-between"
                            :class="$store.ui.currentModule === 'postulantes' ? 'nav-item-active text-white' : 'text-white/80'">
                            <span class="flex items-center gap-3">
                                <i class="fas fa-users"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Postulantes</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>

                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition @click.away="open=false"
                            class="mt-2 ml-2 space-y-1">
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
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open; $store.ui.setModule('afiches')"
                            class="nav-item w-full justify-between"
                            :class="$store.ui.currentModule === 'afiches' ? 'nav-item-active text-white' : 'text-white/80'">
                            <span class="flex items-center gap-3">
                                <i class="fa-regular fa-images"></i>
                                <span x-show="$store.ui.sidebarOpen" x-transition>Afiches</span>
                            </span>
                            <i x-show="$store.ui.sidebarOpen" class="fas text-xs"
                                :class="open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>

                        <div x-show="open && $store.ui.sidebarOpen" x-cloak x-transition @click.away="open=false"
                            class="mt-2 ml-2 space-y-1">
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
                    <a href="#" @click.prevent="$store.ui.setModule('entrevistas')"
                        class="nav-item"
                        :class="$store.ui.currentModule === 'entrevistas' ? 'nav-item-active text-white' : 'text-white/80'">
                        <i class="fas fa-calendar-check"></i>
                        <span x-show="$store.ui.sidebarOpen" x-transition>Entrevistas</span>
                    </a>

                    <!-- USUARIOS -->
                    @role('ADMINISTRADOR')
                        <a href="#" @click.prevent="$store.ui.setModule('usuarios')"
                            class="nav-item"
                            :class="$store.ui.currentModule === 'usuarios' ? 'nav-item-active text-white' : 'text-white/80'">
                            <i class="fas fa-users-gear"></i>
                            <span x-show="$store.ui.sidebarOpen" x-transition>Usuarios</span>
                        </a>
                    @endrole

                    <!-- CONFIG -->
                    <a href="#" @click.prevent="$store.ui.setModule('configuracion')"
                        class="nav-item"
                        :class="$store.ui.currentModule === 'configuracion' ? 'nav-item-active text-white' : 'text-white/80'">
                        <i class="fas fa-gear"></i>
                        <span x-show="$store.ui.sidebarOpen" x-transition>Configuración</span>
                    </a>

                    <div class="mt-6 px-2" x-show="$store.ui.sidebarOpen" x-transition>
                        <div class="rounded-2xl p-4 glass-strong">
                            <div class="text-xs text-white/70">Consejo rápido</div>
                            <div class="mt-1 text-sm font-semibold">Usa el panel para monitorear tu funnel</div>
                            <div class="mt-3 flex gap-2">
                                <a href="{{ route('postulantes.filtrar') }}"
                                    class="text-xs font-semibold rounded-xl px-3 py-2 bg-white/10 hover:bg-white/15 transition">
                                    Ver postulantes
                                </a>
                                <a href="{{ route('requerimientos.filtrar') }}"
                                    class="text-xs font-semibold rounded-xl px-3 py-2 bg-white/10 hover:bg-white/15 transition">
                                    Ver solicitudes
                                </a>
                            </div>
                        </div>
                    </div>

                </nav>
            </aside>

            <!-- Mobile drawer -->
            <div class="sm:hidden" x-show="$store.ui.mobileMenuOpen" x-cloak>
                <div class="fixed inset-0 bg-black/50 z-40" @click="$store.ui.mobileMenuOpen=false"></div>
                <div class="fixed left-0 top-16 bottom-0 w-80 glass z-50 border-r border-white/10 overflow-y-auto">
                    <nav class="p-4 space-y-1">
                        <a href="#" @click.prevent="$store.ui.setModule('dashboard'); $store.ui.mobileMenuOpen=false"
                            class="nav-item text-white"
                            :class="$store.ui.currentModule === 'dashboard' ? 'nav-item-active' : 'text-white/80'">
                            <i class="fas fa-gauge-high"></i> Dashboard
                        </a>

                        <div class="my-3 border-t border-white/10"></div>

                        <a href="{{ route('requerimientos.requerimiento') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-plus"></i> Crear Solicitud
                        </a>
                        <a href="{{ route('requerimientos.filtrar') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-list"></i> Ver Solicitudes
                        </a>
                        <a href="{{ route('postulantes.formInterno') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-plus"></i> Crear Postulante
                        </a>
                        <a href="{{ route('postulantes.filtrar') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-list"></i> Ver Postulantes
                        </a>
                        <a href="{{ route('afiches.index') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-image"></i> Generar Afiche
                        </a>
                        <a href="{{ route('afiches.assets.upload') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-plus"></i> Añadir recursos
                        </a>
                        <a href="{{ route('entrevistas.index') }}"
                            class="nav-item text-white/80 hover:text-white">
                            <i class="fas fa-calendar-check"></i> Entrevistas
                        </a>
                        @role('ADMINISTRADOR')
                            <a href="{{ route('usuarios.index') }}"
                                class="nav-item text-white/80 hover:text-white">
                                <i class="fas fa-users-gear"></i> Usuarios
                            </a>
                        @endrole
                    </nav>
                </div>
            </div>

            <!-- Main -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                <!-- Dashboard -->
                <div x-show="$store.ui.currentModule === 'dashboard'" x-cloak>
                    <!-- KPI Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 lg:gap-6 mb-6">
                        <!-- Total Postulantes -->
                        <div class="card bg-white text-gray-900 p-6 shadow-soft">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Postulantes</div>
                                    <div class="mt-1 text-3xl font-extrabold">{{ $totalPostulantes }}</div>
                                    <div class="mt-1 text-sm text-gray-600">Total registrados</div>
                                </div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center bg-sky-50">
                                    <i class="fas fa-users text-sky-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Variación</span>
                                <span class="text-sm font-semibold {{ $variacionPostulantes >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $variacionPostulantes > 0 ? '+' : '' }}{{ $variacionPostulantes }}%
                                </span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: {{ min(100, max(0, (int)abs($variacionPostulantes))) }}%; background: linear-gradient(135deg,#38bdf8,#6366f1);">
                                </div>
                            </div>
                        </div>

                        <!-- Requerimientos Activos -->
                        <div class="card bg-white text-gray-900 p-6 shadow-soft">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Requerimientos</div>
                                    <div class="mt-1 text-3xl font-extrabold">{{ $totalRequerimientos }}</div>
                                    <div class="mt-1 text-sm text-gray-600">Vacantes abiertas</div>
                                </div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center bg-orange-50">
                                    <i class="fas fa-briefcase text-orange-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Variación</span>
                                <span class="text-sm font-semibold {{ $variacionRequerimientos >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                    {{ $variacionRequerimientos > 0 ? '+' : '' }}{{ $variacionRequerimientos }}%
                                </span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: {{ min(100, max(0, (int)abs($variacionRequerimientos))) }}%; background: linear-gradient(135deg,#fb923c,#f97316);">
                                </div>
                            </div>
                        </div>

                        <!-- Entrevistas Hoy (placeholder como estaba) -->
                        <div class="card bg-white text-gray-900 p-6 shadow-soft">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Entrevistas</div>
                                    <div class="mt-1 text-3xl font-extrabold">6</div>
                                    <div class="mt-1 text-sm text-gray-600">Programadas hoy</div>
                                </div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center bg-emerald-50">
                                    <i class="fas fa-calendar-day text-emerald-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="mt-5 text-xs text-gray-500">Indicador diario</div>
                            <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: 60%; background: linear-gradient(135deg,#22c55e,#10b981);"></div>
                            </div>
                        </div>

                        <!-- Operativo (placeholder como estaba) -->
                        <div class="card bg-white text-gray-900 p-6 shadow-soft">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Personal Operativo</div>
                                    <div class="mt-1 text-3xl font-extrabold">18</div>
                                    <div class="mt-1 text-sm text-gray-600">Contrataciones</div>
                                </div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center bg-indigo-50">
                                    <i class="fa-solid fa-user-shield text-indigo-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Nuevos</span>
                                <span class="text-sm font-semibold text-emerald-600">+5</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: 55%; background: linear-gradient(135deg,#6366f1,#38bdf8);"></div>
                            </div>
                        </div>

                        <!-- Administrativo (placeholder como estaba) -->
                        <div class="card bg-white text-gray-900 p-6 shadow-soft">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-semibold text-gray-500">Personal Administrativo</div>
                                    <div class="mt-1 text-3xl font-extrabold">18</div>
                                    <div class="mt-1 text-sm text-gray-600">Contrataciones</div>
                                </div>
                                <div class="h-12 w-12 rounded-2xl grid place-items-center bg-purple-50">
                                    <i class="fa-solid fa-user-tie text-purple-600 text-lg"></i>
                                </div>
                            </div>
                            <div class="mt-5 flex items-center justify-between">
                                <span class="text-xs text-gray-500">Nuevos</span>
                                <span class="text-sm font-semibold text-emerald-600">+5</span>
                            </div>
                            <div class="mt-2 h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-2 rounded-full" style="width: 55%; background: linear-gradient(135deg,#a855f7,#6366f1);"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-6">
                        <!-- Bar chart: Postulaciones por Sede -->
                        <div class="card glass-strong p-6 shadow-soft">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <div class="text-sm font-semibold">Postulaciones por Sede</div>
                                    <div class="text-xs text-white/70">Distribución de postulantes según sede</div>
                                </div>
                                <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                    <i class="fas fa-chart-column mr-2"></i>Interactivo
                                </div>
                            </div>
                            <div class="bg-white rounded-2xl p-4">
                                <canvas id="chartSede" height="140"></canvas>
                            </div>

                            <!-- mini resumen visual (manteniendo tus datos) -->
                            <div class="mt-4 space-y-3">
                                @php
                                    $hex = ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6','#f97316'];
                                @endphp
                                @foreach (($porSede ?? []) as $idx => $sede)
                                    @php
                                        $porcentaje = $maxTotalSede ? round(($sede->total / $maxTotalSede) * 100) : 0;
                                        $colorHex = $hex[$idx % count($hex)];
                                    @endphp

                                    <div class="flex items-center gap-3">
                                        <div class="h-2.5 w-2.5 rounded-full" style="background: {{ $colorHex }}"></div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm text-white/85 capitalize">{{ strtolower($sede->nombre_departamento) }}</div>
                                                <div class="text-sm font-semibold text-white">{{ $sede->total }}</div>
                                            </div>
                                            <div class="mt-1 h-2 rounded-full bg-white/10 overflow-hidden">
                                                <div class="h-2 rounded-full" style="width: {{ $porcentaje }}%; background: {{ $colorHex }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if (empty($porSede) || (is_countable($porSede) && count($porSede) === 0))
                                    <div class="text-sm text-white/70">No hay datos por sede para mostrar.</div>
                                @endif
                            </div>
                        </div>

                        <!-- Donut chart: Estado de Postulantes -->
                        <div class="card glass-strong p-6 shadow-soft">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <div class="text-sm font-semibold">Estado de Postulantes</div>
                                    <div class="text-xs text-white/70">Funnel / pipeline de postulaciones</div>
                                </div>
                                <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                    <i class="fas fa-chart-pie mr-2"></i>Interactivo
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-center">
                                <div class="md:col-span-2 bg-white rounded-2xl p-4">
                                    <canvas id="chartEstado" height="160"></canvas>
                                </div>

                                <div class="md:col-span-3 space-y-3">
                                    <template x-data="{ labels: @json($estadoLabels), values: @json($estadoValues) }"></template>

                                    @php
                                        $estadoPills = [
                                            'Apto' => ['bg' => 'bg-emerald-500', 'soft' => 'bg-emerald-50', 'text' => 'text-emerald-700'],
                                            'Pendiente' => ['bg' => 'bg-amber-500', 'soft' => 'bg-amber-50', 'text' => 'text-amber-700'],
                                            'En entrevista' => ['bg' => 'bg-sky-500', 'soft' => 'bg-sky-50', 'text' => 'text-sky-700'],
                                            'No Apto' => ['bg' => 'bg-rose-500', 'soft' => 'bg-rose-50', 'text' => 'text-rose-700'],
                                        ];
                                    @endphp

                                    @foreach ($estadoPostulantes as $k => $v)
                                        @php
                                            $pill = $estadoPills[$k] ?? ['bg'=>'bg-indigo-500','soft'=>'bg-indigo-50','text'=>'text-indigo-700'];
                                        @endphp
                                        <div class="bg-white rounded-2xl p-4 flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="h-3 w-3 rounded-full {{ $pill['bg'] }}"></div>
                                                <div class="text-sm font-semibold text-gray-800">{{ $k }}</div>
                                            </div>
                                            <div class="text-sm font-extrabold {{ $pill['text'] }}">{{ $v }}</div>
                                        </div>
                                    @endforeach

                                    <div class="bg-white rounded-2xl p-4">
                                        <div class="text-xs text-gray-500">Tip</div>
                                        <div class="text-sm font-semibold text-gray-800">Convierte “Pendiente” a “Entrevista” con recordatorios automáticos.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bottom section -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                        <!-- Próximas Entrevistas -->
                        <div class="card glass-strong p-6 shadow-soft" x-data="{ q: '' }">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <div class="text-sm font-semibold">Próximas Entrevistas Programadas</div>
                                    <div class="text-xs text-white/70">Vista rápida de agenda</div>
                                </div>
                                <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                    <i class="fas fa-calendar-check mr-2"></i>Agenda
                                </div>
                            </div>

                            <div class="flex items-center gap-2 mb-4">
                                <div class="flex-1 relative">
                                    <i class="fas fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" x-model="q" placeholder="Buscar por postulante o cargo..."
                                        class="w-full pl-9 pr-3 py-2 rounded-xl bg-white text-gray-800 border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <button class="btn-primary" type="button" @click="q=''">
                                    <i class="fas fa-broom"></i> Limpiar
                                </button>
                            </div>

                            <div class="bg-white rounded-2xl overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-gray-700">
                                        <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                                            <tr>
                                                <th class="text-left px-4 py-3">Fecha</th>
                                                <th class="text-left px-4 py-3">Hora</th>
                                                <th class="text-left px-4 py-3">Postulante</th>
                                                <th class="text-left px-4 py-3">Cargo</th>
                                                <th class="text-left px-4 py-3">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                // Si luego lo pasas desde el controller, reemplazas $proximasEntrevistas.
                                                $proximasEntrevistas = $proximasEntrevistas ?? [
                                                    ['fecha'=>'2024-01-15','hora'=>'09:00 am','postulante'=>'Ruben Cruz','cargo'=>'Analista de datos','estado'=>'Completo'],
                                                    ['fecha'=>'2025-01-22','hora'=>'10:00 am','postulante'=>'Emma Acosta','cargo'=>'Secretaria','estado'=>'Pendiente'],
                                                    ['fecha'=>'2025-06-22','hora'=>'10:30 am','postulante'=>'Bryan Arteaga','cargo'=>'Analista Programador','estado'=>'Completo'],
                                                ];
                                            @endphp

                                            @foreach ($proximasEntrevistas as $row)
                                                <tr class="border-t border-gray-100 hover:bg-gray-50 transition"
                                                    x-show="(q.trim()==='' || ('{{ strtolower($row['postulante']) }} {{ strtolower($row['cargo']) }}').includes(q.toLowerCase()))">
                                                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['fecha'] }}</td>
                                                    <td class="px-4 py-3 whitespace-nowrap">{{ $row['hora'] }}</td>
                                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $row['postulante'] }}</td>
                                                    <td class="px-4 py-3">{{ $row['cargo'] }}</td>
                                                    <td class="px-4 py-3">
                                                        @php
                                                            $isPending = strtolower($row['estado']) === 'pendiente';
                                                        @endphp
                                                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold
                                                            {{ $isPending ? 'bg-amber-100 text-amber-800' : 'bg-emerald-100 text-emerald-800' }}">
                                                            {{ $row['estado'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
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
                                    <div class="text-xs text-white/70">Eventos que requieren atención</div>
                                </div>
                                <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                    <i class="fas fa-bell mr-2"></i>Alertas
                                </div>
                            </div>

                            <div class="space-y-3">
                                <div class="bg-white rounded-2xl p-4 border-l-4 border-amber-400">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-2xl grid place-items-center bg-amber-50">
                                            <i class="fas fa-triangle-exclamation text-amber-500"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900">Faltan validar 5 postulantes</div>
                                            <div class="text-xs text-gray-500 mt-1">Hace 2 horas</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-2xl p-4 border-l-4 border-sky-400">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-2xl grid place-items-center bg-sky-50">
                                            <i class="fas fa-circle-info text-sky-500"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900">Requerimiento urgente sin oficina</div>
                                            <div class="text-xs text-gray-500 mt-1">Hace 4 horas</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-white rounded-2xl p-4 border-l-4 border-orange-400">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-2xl grid place-items-center bg-orange-50">
                                            <i class="fas fa-clock text-orange-500"></i>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="text-sm font-semibold text-gray-900">Postulante con entrevista reprogramada</div>
                                            <div class="text-xs text-gray-500 mt-1">Hace 6 horas</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-white/85 hover:text-white transition">
                                    Ver historial de alertas <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Module panels inside home -->
                <div x-show="$store.ui.currentModule !== 'dashboard'" x-cloak class="space-y-6">
                    <div class="card glass-strong p-6 shadow-soft flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="text-sm font-semibold">Acciones del módulo</div>
                            <div class="text-xs text-white/70 mt-1">Estás viendo el módulo dentro del Inicio (Dashboard).</div>
                        </div>
                        <button class="btn-primary" @click="$store.ui.setModule('dashboard')">
                            <i class="fas fa-arrow-left"></i> Volver al Dashboard
                        </button>
                    </div>

                    <!-- SOLICITUDES -->
                    <div x-show="$store.ui.currentModule === 'solicitudes'" class="card glass-strong p-6 shadow-soft">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold">Solicitudes</div>
                                <div class="text-xs text-white/70 mt-1">Gestiona requerimientos y vacantes.</div>
                            </div>
                            <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                <i class="fas fa-file-lines mr-2"></i>Módulo
                            </div>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('requerimientos.requerimiento') }}"
                                class="btn-primary">
                                <i class="fas fa-plus"></i> Crear Solicitud
                            </a>
                            <a href="{{ route('requerimientos.filtrar') }}"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold bg-white/10 hover:bg-white/15 transition text-white">
                                <i class="fas fa-list"></i> Ver Solicitudes
                            </a>
                        </div>
                    </div>

                    <!-- POSTULANTES -->
                    <div x-show="$store.ui.currentModule === 'postulantes'" class="card glass-strong p-6 shadow-soft">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold">Postulantes</div>
                                <div class="text-xs text-white/70 mt-1">Registro, validación y seguimiento.</div>
                            </div>
                            <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                <i class="fas fa-users mr-2"></i>Módulo
                            </div>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('postulantes.formInterno') }}" class="btn-primary">
                                <i class="fas fa-plus"></i> Crear Postulante
                            </a>
                            <a href="{{ route('postulantes.filtrar') }}"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold bg-white/10 hover:bg-white/15 transition text-white">
                                <i class="fas fa-list"></i> Ver Postulantes
                            </a>
                        </div>
                    </div>

                    <!-- AFICHES -->
                    <div x-show="$store.ui.currentModule === 'afiches'" class="card glass-strong p-6 shadow-soft">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold">Afiches</div>
                                <div class="text-xs text-white/70 mt-1">Generación y recursos del módulo.</div>
                            </div>
                            <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                <i class="fa-regular fa-images mr-2"></i>Módulo
                            </div>
                        </div>
                        <div class="mt-5 flex flex-wrap gap-3">
                            <a href="{{ route('afiches.index') }}"
                                class="btn-primary">
                                <i class="fas fa-image"></i> Generar Afiche
                            </a>
                            <a href="{{ route('afiches.assets.upload') }}"
                                class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold bg-white/10 hover:bg-white/15 transition text-white">
                                <i class="fas fa-plus"></i> Añadir recursos
                            </a>
                        </div>
                    </div>

                    <!-- ENTREVISTAS -->
                    <div x-show="$store.ui.currentModule === 'entrevistas'" class="card glass-strong p-6 shadow-soft">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm font-semibold">Entrevistas</div>
                                <div class="text-xs text-white/70 mt-1">Agenda y gestión de entrevistas.</div>
                            </div>
                            <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                <i class="fas fa-calendar-check mr-2"></i>Módulo
                            </div>
                        </div>
                        <div class="mt-5">
                            <a href="{{ route('entrevistas.index') }}"
                                class="btn-primary">
                                <i class="fas fa-calendar-check"></i> Ver Entrevistas
                            </a>
                        </div>
                    </div>

                    <!-- USUARIOS -->
                    @role('ADMINISTRADOR')
                        <div x-show="$store.ui.currentModule === 'usuarios'" class="card glass-strong p-6 shadow-soft">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="text-sm font-semibold">Usuarios</div>
                                    <div class="text-xs text-white/70 mt-1">Administración de accesos.</div>
                                </div>
                                <div class="pill rounded-xl px-3 py-1.5 text-xs text-white/80">
                                    <i class="fas fa-users-gear mr-2"></i>Módulo
                                </div>
                            </div>
                            <div class="mt-5">
                                <a href="{{ route('usuarios.index') }}"
                                    class="btn-primary">
                                    <i class="fas fa-users-cog"></i> Gestionar Usuarios
                                </a>
                            </div>
                        </div>
                    @endrole

                    <!-- CONFIG -->
                    <div x-show="$store.ui.currentModule === 'configuracion'" class="card glass-strong p-6 shadow-soft">
                        <div class="text-sm font-semibold">Configuración</div>
                        <div class="text-xs text-white/70 mt-1">Aquí puedes mostrar accesos/configs cuando lo tengas listo.</div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Chart.js (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        // Alpine store: Sidebar + módulo actual (MISMA LÓGICA, mejorada y persistiendo estado)
        document.addEventListener('alpine:init', () => {
            Alpine.store('ui', {
                sidebarOpen: JSON.parse(localStorage.getItem('sidebarOpen') ?? 'true'),
                mobileMenuOpen: false,
                currentModule: localStorage.getItem('currentModule') ?? 'dashboard',
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                    localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
                },
                setModule(m) {
                    this.currentModule = m;
                    localStorage.setItem('currentModule', m);
                }
            });
        });

        // Charts (solo se basan en tus datos existentes)
        document.addEventListener('DOMContentLoaded', function() {
            const labelsSede = @json($labelsSede);
            const totalsSede = @json($totalesSede);
            const estadoLabels = @json($estadoLabels);
            const estadoValues = @json($estadoValues);

            // Paletas (hex) - evita problemas de Tailwind JIT con clases dinámicas
            const colors = ['#3b82f6','#22c55e','#f59e0b','#ef4444','#8b5cf6','#f97316','#06b6d4','#a855f7'];

            // Chart: Sedes (bar)
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
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: (ctx) => ` ${ctx.raw} postulantes`
                                }
                            }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: '#334155', font: { weight: '600' } }
                            },
                            y: {
                                grid: { color: 'rgba(15, 23, 42, .08)' },
                                ticks: { color: '#334155' }
                            }
                        }
                    }
                });
            }

            // Chart: Estado (donut)
            const ctxEstado = document.getElementById('chartEstado');
            if (ctxEstado && estadoLabels.length) {
                new Chart(ctxEstado, {
                    type: 'doughnut',
                    data: {
                        labels: estadoLabels,
                        datasets: [{
                            data: estadoValues,
                            borderWidth: 0,
                            backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9', '#f43f5e'],
                            hoverOffset: 6,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
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

    {{-- NOTA IMPORTANTE (para que la barra izquierda aparezca en OTRAS VISTAS):
        Este archivo deja el sidebar dentro de esta vista (como lo tenías).
        Si quieres que el sidebar se mantenga en TODAS LAS VISTAS (crear solicitud, ver postulantes, etc.),
        copia el bloque <header> y <aside> al archivo layouts.app (layout global) o a un componente Blade,
        y deja aquí solo el contenido del <main>.
        El estado de la barra ya se guarda en localStorage (sidebarOpen), así se respeta entre páginas.
    --}}
@endsection
