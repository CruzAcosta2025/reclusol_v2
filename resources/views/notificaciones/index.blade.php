@extends('layouts.app')

@section('module', 'notificaciones')

@section('content')
    <div class="space-y-6">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">
                            Notificaciones
                        </h2>
                        <p class="text-sm text-white/70 mt-1">
                            Listado completo de notificaciones del sistema.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <div class="pill rounded-xl px-4 py-2 text-sm">
                            <span class="text-white/70">Total</span>
                            <span class="ml-2 font-semibold text-white">{{ $totalNotificaciones }}</span>
                        </div>
                        <div class="pill rounded-xl px-4 py-2 text-sm">
                            <span class="text-white/70">Sin leer</span>
                            <span class="ml-2 font-semibold text-white">{{ $noLeidas }}</span>
                        </div>
                        <div class="pill rounded-xl px-4 py-2 text-sm">
                            <span class="text-white/70">Leidas</span>
                            <span class="ml-2 font-semibold text-white">{{ $leidas }}</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('notificaciones.index', ['estado' => 'todas']) }}"
                            class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $estado === 'todas' ? 'bg-white text-gray-900' : 'bg-white/10 text-white hover:bg-white/20' }}">
                            Todas
                        </a>
                        <a href="{{ route('notificaciones.index', ['estado' => 'no_leidas']) }}"
                            class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $estado === 'no_leidas' ? 'bg-white text-gray-900' : 'bg-white/10 text-white hover:bg-white/20' }}">
                            No leidas
                        </a>
                        <a href="{{ route('notificaciones.index', ['estado' => 'leidas']) }}"
                            class="px-4 py-2 rounded-xl text-sm font-semibold transition {{ $estado === 'leidas' ? 'bg-white text-gray-900' : 'bg-white/10 text-white hover:bg-white/20' }}">
                            Leidas
                        </a>
                    </div>
                    @if ($noLeidas > 0)
                        <form method="POST" action="{{ route('notificaciones.leer-todas') }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="px-4 py-2 rounded-xl text-sm font-semibold bg-indigo-600 hover:bg-indigo-700 text-white transition">
                                Marcar todas como leidas
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-3">
                @forelse ($notificacionesListado as $noti)
                    @php
                        $data = $noti->data ?? [];
                        $titulo = $data['titulo'] ?? null;
                        $mensaje = $data['mensaje'] ?? 'Tienes una nueva notificacion.';
                        $nivel = $data['nivel'] ?? 'info';
                        $url = $data['url'] ?? null;
                        $icono = $data['icono'] ?? null;

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

                        if (!$icono) {
                            $icono = match ($nivel) {
                                'urgente' => 'exclamation-triangle',
                                'alerta' => 'bell',
                                default => 'info-circle',
                            };
                        }

                        $isUnread = is_null($noti->read_at);
                    @endphp

                    <div class="bg-white/95 text-gray-800 rounded-2xl p-4 shadow-soft border border-gray-200">
                        <div class="flex items-start gap-4">
                            <div class="h-11 w-11 rounded-xl grid place-items-center {{ $bg }}">
                                <i class="fas fa-{{ $icono }} {{ $text }} text-sm"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-base font-semibold text-gray-900">
                                        {{ $titulo ?: 'Notificacion' }}
                                    </div>
                                    @if ($isUnread)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-600 text-white">
                                            NUEVA
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold bg-gray-200 text-gray-700">
                                            LEIDA
                                        </span>
                                    @endif
                                </div>

                                <div class="text-sm text-gray-700 mt-1 break-words">
                                    {{ $mensaje }}
                                </div>

                                @if (!empty($data['nombre']))
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $data['nombre'] }}
                                    </div>
                                @endif

                                <div class="text-xs text-gray-500 mt-2">
                                    {{ $noti->created_at?->diffForHumans() }}
                                </div>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                @if ($url)
                                    <a href="{{ $url }}"
                                        class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800">
                                        Ver
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                @endif
                                @if ($isUnread)
                                    <form method="POST" action="{{ route('notificaciones.leer', $noti->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="text-xs font-semibold text-gray-700 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-lg transition">
                                            Marcar como leida
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="bg-white/90 text-gray-700 rounded-2xl p-6 text-center border border-gray-200">
                        No hay notificaciones para mostrar.
                    </div>
                @endforelse
            </div>

            @if ($notificacionesListado instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="mt-6">
                    {{ $notificacionesListado->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
