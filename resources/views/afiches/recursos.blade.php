@extends('layouts.app')

@section('module', 'afiches')

@section('content')
    <div class ="space-y-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">
                            Cargar recursos para afiches
                        </h2>

                        <p class="text-sm text-white/70 mt-1">
                            Aquí puedes subir nuevas plantillas, íconos o fuentes para usarlas en el generador de afiches.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('afiches.assets.upload') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-plus mr-2"></i>Añadir recursos
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-gauge-high mr-2"></i>Dashboard
                        </a>
                    </div>
                </div>
                {{-- Mensajes flash --}}
                @if (session('success') || session('error'))
                    <div class="mb-4">
                        <x-alerts />
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4">
                        <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-2 rounded-lg">
                            <ul class="list-disc pl-5 text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6 mt-4">

                {{-- COL 1: FORMULARIO --}}
                <div class="md:col-span-1">
                    <div class="panel-light p-4 rounded-2xl">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Subir recurso</h2>
                        <form action="{{ route('afiches.assets.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de recurso</label>
                                <select name="tipo"
                                    class="form-input w-full border-2 border-gray-200 rounded-xl px-3 py-2 focus:border-blue-500 focus:ring-0 transition bg-white text-gray-900">
                                    <option value="">-- Selecciona --</option>
                                    <option value="plantilla" {{ old('tipo') === 'plantilla' ? 'selected' : '' }}>Plantilla
                                        de afiche</option>
                                    <option value="iconG" {{ old('tipo') === 'iconG' ? 'selected' : '' }}>Ícono principal
                                        (personaje)</option>
                                    <option value="iconCheck" {{ old('tipo') === 'iconCheck' ? 'selected' : '' }}>Ícono de
                                        check (requisitos)</option>
                                    <option value="iconPhone" {{ old('tipo') === 'iconPhone' ? 'selected' : '' }}>Ícono de
                                        teléfono</option>
                                    <option value="iconEmail" {{ old('tipo') === 'iconEmail' ? 'selected' : '' }}>Ícono de
                                        email</option>
                                    <option value="font" {{ old('tipo') === 'font' ? 'selected' : '' }}>Fuente (TTF/OTF)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Archivo</label>
                                <input type="file" name="archivo"
                                    class="form-input w-full border-2 border-gray-200 rounded-xl px-3 py-2 bg-white text-gray-900 text-sm focus:border-blue-500 focus:ring-0 transition">
                                <p class="text-xs text-gray-500 mt-1">
                                    Imágenes: PNG/JPG. Fuentes: TTF/OTF. Tamaño máximo: 4 MB.
                                </p>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-2 rounded-xl font-semibold flex items-center space-x-2 w-full justify-center transition">
                                    <i class="fas fa-upload"></i> <span>Subir recurso</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- COL 2–3: LISTAS CON TOGGLES --}}
                {{-- COL 2–3: LISTAS --}}
                <div class="md:col-span-2 space-y-6">
                    {{-- Plantillas --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Plantillas actuales</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-plantillas')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- OJO: hidden agregado --}}
                        <div id="sec-plantillas" class="mt-3 hidden">
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($plantillas ?? [] as $tpl)
                                    <div
                                        class="border border-gray-200 rounded-lg p-2 flex flex-col items-center bg-gray-50">
                                        <img src="{{ asset($tpl->path) }}" alt="{{ $tpl->name }}"
                                            class="w-full h-24 object-cover rounded-md mb-2">
                                        <p class="text-xs text-gray-700 text-center mb-2 truncate w-full">
                                            {{ $tpl->name }}
                                        </p>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $tpl->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="plantilla">
                                            <input type="hidden" name="filename" value="{{ $tpl->filename }}">
                                            <button type="submit"
                                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay plantillas cargadas.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Íconos principales --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos principales (iconG)</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconG')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- hidden agregado --}}
                        <div id="sec-iconG" class="mt-3 hidden">
                            <div class="grid grid-cols-3 lg:grid-cols-5 gap-4">
                                @forelse($iconosG ?? [] as $icon)
                                    <div
                                        class="border border-gray-200 rounded-lg p-2 flex flex-col items-center bg-gray-50">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">
                                        <p class="text-[11px] text-gray-700 text-center mb-2 truncate w-full">
                                            {{ $icon->name }}
                                        </p>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconG">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay íconos principales cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Íconos de check --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de check</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconCheck')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- hidden agregado --}}
                        <div id="sec-iconCheck" class="mt-3 hidden">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosCheck ?? [] as $icon)
                                    <div
                                        class="border border-gray-200 rounded-lg p-2 flex flex-col items-center bg-gray-50">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconCheck">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay íconos de check cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Íconos de teléfono --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de teléfono</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconPhone')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- hidden agregado --}}
                        <div id="sec-iconPhone" class="mt-3 hidden">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosPhone ?? [] as $icon)
                                    <div
                                        class="border border-gray-200 rounded-lg p-2 flex flex-col items-center bg-gray-50">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconPhone">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay íconos de teléfono cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Íconos de email --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de email</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconEmail')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- hidden agregado --}}
                        <div id="sec-iconEmail" class="mt-3 hidden">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosEmail ?? [] as $icon)
                                    <div
                                        class="border border-gray-200 rounded-lg p-2 flex flex-col items-center bg-gray-50">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconEmail">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay íconos de email cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Fuentes --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Fuentes cargadas</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-fonts')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        {{-- <-- hidden agregado --}}
                        <div id="sec-fonts" class="mt-3 hidden">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($fonts ?? [] as $font)
                                    <div
                                        class="border border-gray-200 rounded-lg p-3 flex items-center justify-between bg-gray-50">
                                        <span class="text-sm text-gray-700 truncate mr-3">
                                            {{ $font->filename }}
                                        </span>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $font->filename }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="font">
                                            <input type="hidden" name="filename" value="{{ $font->filename }}">
                                            <button type="submit"
                                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded transition">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">
                                        No hay fuentes cargadas.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>

    {{-- SweetAlert + toggle secciones --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmarEliminar(form, nombre) {
            event.preventDefault();

            Swal.fire({
                title: '¿Eliminar recurso?',
                text: nombre ? 'Se eliminará: ' + nombre : 'Se eliminará este archivo.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });

            return false;
        }

        function toggleSection(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.classList.toggle('hidden');
        }
    </script>

    {{-- Estilos adicionales --}}
    <style>
        .panel-light {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(249, 250, 251, 0.95));
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
            backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .form-input:focus {
            outline: none;
        }
    </style>
@endsection
