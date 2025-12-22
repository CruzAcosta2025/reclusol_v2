@extends('layouts.app')

@section('module', 'postulantes')

@section('content')

    <style>
        /* Panel claro (no blanco puro) para que combine con el tema oscuro */
        .panel-light {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(249, 250, 251, 0.86));
            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 1.25rem;
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.28);
            backdrop-filter: blur(10px);
        }

        /* Inputs dentro del panel: texto visible sobre fondo claro */
        .panel-light input[type="text"],
        .panel-light input[type="number"],
        .panel-light input[type="date"],
        .panel-light select,
        .panel-light textarea {
            background-color: #ffffff;
            color: #111827 !important;
            /* fuerza texto oscuro */
            border-color: #e5e7eb;
        }

        .panel-light input::placeholder,
        .panel-light textarea::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        /* Mejoras sutiles de tabla */
        .table-sticky thead th {
            position: sticky;
            top: 0;
            z-index: 1;
        }
    </style>

    <div class="space-y-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Encabezado --}}
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">Listado de Postulantes</h1>
                        <p class="text-sm text-white/70 mt-1">Ver y filtrar postulantes segÃºn diferentes criterios</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('postulantes.formInterno') }}"
                            class="px-4 py-2 rounded-xl font-extrabold text-sm text-white"
                            style="background:linear-gradient(135deg,#3b82f6,#4f46e5);">
                            <i class="fas fa-user-plus mr-2"></i>Crear Postulante
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition text-white">
                            <i class="fas fa-gauge-high mr-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtro --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 panel-light p-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                    <input type="text" name="dni" value="{{ request('dni') }}" placeholder="Ingrese DNI"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                    <input type="text" name="nombre" value="{{ request('nombre') }}" placeholder="Nombre o Apellido"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                </div>

                <!-- Tipo de Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                    <select name="tipo_cargo" id="tipo_cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($tipoCargos as $codigo => $desc)
                            <option value="{{ $codigo }}"
                                {{ (string) request('tipo_cargo') === (string) $codigo ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{--

<div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo Solicitado</label>
                    <select name="cargo" id="cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($cargos as $codigo => $cargo)
                            <option value="{{ $codigo }}" {{ request('cargo') == $codigo ? 'selected' : '' }}>
                                {{ $cargo->DESC_CARGO }}
                            </option>
                        @endforeach
                    </select>
                </div>
 --}}


                {{-- Departamento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select name="departamento" id="departamento"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($departamentos as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('departamento') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Provincia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                    <select name="provincia" id="provincia"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500">
                        <option value="">Todas</option>
                        @foreach ($provincias as $codigo => $provincia)
                            <option value="{{ $codigo }}" {{ request('provincia') == $codigo ? 'selected' : '' }}>
                                {{ $provincia->PROVI_DESCRIPCION }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Distrito --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                    <select name="distrito" id="distrito"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500">
                        <option value="">Todos</option>
                        @foreach ($distritos as $codigo => $distrito)
                            <option value="{{ $codigo }}" {{ request('distrito') == $codigo ? 'selected' : '' }}>
                                {{ $distrito->DIST_DESCRIPCION }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-4 flex flex-wrap gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                    <button type="button" onclick="limpiarFiltros()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-times"></i> Limpiar filtros
                    </button>
                </div>
            </form>
        </div>

        {{-- Cards de resumen --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#ecfdf5;">
                        <i class="fas fa-check-circle text-green-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Postulantes Aptos</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['aptos'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fef2f2;">
                        <i class="fas fa-times-circle text-red-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Postulantes No Aptos</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['no_aptos'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#eff6ff;">
                        <i class="fa-solid fa-users text-blue-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Total Postulantes</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['total'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fffbeb;">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Pendientes</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['pendientes'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 mt-6">
        {{-- Encabezado --}}
        <div
            class="flex items-center justify-between bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 rounded-t-xl shadow-md">
            <h2 class="flex items-center text-lg font-semibold">
                <i class="fas fa-search mr-2"></i>
                Listado de Postulantes
            </h2>
            <span class="text-sm opacity-80">
                {{-- {{ $postulantes->total() }} resultados --}}
            </span>
        </div>

        <div class="overflow-x-auto bg-white rounded-b-xl shadow-md">
            <table class="w-full table-sticky">
                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Puesto al que postula
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Lugar de Nacimiento
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Nombres y Apellidos
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Edad</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">DNI</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Nacionalidad</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Experiencia</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Curso SUCAMEC</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">CarnÃ© SUCAMEC</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Grado de InstrucciÃ³n
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Celular</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Licencia de Arma L4
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Licencia de Conducir A1
                        </th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">CV</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">CUL</th>
                        <th class="px-6 py-4 text-sm font-bold text-gray-800 uppercase text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($postulantes as $postulante)
                        <tr id="row-{{ $postulante->id }}" data-dni="{{ strtolower($postulante->dni) }}"
                            data-nombre="{{ strtolower($postulante->nombres . ' ' . $postulante->apellidos) }}"
                            class="hover:bg-blue-50 transition
                            @if ($postulante->decision === 'apto') bg-green-100
                            @elseif ($postulante->decision === 'no_apto') bg-red-100 @endif">
                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->puesto_postula }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700 text-center">{{ $postulante->distrito_nombre }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->apellidos }}
                                    {{ $postulante->nombres }}
                                </p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ ucfirst($postulante->edad) }} aÃ±os</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->dni }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->nacionalidad }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->experiencia_rubro }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->sucamec ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->carne_sucamec ?: '-' }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->grado_instruccion }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->celular }}</p>
                            </td>

                            <td class="px-6 py-4 text-gray-700 text-center">
                                {{ $postulante->licencia_arma ?: '-' }}
                            </td>
                            <td class="px-6 py-4 text-gray-700 text-center">
                                {{ $postulante->licencia_conducir ?: '-' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if ($postulante->cv)
                                    <a href="{{ route('postulantes.ver-envuelto', ['postulante' => $postulante->id, 'tipo' => 'cv']) }}"
                                        target="_blank"
                                        class="flex items-center justify-center w-24 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Ver CV">
                                        <i class="fa-solid fa-file-pdf mr-2"></i> Ver CV
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if ($postulante->cul)
                                    <a href="{{ route('postulantes.ver-envuelto', ['postulante' => $postulante->id, 'tipo' => 'cul']) }}"
                                        target="_blank"
                                        class="flex items-center justify-center w-24 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Ver CUL">
                                        <i class="fa-solid fa-file-pdf mr-2"></i> Ver CUL
                                    </a>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 align-middle">
                                <div class="flex items-center justify-center gap-2">
                                    <button data-id="{{ $postulante->id }}"
                                        data-nombre="{{ $postulante->nombres }} {{ $postulante->apellidos }}"
                                        class="btn-validar inline-flex items-center justify-center h-8 px-3 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Validar documentos">
                                        <i class="fa-solid fa-clipboard-check mr-2"></i>
                                    </button>

                                    <button data-id="{{ $postulante->id }}"
                                        class="btn-edit inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button onclick="eliminarPostulante({{ $postulante->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-gray-500">No se encontraron resultados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Modales (eliminaciÃ³n, ediciÃ³n, validaciÃ³n) --}}
        {{-- Modal de EliminaciÃ³n --}}
        <div id="delete-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md"
            role="dialog" aria-modal="true" aria-labelledby="delete-title" aria-describedby="delete-desc">
            <!-- Panel -->
            <div class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl border-2 border-red-100 p-8
       opacity-0 translate-y-2 transition-all duration-300 ease-out
       data-[open=true]:opacity-100 data-[open=true]:translate-y-0"
                id="delete-panel" data-open="true">
                <!-- Encabezado -->
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-red-100 to-red-50 border-2 border-red-200">
                            <i class="fa-solid fa-triangle-exclamation text-red-600 text-2xl"></i>
                        </div>
                    </div>
                    <h3 id="delete-title" class="text-2xl font-bold text-gray-900 mb-2">¿Eliminar postulante?</h3>
                    <p id="delete-desc" class="text-base text-gray-700 leading-relaxed">Esta accion no se puede deshacer. Confirma que deseas eliminar este postulante.</p>
                </div>

                <!-- LÃ­nea separadora -->
                <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent my-6"></div>

                <!-- Botones -->
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="closeDeleteModal()"
                        class="px-6 py-3 rounded-lg font-semibold text-gray-800 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 min-w-[120px]">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>

                    <button type="button" onclick="confirmDelete()"
                        class="px-6 py-3 rounded-lg font-semibold text-white bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 min-w-[120px]">
                        <i class="fas fa-trash-alt mr-2"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal de EdiciÃ³n --}}
        <div id="edit-modal" class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm">
            <div id="edit-panel"
                class="panel-light w-full max-w-3xl mx-4 rounded-2xl shadow-2xl border border-gray-100 max-h-[90vh] overflow-y-auto opacity-0 scale-95 transition duration-200 ease-out data-[open=true]:opacity-100 data-[open=true]:scale-100">
                <div id="edit-modal-content"><!-- aquÃ­ se inyecta el formulario --></div>
            </div>
        </div>

        {{-- Modal de ValidaciÃ³n --}}
        <div id="validar-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-md" role="dialog"
            aria-modal="true" aria-labelledby="validar-title" aria-describedby="validar-desc">
            <div
                class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl border-2 border-emerald-100 p-8
           opacity-0 translate-y-2 transition-all duration-300 ease-out
           data-[open=true]:opacity-100 data-[open=true]:translate-y-0"
                id="validar-panel" data-open="true">

                <!-- Encabezado -->
                <div class="text-center mb-6">
                    <div class="flex justify-center mb-4">
                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-gradient-to-br from-emerald-100 to-emerald-50 border-2 border-emerald-200">
                            <i class="fa-solid fa-clipboard-check text-emerald-600 text-2xl"></i>
                        </div>
                    </div>
                    <h3 id="validar-title" class="text-2xl font-bold text-gray-900 mb-2">Validar Postulante</h3>
                    <p id="validar-desc" class="text-base text-gray-700 leading-relaxed"><span id="val-nombre" class="font-semibold text-emerald-700"></span></p>
                </div>

                <!-- LÃ­nea separadora -->
                <div class="h-px bg-gradient-to-r from-transparent via-gray-200 to-transparent my-6"></div>

                <!-- Formulario -->
                <form id="form-validar" method="POST" class="space-y-5" autocomplete="off">
                    @csrf

                    <!-- Opciones de radio -->
                    <fieldset class="space-y-3">
                        <legend class="text-sm font-semibold text-gray-900 mb-3">Resultado de validación</legend>
                        
                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 hover:border-emerald-300 hover:bg-emerald-50 cursor-pointer transition-all">
                            <input type="radio" name="decision" value="apto" class="w-5 h-5 accent-emerald-600" checked>
                            <span class="flex-1">
                                <span class="text-gray-900 font-semibold">Apto</span>
                                <span class="text-xs text-gray-500 block">Postulante cumple los requisitos</span>
                            </span>
                            <i class="fas fa-check-circle text-emerald-600 text-lg"></i>
                        </label>
                        
                        <label class="flex items-center gap-3 p-4 rounded-xl border-2 border-gray-200 hover:border-red-300 hover:bg-red-50 cursor-pointer transition-all">
                            <input type="radio" name="decision" value="no_apto" class="w-5 h-5 accent-red-600">
                            <span class="flex-1">
                                <span class="text-gray-900 font-semibold">No apto</span>
                                <span class="text-xs text-gray-500 block">Postulante no cumple requisitos</span>
                            </span>
                            <i class="fas fa-times-circle text-red-600 text-lg"></i>
                        </label>
                    </fieldset>

                    <!-- Comentario -->
                    <div>
                        <label for="comentario-no-apto" class="block text-sm font-semibold text-gray-900 mb-2">
                            Comentario o Notas
                            <span class="text-gray-500 font-normal">(obligatorio si es No apto)</span>
                        </label>
                        <textarea name="comentario" id="comentario-no-apto" rows="4" maxlength="300"
                            class="w-full bg-white border-2 border-gray-200 rounded-xl p-3 text-gray-900 placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition-all resize-none"
                            placeholder="Motivo o notas (máx. 300 caracteres)"></textarea>
                        <div class="text-xs text-gray-500 mt-1">
                            <span id="char-count">0</span>/300 caracteres
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-center gap-3 pt-2 mt-6">
                        <button type="button" onclick="cerrarValidar()"
                            class="px-6 py-3 rounded-lg font-semibold text-gray-800 bg-gray-100 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition-all duration-200 min-w-[120px]">
                            <i class="fas fa-times mr-2"></i>Cancelar
                        </button>
                        <button type="submit"
                            class="px-6 py-3 rounded-lg font-semibold text-white bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 min-w-[120px]">
                            <i class="fas fa-save mr-2"></i>Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Scripts --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        let deletePostulanteId = null;
        let deleteTriggerEl = null; // para devolver el foco
        const modal = document.getElementById('delete-modal');
        const panel = document.getElementById('delete-panel');
        const btnCancelar = modal?.querySelector('button[onclick="closeDeleteModal()"]');
        const btnEliminar = modal?.querySelector('button[onclick="confirmDelete()"]');

        const editModal = document.getElementById('edit-modal');
        const editPanel = document.getElementById('edit-panel'); // <- debe existir en el HTML
        const editContent = document.getElementById('edit-modal-content');
        let editTriggerEl = null;

        let validarId = null;
        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));
        const dniInput = document.querySelector('input[name="dni"]');
        const nombreInput = document.querySelector('input[name="nombre"]');

        function filtrarTabla() {
            const dniVal = dniInput.value.toLowerCase();
            const nombreVal = nombreInput.value.toLowerCase();

            document.querySelectorAll('tbody tr[data-dni]').forEach(row => {
                const rowDni = row.dataset.dni;
                const rowNombre = row.dataset.nombre;
                const match = rowDni.includes(dniVal) && rowNombre.includes(nombreVal);
                row.style.display = match ? '' : 'none';
            });
        }

        if (dniInput && nombreInput) {
            dniInput.addEventListener('input', filtrarTabla);
            nombreInput.addEventListener('input', filtrarTabla);
        }

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        function openDeleteModal(id, triggerEl = null) {
            deletePostulanteId = id;
            deleteTriggerEl = triggerEl || document.activeElement;

            // Mostrar overlay
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Bloquear scroll del fondo
            document.body.style.overflow = 'hidden';

            // Forzar reflow para que la transiciÃ³n se aplique
            void panel.offsetWidth;
            panel.setAttribute('data-open', 'true');

            // Foco al botÃ³n mÃ¡s seguro (Cancelar)
            setTimeout(() => {
                btnCancelar?.focus();
            }, 0);

            // Listeners de UX
            document.addEventListener('keydown', onEscClose);
            modal.addEventListener('mousedown', onBackdropClick);
        }

        // Mantengo tu firma para compatibilidad con el onclick anterior
        function eliminarPostulante(id) {
            openDeleteModal(id);
        }

        function closeDeleteModal() {
            deletePostulanteId = null;

            // AnimaciÃ³n de salida
            panel.setAttribute('data-open', 'false');

            // Habilitar de nuevo botones y texto original si estaba cargando
            restoreButtons();

            // Quitar listeners
            document.removeEventListener('keydown', onEscClose);
            modal.removeEventListener('mousedown', onBackdropClick);

            // Devolver scroll
            document.body.style.overflow = '';

            // Ocultar despuÃ©s de la duraciÃ³n de la transiciÃ³n (200ms)
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                // Devolver foco al disparador
                if (deleteTriggerEl && typeof deleteTriggerEl.focus === 'function') {
                    deleteTriggerEl.focus();
                }
                deleteTriggerEl = null;
            }, 200);
        }

        function onEscClose(e) {
            if (e.key === 'Escape') closeDeleteModal();
        }

        function onBackdropClick(e) {
            // Cierra si clickea fuera del panel (overlay)
            if (!panel.contains(e.target)) {
                closeDeleteModal();
            }
        }

        async function confirmDelete() {
            if (!deletePostulanteId) {
                closeDeleteModal();
                return;
            }

            const prevEliminarText = btnEliminar?.textContent;
            btnEliminar.disabled = true;
            btnCancelar.disabled = true;
            btnEliminar.textContent = 'Eliminando...';
            let deleted = false;

            try {
                const res = await fetch(`/postulantes/${deletePostulanteId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                let data = null;
                const contentType = res.headers.get('Content-Type') || '';
                if (contentType.includes('application/json')) {
                    data = await res.json().catch(() => null);
                }

                if (res.ok && (!data || data.success !== false)) {
                    deleted = true;
                    closeDeleteModal();
                    Swal.fire({
                        icon: 'success',
                        title: 'Postulante eliminado',
                        text: 'Se elimino el postulante correctamente.',
                        timer: 1600,
                        timerProgressBar: true,
                        showConfirmButton: false,
                    }).then(() => window.location.reload());
                    return;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar',
                    text: (data && data.message) ? data.message : 'No se pudo eliminar el postulante.',
                });
            } catch (err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al eliminar',
                    text: 'No se pudo eliminar el postulante.',
                });
            } finally {
                if (!document.hidden && !deleted) {
                    if (prevEliminarText) btnEliminar.textContent = prevEliminarText;
                    btnEliminar.disabled = false;
                    btnCancelar.disabled = false;
                    closeDeleteModal();
                }
            }
        }

        function restoreButtons() {
            if (!btnEliminar || !btnCancelar) return;
            btnEliminar.disabled = false;
            btnCancelar.disabled = false;
            if (btnEliminar.textContent !== 'Eliminar') {
                btnEliminar.textContent = 'Eliminar';
            }
        }

        // Filtrar cargos dinÃ¡micamente en el filtro principal
        document.getElementById('tipo_cargo').addEventListener('change', function() {
            const tipoCargoId = this.value;
            const cargoSelect = document.getElementById('cargo');

            cargoSelect.innerHTML = '<option value="">Selecciona un cargo</option>';

            const cargosFiltrados = cargos.filter(p => p.TIPO_CARG === tipoCargoId);

            cargosFiltrados.forEach(p => {
                const option = document.createElement('option');
                option.value = p.CODI_CARG;
                option.textContent = p.DESC_CARGO;
                cargoSelect.appendChild(option);
            });

            if (cargosFiltrados.length === 0) {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay cargos para este tipo";
                cargoSelect.appendChild(option);
            }
        });

        // Filtrar provincias al cambiar departamento
        document.getElementById('departamento').addEventListener('change', function() {
            const depaId = this.value.padStart(2, '0');
            const provinciaSelect = document.getElementById('provincia');

            provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';

            if (depaId) {
                const provinciasFiltradas = provincias.filter(p => p.DEPA_CODIGO === depaId);

                provinciasFiltradas.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.PROVI_CODIGO;
                    option.textContent = p.PROVI_DESCRIPCION;
                    provinciaSelect.appendChild(option);
                });
            }
        });

        // Filtrar distritos al cambiar provincia
        document.getElementById('provincia').addEventListener('change', function() {
            const provId = this.value.padStart(2, '0');
            const distritoSelect = document.getElementById('distrito');

            distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';

            if (provId) {
                const distritosFiltrados = distritos.filter(p => p.PROVI_CODIGO === provId);

                distritosFiltrados.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.DIST_CODIGO;
                    option.textContent = p.DIST_DESCRIPCION;
                    distritoSelect.appendChild(option);
                });
            }
        });

        async function openEditModal(url, triggerEl = null) {
            editTriggerEl = triggerEl || document.activeElement;

            // Skeleton
            editContent.innerHTML = `
    <div class="p-6 space-y-3">
      <div class="h-6 w-40 bg-gray-200 rounded animate-pulse"></div>
      <div class="h-4 w-3/4 bg-gray-200 rounded animate-pulse"></div>
      <div class="h-32 w-full bg-gray-200 rounded animate-pulse"></div>
    </div>`;

            // Mostrar modal
            editModal.classList.remove('hidden');
            editModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            if (editPanel) {
                void editPanel.offsetWidth;
                editPanel.setAttribute('data-open', 'true');
            }

            try {
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const html = await res.text();
                editContent.innerHTML = html;

                // === IMPORTANTE: inicializar selects dependientes del modal ===
                initEditForm(editContent);

                const firstEl = editContent.querySelector('[autofocus], input, select, textarea, button');
                if (firstEl) firstEl.focus();

                editModal.addEventListener('mousedown', onEditBackdropClick);
                document.addEventListener('keydown', onEditEsc);
            } catch (e) {
                editContent.innerHTML = `<div class="p-6 text-sm text-red-600">No se pudo cargar el formulario.</div>`;
            }
        }


        function closeEditModal() {
            // AnimaciÃ³n de salida
            if (editPanel) editPanel.setAttribute('data-open', 'false');

            // Quitar listeners y devolver scroll
            document.removeEventListener('keydown', onEditEsc);
            editModal.removeEventListener('mousedown', onEditBackdropClick);
            document.body.style.overflow = '';

            // Ocultar tras la duraciÃ³n de la animaciÃ³n (200ms)
            setTimeout(() => {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
                // Devolver foco al disparador
                if (editTriggerEl && typeof editTriggerEl.focus === 'function') editTriggerEl.focus();
                editTriggerEl = null;
            }, 200);
        }

        function onEditEsc(e) {
            if (e.key === 'Escape') closeEditModal();
        }

        function onEditBackdropClick(e) {
            if (!editPanel?.contains(e.target)) closeEditModal();
        }

        // Conectar los botones "Editar" a la nueva funciÃ³n
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                openEditModal(`/postulantes/${id}/edit`, btn);
            });
        });

        // Capturar submit y enviar por AJAX usando POST + override
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'form-edit') {
                e.preventDefault();
                const form = e.target;
                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PUT',
                            'Accept': 'application/json'
                        },
                        body: new FormData(form)
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => Promise.reject(err));
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Â¡Ã‰xito!',
                                text: 'Â¡Postulante actualizado correctamente!',
                                confirmButtonColor: '#3085d6',
                                timer: 1800,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                            // Cerrar el modal antes de recargar (opcional)
                            closeEditModal();
                            // Esperar al SweetAlert antes de recargar
                            setTimeout(() => window.location.reload(), 1800);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al actualizar.',
                                confirmButtonColor: '#d33'
                            });
                        }
                    })
                    .catch(err => {
                        let mensaje = 'OcurriÃ³ un error inesperado.';
                        if (err.errors) {
                            mensaje = Object.values(err.errors).flat().join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validaciÃ³n',
                            text: mensaje,
                            confirmButtonColor: '#d33'
                        });
                        console.error('Errores de validaciÃ³n:', err.errors || err);
                    });
            }
        });

        function exportarResultados() {
            const form = document.getElementById('filter-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.open(`/postulantes/exportar?${params}`, '_blank');
        }

        document.querySelectorAll('.btn-validar').forEach(b => {
            b.addEventListener('click', () => {
                validarId = b.dataset.id;
                document.getElementById('val-nombre').textContent = b.dataset.nombre;
                const form = document.getElementById('form-validar');
                form.action = `{{ url('/postulantes') }}/${validarId}/validar`;

                // reset form
                form.reset();
                document.querySelector('input[name="decision"][value="apto"]').checked = true;
                document.getElementById('comentario-no-apto').removeAttribute('required');
                document.getElementById('char-count').textContent = '0';

                // abrir modal
                const m = document.getElementById('validar-modal');
                const panel = document.getElementById('validar-panel');
                m.classList.remove('hidden');
                m.classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                void panel.offsetWidth;
                panel.setAttribute('data-open', 'true');
                
                setTimeout(() => {
                    document.querySelector('input[name="decision"]')?.focus();
                }, 0);
                
                m.addEventListener('mousedown', onValidarBackdropClick);
                document.addEventListener('keydown', onValidarEsc);
            });
        });

        function cerrarValidar() {
            const m = document.getElementById('validar-modal');
            const panel = document.getElementById('validar-panel');
            
            panel.setAttribute('data-open', 'false');
            
            document.removeEventListener('keydown', onValidarEsc);
            m.removeEventListener('mousedown', onValidarBackdropClick);
            document.body.style.overflow = '';
            
            setTimeout(() => {
                m.classList.add('hidden');
                m.classList.remove('flex');
            }, 300);
        }

        function onValidarEsc(e) {
            if (e.key === 'Escape') cerrarValidar();
        }

        function onValidarBackdropClick(e) {
            const m = document.getElementById('validar-modal');
            const panel = document.getElementById('validar-panel');
            if (!panel?.contains(e.target)) cerrarValidar();
        }

        // Requerir comentario si "no_apto"
        document.addEventListener('change', e => {
            if (e.target.name === 'decision') {
                const req = (e.target.value === 'no_apto');
                const txt = document.getElementById('comentario-no-apto');
                if (req) txt.setAttribute('required', 'required');
                else txt.removeAttribute('required');
            }
        });

        // Contador de caracteres
        const comentarioTextarea = document.getElementById('comentario-no-apto');
        if (comentarioTextarea) {
            comentarioTextarea.addEventListener('input', function() {
                document.getElementById('char-count').textContent = this.value.length;
            });
        }


        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            const maxMB = parseInt(input.dataset.max || "5", 10);
            const maxBytes = maxMB * 1024 * 1024;
            const preview = document.getElementById(previewId);
            const fileName = preview?.querySelector(".file-name");

            // Reiniciar estado
            if (preview) preview.classList.add("hidden");
            input.classList.remove("border-red-500");
            if (fileName) fileName.textContent = "";

            if (!file) return; // usuario cancelÃ³

            if (file.size > maxBytes) {
                Swal.fire({
                    icon: "error",
                    title: "Archivo demasiado grande",
                    text: `El archivo supera el lÃ­mite de ${maxMB} MB. Por favor elige otro.`,
                    width: 500,
                    padding: '2rem',
                    confirmButtonColor: "#d33",
                }).then(() => {
                    input.value = "";
                    input.classList.add("border-red-500");
                });
                return;
            }

            if (fileName) fileName.textContent = file.name;
            if (preview) preview.classList.remove("hidden");
        }

        function initEditForm(root) {
            // Elementos del modal
            const tipoSel = root.querySelector('#tipo_cargo_edit');
            const cargoSel = root.querySelector('#cargo_edit');
            const depaSel = root.querySelector('#departamento_edit');
            const provSel = root.querySelector('#provincia_edit');
            const distSel = root.querySelector('#distrito_edit');
            if (!tipoSel || !cargoSel || !depaSel || !provSel || !distSel) return;

            // Helpers
            const pad = (v, l) =>
                (v == null || v === '') ? '' : String(v).replace(/\D+/g, '').padStart(l, '0');

            const setOptions = (select, list, placeholder = 'Seleccionaâ€¦') => {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                list.forEach(it => {
                    const opt = document.createElement('option');
                    opt.value = String(it.value ?? '');
                    opt.textContent = String(it.label ?? '');
                    select.appendChild(opt);
                });
            };

            // âœ… CLAVE: cargo (CODI_CARG) siempre a 4 dÃ­gitos, igual que data-value del blade
            const fillCargos = (tipo, preselect = null) => {
                const t = pad(tipo, 2);

                const list = cargos
                    .filter(c => pad(c.TIPO_CARG, 2) === t)
                    .map(c => ({
                        value: pad(c.CODI_CARG, 4),
                        label: c.DESC_CARGO
                    }));

                setOptions(cargoSel, list, 'Selecciona el cargo');

                // preselect: primero data-value, sino el value actual
                const v = pad(preselect, 4);
                if (v) cargoSel.value = v;
            };

            const fillProvs = (depa, preselect = null) => {
                const d = pad(depa, 2);

                const list = provincias
                    .filter(p => pad(p.DEPA_CODIGO, 2) === d)
                    .map(p => ({
                        value: pad(p.PROVI_CODIGO, 4),
                        label: p.PROVI_DESCRIPCION
                    }));

                setOptions(provSel, list, 'Seleccionaâ€¦');

                const v = pad(preselect, 4);
                if (v) provSel.value = v;
            };

            const fillDists = (prov, preselect = null) => {
                const p = pad(prov, 4);

                const list = distritos
                    .filter(d => pad(d.PROVI_CODIGO, 4) === p)
                    .map(d => ({
                        value: pad(d.DIST_CODIGO, 6),
                        label: d.DIST_DESCRIPCION
                    }));

                setOptions(distSel, list, 'Seleccionaâ€¦');

                const v = pad(preselect, 6);
                if (v) distSel.value = v;
            };

            // Estado inicial usando data-value del parcial (o value actual como fallback)
            fillCargos(tipoSel.value, cargoSel.dataset.value || cargoSel.value || '');
            fillProvs(depaSel.value, provSel.dataset.value || provSel.value || '');
            fillDists(provSel.value, distSel.dataset.value || distSel.value || '');

            // Listeners en el modal
            tipoSel.addEventListener('change', () => {
                fillCargos(tipoSel.value, ''); // recarga cargos y limpia selecciÃ³n
            });

            depaSel.addEventListener('change', () => {
                fillProvs(depaSel.value, ''); // recarga provincias
                setOptions(distSel, [], 'Seleccionaâ€¦'); // limpia distritos
            });

            provSel.addEventListener('change', () => {
                fillDists(provSel.value, ''); // recarga distritos
            });
        }
    </script>
@endsection







