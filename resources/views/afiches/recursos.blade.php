@extends('layouts.app')

@section('content')
    <div class="min-h-screen gradient-bg py-8 pt-24">

        <a href="{{ route('dashboard') }}"
            class="absolute top-6 left-6 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-lg transition-colors flex items-center space-x-3 px-6 py-3 text-lg z-10 group">
            <i class="fas fa-arrow-left text-2xl group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-bold">Volver al Dashboard</span>
        </a>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800 mb-1">Cargar recursos para afiches</h1>
                <p class="text-gray-600 mb-4">
                    Aquí puedes subir nuevas plantillas, íconos o fuentes para usarlas en el generador de afiches.
                </p>

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

        {{-- GRID: IZQUIERDA FORM / DERECHA LISTAS --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6 mt-4">
                {{-- COL 1: FORMULARIO --}}
                <div class="md:col-span-1">
                    <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                        <h2 class="text-lg font-semibold text-gray-800 mb-3">Subir recurso</h2>

                        <form action="{{ route('afiches.assets.upload') }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Tipo de recurso
                                </label>
                                <select name="tipo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                                    <option value="">-- Selecciona --</option>
                                    <option value="plantilla" {{ old('tipo') === 'plantilla' ? 'selected' : '' }}>
                                        Plantilla de afiche
                                    </option>
                                    <option value="iconG" {{ old('tipo') === 'iconG' ? 'selected' : '' }}>
                                        Ícono principal (personaje)
                                    </option>
                                    <option value="iconCheck" {{ old('tipo') === 'iconCheck' ? 'selected' : '' }}>
                                        Ícono de check (requisitos)
                                    </option>
                                    <option value="iconPhone" {{ old('tipo') === 'iconPhone' ? 'selected' : '' }}>
                                        Ícono de teléfono
                                    </option>
                                    <option value="iconEmail" {{ old('tipo') === 'iconEmail' ? 'selected' : '' }}>
                                        Ícono de email
                                    </option>
                                    <option value="font" {{ old('tipo') === 'font' ? 'selected' : '' }}>
                                        Fuente (TTF/OTF)
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Archivo
                                </label>
                                <input type="file" name="archivo"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white text-sm">
                                <p class="text-xs text-gray-500 mt-1">
                                    Imágenes: PNG/JPG. Fuentes: TTF/OTF. Tamaño máximo: 4 MB.
                                </p>
                            </div>

                            <div class="pt-2">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold flex items-center space-x-2 w-full justify-center">
                                    <i class="fas fa-upload"></i>
                                    <span>Subir recurso</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- COL 2–3: LISTAS CON TOGGLES --}}
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

                        <div id="sec-plantillas" class="mt-3">
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($plantillas ?? [] as $tpl)
                                    <div class="border border-gray-200 rounded-lg p-2 flex flex-col items-center">
                                        <img src="{{ asset($tpl->path) }}" alt="{{ $tpl->name }}"
                                            class="w-full h-24 object-cover rounded-md mb-2">
                                        <p class="text-xs text-center mb-2">{{ $tpl->name }}</p>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $tpl->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="plantilla">
                                            <input type="hidden" name="filename" value="{{ $tpl->filename }}">
                                            <button type="submit"
                                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay plantillas cargadas.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Iconos principales --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos principales (iconG)</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconG')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        <div id="sec-iconG" class="mt-3">
                            <div class="grid grid-cols-3 lg:grid-cols-5 gap-4">
                                @forelse($iconosG ?? [] as $icon)
                                    <div class="border border-gray-200 rounded-lg p-2 flex flex-col items-center">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">
                                        <p class="text-[11px] text-center mb-2 truncate w-full">{{ $icon->name }}</p>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconG">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay íconos principales cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Iconos Check --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de check</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconCheck')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        <div id="sec-iconCheck" class="mt-3">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosCheck ?? [] as $icon)
                                    <div class="border border-gray-200 rounded-lg p-2 flex flex-col items-center">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconCheck">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay íconos de check cargados.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Iconos Phone --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de teléfono</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconPhone')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        <div id="sec-iconPhone" class="mt-3">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosPhone ?? [] as $icon)
                                    <div class="border border-gray-200 rounded-lg p-2 flex flex-col items-center">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconPhone">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay íconos de teléfono cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Iconos Email --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-800">Íconos de email</h2>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-800"
                                onclick="toggleSection('sec-iconEmail')">
                                Mostrar / Ocultar
                            </button>
                        </div>

                        <div id="sec-iconEmail" class="mt-3">
                            <div class="grid grid-cols-3 lg:grid-cols-6 gap-4">
                                @forelse($iconosEmail ?? [] as $icon)
                                    <div class="border border-gray-200 rounded-lg p-2 flex flex-col items-center">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mb-2">

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $icon->name }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="iconEmail">
                                            <input type="hidden" name="filename" value="{{ $icon->filename }}">
                                            <button type="submit"
                                                class="text-[11px] bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay íconos de email cargados.</p>
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

                        <div id="sec-fonts" class="mt-3">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @forelse($fonts ?? [] as $font)
                                    <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                                        <span class="text-sm text-gray-700">{{ $font->filename }}</span>

                                        <form action="{{ route('afiches.assets.delete') }}" method="POST"
                                            onsubmit="return confirmarEliminar(this, '{{ $font->filename }}');">
                                            @csrf
                                            <input type="hidden" name="tipo" value="font">
                                            <input type="hidden" name="filename" value="{{ $font->filename }}">
                                            <button type="submit"
                                                class="text-xs bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 col-span-full">No hay fuentes cargadas.</p>
                                @endforelse
                            </div>
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
@endsection
