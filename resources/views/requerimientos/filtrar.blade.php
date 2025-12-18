@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        <x-block>
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-M2">
                    Listado de Requerimientos
                </h1>
                <p class="text-M3 text-sm">
                    Gestiona y supervisa todos los requerimientos laborales de manera eficiente
                </p>
            </div>
            <div class="flex flex-row items-center gap-2">
                {{-- <a href="{{ route('requerimientos.requerimiento') }}"> --}}
                <x-primary-button-create x-on:click="$dispatch('open-modal', 'crearRequerimiento')">
                    Crear Nuevo Requerimiento
                </x-primary-button-create>
                {{-- </a> --}}
            </div>
        </x-block>
        <x-modal name="crearRequerimiento" :show="false" maxWidth="2xl">
            <x-slot name="title">
                <h2 class="text-lg text-M2 font-bold">Nuevo Requerimiento</h2>
                <p class="text-sm text-M3">Completa los datos paso a paso para crear un nuevo requerimiento.</p>
            </x-slot>
            <form id="requerimiento-wizard-form" method="POST" action="{{ route('requerimientos.store') }}"
                class="space-y-6">
                @csrf
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center text-center w-full">
                            <div id="step-indicator-0"
                                class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-M1 text-M1 bg-M6 font-semibold">
                                1</div>
                            <p id="step-label-0" class="mt-2 text-sm text-M1 font-semibold">Datos de la solicitud</p>
                        </div>
                        <div class="h-px flex-1 bg-neutral hidden md:block"></div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center text-center w-full">
                            <div id="step-indicator-1"
                                class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-neutral text-M3 bg-white font-semibold">
                                2</div>
                            <p id="step-label-1" class="mt-2 text-sm text-M3">Detalles del perfil</p>
                        </div>
                        <div class="h-px flex-1 bg-neutral hidden md:block"></div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center text-center w-full">
                            <div id="step-indicator-2"
                                class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-neutral text-M3 bg-white font-semibold">
                                3</div>
                            <p id="step-label-2" class="mt-2 text-sm text-M3">Remuneración y Beneficios</p>
                        </div>
                        <div class="h-px flex-1 bg-neutral hidden md:block"></div>
                    </div>
                    <div class="flex-1 flex items-center">
                        <div class="flex flex-col items-center text-center w-full">
                            <div id="step-indicator-3"
                                class="w-10 h-10 flex items-center justify-center rounded-full border-2 border-neutral text-M3 bg-white font-semibold">
                                4</div>
                            <p id="step-label-3" class="mt-2 text-sm text-M3">Estado</p>
                        </div>
                    </div>
                </div>

                <!-- Contenido de Pasos -->
                <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-6 min-h-[400px]">
                    <!-- PASO 1: DATOS DE SOLICITUD -->
                    <div id="step-content-0" class="step-content">
                        @include('requerimientos.partials.form-datos-solicitud', [
                            'sucursales' => $sucursales,
                            'clientes' => $clientes,
                            'tipoPersonal' => $tipoPersonal,
                        ])
                    </div>

                    <div id="step-content-1" class="step-content hidden">
                        @include('requerimientos.partials.form-detalles-perfil')
                    </div>

                    <div id="step-content-2" class="step-content hidden">
                        @include('requerimientos.partials.form-remuneracion-beneficios')
                    </div>

                    <div id="step-content-3" class="step-content hidden">
                        @include('requerimientos.partials.form-estado', ['estados' => $estados])
                    </div>
                </div>
            </form>

            <x-slot name="footer">
                <x-cancel-button type="button" onclick="closeRequerimientoWizard()">
                    Cancelar
                </x-cancel-button>
                <x-cancel-button type="button" id="wizard-prev-btn" onclick="wizardPrev()">
                    Anterior
                </x-cancel-button>
                <x-confirm-button type="button" id="wizard-next-btn" onclick="wizardNext()">
                    Siguiente
                </x-confirm-button>
                <x-confirm-button type="button" id="wizard-submit-btn"
                    onclick="document.getElementById('requerimiento-wizard-form').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }))">
                    Crear Requerimiento
                </x-confirm-button>
            </x-slot>
        </x-modal>

        <!-- Stats Section -->
        <div class="w-full">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-yellow-100 border border-yellow-200 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-hourglass-half text-yellow-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">En Proceso</h3>
                    <p class="text-2xl font-bold">{{ $stats['en_validacion'] ?? 0 }} </p>
                </div>

                <div class="bg-green-100 border border-green-200 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-check-circle text-green-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cubiertos</h3>
                    <p class="text-2xl font-bold">{{ $stats['aprobado'] ?? 0 }}</p>
                </div>

                <div class="bg-blue-100 border border-blue-200 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-times-circle text-blue-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cancelados</h3>
                    <p class="text-2xl font-bold">{{ $stats['cancelado'] ?? 0 }}</p>
                </div>

                <div class="bg-red-100 border border-red-200 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-clock text-red-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Vencidos</h3>
                    <p class="text-2xl font-bold">{{ $stats['cerrado'] ?? 0 }} </p>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="w-full">
            <form id="filter-form" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-M6 rounded-lg border border-neutral shadow-sm p-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <x-select name="sucursal" id="sucursal">
                        <option value="">Todos</option>
                        @foreach ($sucursales as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('sucursal') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <x-select name="cliente" id="cliente">
                        <option value="">Todos</option>
                        @foreach ($clientes as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('sucursal') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <!-- Tipo de Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                    <x-select name="tipo_cargo" id="tipo_cargo">
                        <option value="">Todos</option>
                        @foreach ($tipoCargos as $codigo => $desc)
                            <option value="{{ $codigo }}"
                                {{ (string) request('tipo_cargo') === (string) $codigo ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo Solicitado</label>
                    <x-select name="cargo" id="cargo">
                        <option value="">Todos</option>
                        @foreach ($cargos as $codigo => $cargo)
                            <option value="{{ $codigo }}" {{ request('cargo') == $codigo ? 'selected' : '' }}>
                                {{ $cargo->DESC_CARGO }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Departamento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <x-select name="departamento" id="departamento">
                        <option value="">Todos</option>
                        @foreach ($departamentos as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('departamento') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Provincia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                    <x-select name="provincia" id="provincia">
                        <option value="">Todas</option>
                        @foreach ($provincias as $codigo => $provincia)
                            <option value="{{ $codigo }}" {{ request('provincia') == $codigo ? 'selected' : '' }}>
                                {{ $provincia->PROVI_DESCRIPCION }}
                            </option>
                        @endforeach
                    </x-select>
                </div>

                {{-- Distrito --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                    <x-select name="distrito" id="distrito">
                        <option value="">Todos</option>
                        @foreach ($distritos as $codigo => $distrito)
                            <option value="{{ $codigo }}" {{ request('distrito') == $codigo ? 'selected' : '' }}>
                                {{ $distrito->DIST_DESCRIPCION }}
                            </option>
                        @endforeach
                    </x-select>
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

        {{-- Tabla --}}
        <div class="w-full">
            <div class="bg-white rounded-2xl shadow-sm border">
                @php
                    // Define table columns for the component
                    $columns = [
                        [
                            'key' => 'tipo_personal_nombre',
                            'label' => 'Tipo de Personal',
                            'sortable' => true,
                            'align' => 'text-center',
                        ],
                        [
                            'key' => 'cargo_nombre',
                            'label' => 'Cargo Solicitado',
                            'sortable' => true,
                            'align' => 'text-center',
                        ],
                        [
                            'key' => 'sucursal_nombre',
                            'label' => 'Sucursal',
                            'sortable' => true,
                            'align' => 'text-center',
                        ],
                        ['key' => 'cliente_nombre', 'label' => 'Cliente', 'sortable' => true, 'align' => 'text-center'],
                        [
                            'key' => 'urgencia_label',
                            'label' => 'Urgencia',
                            'sortable' => true,
                            'align' => 'text-center',
                        ],
                        ['key' => 'estado_label', 'label' => 'Estado', 'sortable' => true, 'align' => 'text-center'],
                        [
                            'key' => 'fecha_inicio',
                            'label' => 'Fecha Inicio',
                            'sortable' => true,
                            'align' => 'text-center',
                        ],
                        ['key' => 'fecha_fin', 'label' => 'Fecha Final', 'sortable' => true, 'align' => 'text-center'],
                        ['key' => 'actions', 'label' => 'Acciones', 'sortable' => false, 'align' => 'text-center', 'sticky' => true],
                    ];

                    // Prepare rows as plain arrays that include HTML for actions and labels
                    $rows = $requerimientos
                        ->map(function ($r) {
                            $urg = strtolower($r->urgencia ?? '');
                            $priorityColors = [
                                'mayor' => 'bg-neutral-lightest text-neutral-darkest border border-neutral-dark',
                                'alta' => 'bg-error-light text-error-dark',
                                'media' => 'bg-warning-light text-warning-dark',
                                'baja' => 'bg-success-light text-success-dark',
                            ];
                            $priorityClass =
                                $priorityColors[$urg] ??
                                'bg-neutral-lightest text-neutral-darkest border border-neutral-dark';
                            $urgHtml =
                                '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs ' .
                                $priorityClass .
                                '">' .
                                ucfirst($r->urgencia ?? 'N/A') .
                                '</span>';

                            $estadoNombre = $r->estado_nombre;
                            $statusColors = [
                                'en proceso' => 'bg-yellow-100 text-yellow-800',
                                'cubierto' => 'bg-green-100 text-green-800',
                                'cancelado' => 'bg-red-100 text-red-800',
                                'vencido' => 'bg-gray-200 text-gray-700',
                            ];
                            $statusClass =
                                $statusColors[strtolower($estadoNombre ?? '')] ?? 'bg-gray-100 text-gray-600';
                            $estadoHtml =
                                '<span class="inline-flex items-center px-3 py-1 rounded-full text-xs ' .
                                $statusClass .
                                '">' .
                                ($estadoNombre ?? 'N/A') .
                                '</span>';
                            $actions = '';
                            $actions .=
                                '<button x-on:click="$dispatch(\'open-modal\', { name: \'verRequerimiento\', id: ' .
                                $r->id .
                                ' })" class="btn-assign ..."><i class="fas fa-eye"></i></button>';
                            $actions .=
                                ' <button x-on:click="$dispatch(\'open-modal\', { name: \'form-edit\', id: ' .
                                $r->id .
                                ' })" class="btn-edit inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition" title="Editar"><i class="fas fa-edit"></i></button>';
                            $actions .=
                                ' <button onclick="eliminarRequerimiento(' .
                                $r->id .
                                ')" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition" title="Eliminar"><i class="fas fa-trash"></i></button>';

                            return [
                                'id' => $r->id,
                                'tipo_personal_nombre' => $r->tipo_personal_nombre,
                                'cargo_nombre' => $r->cargo_nombre,
                                'sucursal_nombre' => ucfirst($r->sucursal_nombre),
                                'cliente_nombre' => ucfirst($r->cliente_nombre),
                                'urgencia_label' => $urgHtml,
                                'estado_label' => $estadoHtml,
                                'fecha_inicio' => $r->fecha_inicio ? $r->fecha_inicio->format('d/m/Y') : '—',
                                'fecha_fin' => $r->fecha_fin ? $r->fecha_fin->format('d/m/Y') : '—',
                                'actions' => $actions,
                            ];
                        })
                        ->toArray();
                @endphp

                <div class="overflow-x-auto">
                    <x-data-table :columns="$columns" :rows="$rows" :initial-per-page="25"
                        empty-message="No hay requerimientos." />
                </div>

                <x-modal name="verRequerimiento" :show="false" maxWidth="2xl">
                    @php
                        $tabs = [
                            ['id' => 'general-info', 'label' => 'Información General', 'shortLabel' => 'Info'],
                            ['id' => 'employment-details', 'label' => 'Detalles del Puesto', 'shortLabel' => 'Puesto'],
                            ['id' => 'requirements', 'label' => 'Requisitos', 'shortLabel' => 'Req.'],
                            ['id' => 'compensation', 'label' => 'Remuneración', 'shortLabel' => 'Sueldo'],
                            ['id' => 'status', 'label' => 'Estado', 'shortLabel' => 'Estado'],
                        ];
                    @endphp

                    <x-tabs-modal :tabs="$tabs" name="verRequerimiento">
                        <x-slot name="title">
                            Detalle del Requerimiento
                        </x-slot>

                        <!-- SECCIÓN 1: INFORMACIÓN GENERAL -->
                        <div id="section-general-info-section" class="view-section p-4 lg:p-0">
                            <div class="mb-4 border-b border-neutral pb-3">
                                <h3 class="text-base font-semibold text-M2">Información General</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Fecha de Solicitud</label>
                                    <input type="text" disabled id="requerimiento-fecha-solicitud"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Solicitado por</label>
                                    <input type="text" disabled id="requerimiento-usuario"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Sucursal</label>
                                    <input type="text" disabled id="requerimiento-sucursal"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Cliente</label>
                                    <input type="text" disabled id="requerimiento-cliente"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 2: DETALLES DEL PUESTO -->
                        <div id="section-employment-details-section" class="view-section p-4 lg:p-0">
                            <div class="mb-4 border-b border-neutral pb-3">
                                <h3 class="text-base font-semibold text-M2">Detalles del Puesto</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Tipo de Personal</label>
                                    <input type="text" disabled id="requerimiento-tipo-personal"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Cargo Solicitado</label>
                                    <input type="text" disabled id="requerimiento-cargo"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Cantidad Requerida</label>
                                    <input type="text" disabled id="requerimiento-cantidad"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-M3 mb-1">Fecha Inicio</label>
                                        <input type="text" disabled id="requerimiento-fecha-inicio"
                                            class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                            value="—">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-M3 mb-1">Fecha Fin</label>
                                        <input type="text" disabled id="requerimiento-fecha-fin"
                                            class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                            value="—">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 3: REQUISITOS -->
                        <div id="section-requirements-section" class="view-section p-4 lg:p-0">
                            <div class="mb-4 border-b border-neutral pb-3">
                                <h3 class="text-base font-semibold text-M2">Requisitos</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Edad Mínima</label>
                                    <input type="text" disabled id="requerimiento-edad-min"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Edad Máxima</label>
                                    <input type="text" disabled id="requerimiento-edad-max"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Experiencia Mínima</label>
                                    <input type="text" disabled id="requerimiento-experiencia"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Grado Académico</label>
                                    <input type="text" disabled id="requerimiento-grado"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 4: REMUNERACIÓN -->
                        <div id="section-compensation-section" class="view-section p-4 lg:p-0">
                            <div class="mb-4 border-b border-neutral pb-3">
                                <h3 class="text-base font-semibold text-M2">Remuneración y Beneficios</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Sueldo Básico (S/)</label>
                                    <input type="text" disabled id="requerimiento-sueldo"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Beneficios Incluidos</label>
                                    <input type="text" disabled id="requerimiento-beneficios"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                            </div>
                        </div>

                        <!-- SECCIÓN 5: ESTADO -->
                        <div id="section-status-section" class="view-section p-4 lg:p-0">
                            <div class="mb-4 border-b border-neutral pb-3">
                                <h3 class="text-base font-semibold text-M2">Estado</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Estado Actual</label>
                                    <input type="text" disabled id="requerimiento-estado"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-M3 mb-1">Urgencia</label>
                                    <input type="text" disabled id="requerimiento-urgencia"
                                        class="form-input w-full px-2 py-1 text-sm border border-gray-300 rounded-lg bg-gray-50 text-gray-700 cursor-not-allowed"
                                        value="—">
                                </div>
                            </div>
                        </div>
                    </x-tabs-modal>

                    <x-slot name="footer">
                        <x-cancel-button type="button" x-on:click="$dispatch('close-modal', 'verRequerimiento')">
                            Cerrar
                        </x-cancel-button>
                    </x-slot>
                </x-modal>

                <x-modal name="form-edit" :show="false" maxWidth="2xl">
                    <x-slot name="title">
                        <h2 class="text-lg text-M2 font-bold">Editar Requerimiento</h2>
                        <p class="text-sm text-M3">Modifica los datos del requerimiento según sea necesario.</p>
                    </x-slot>
                    <div id="edit-modal-content">
                    </div>
                    <x-slot name="footer">
                        <x-cancel-button type="button" x-on:click="$dispatch('close-modal', 'form-edit')">
                            Cancelar
                        </x-cancel-button>
                        <x-confirm-button type="button" id="edit-submit-btn"
                            onclick="document.getElementById('form-edit').dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }))">
                            Guardar Cambios
                        </x-confirm-button>
                    </x-slot>
                </x-modal>

                {{-- Modal de Eliminación --}}
                <div id="delete-modal"
                    class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                    role="dialog" aria-modal="true" aria-labelledby="delete-title" aria-describedby="delete-desc">
                    <!-- Panel -->
                    <div class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl border border-gray-100 p-6 opacity-0 translate-y-2 transition-all duration-200 ease-out data-[open=true]:opacity-100 data-[open=true]:translate-y-0"
                        id="delete-panel" data-open="true">
                        <!-- Encabezado -->
                        <div class="flex items-start gap-3 mb-4">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50">
                                <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                            </div>
                            <div>
                                <h3 id="delete-title" class="text-xl font-semibold">¿Eliminar requerimiento?</h3>
                                <p id="delete-desc" class="text-sm text-gray-600 mt-1">Esta acción no se puede deshacer.
                                </p>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="mt-5 flex justify-end gap-3 pt-4 border-t border-gray-100">
                            <button type="button" onclick="closeDeleteModal()"
                                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800 focus:outline-none focus:ring focus:ring-gray-300 transition">
                                Cancelar
                            </button>

                            <button type="button" onclick="confirmDelete()"
                                class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white focus:outline-none focus:ring focus:ring-red-300 transition">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteRequerimientoId = null;
        let deleteTriggerEl = null;
        const modal = document.getElementById('delete-modal');
        const panel = document.getElementById('delete-panel');
        const btnCancelar = modal?.querySelector('button[onclick="closeDeleteModal()"]');
        const btnEliminar = modal?.querySelector('button[onclick="confirmDelete()"]');

        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));
        const sucursales = Object.values(@json($sucursales));
        const tipoCargos = Object.values(@json($tipoCargos));
        const departamentos = Object.values(@json($departamentos));
        const nivelEducativo = @json($nivelEducativo ?? []);
        const estados = @json($estados ?? []);

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        function openDeleteModal(id, triggerEl = null) {
            deleteRequerimientoId = id;
            deleteTriggerEl = triggerEl || document.activeElement;

            // Mostrar overlay
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Bloquear scroll del fondo
            document.body.style.overflow = 'hidden';

            // Forzar reflow para que la transición se aplique
            void panel.offsetWidth;
            panel.setAttribute('data-open', 'true');

            // Foco al botón más seguro (Cancelar)
            setTimeout(() => {
                btnCancelar?.focus();
            }, 0);

            // Listeners de UX
            document.addEventListener('keydown', onEscClose);
            modal.addEventListener('mousedown', onBackdropClick);
        }

        function eliminarRequerimiento(id) {
            openDeleteModal(id);
        }

        function closeDeleteModal() {
            deleteRequerimientoId = null;

            // Animación de salida
            panel.setAttribute('data-open', 'false');

            // Habilitar de nuevo botones y texto original si estaba cargando
            restoreButtons();

            // Quitar listeners
            document.removeEventListener('keydown', onEscClose);
            modal.removeEventListener('mousedown', onBackdropClick);

            // Devolver scroll
            document.body.style.overflow = '';

            // Ocultar después de la duración de la transición (200ms)
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

        function onEscClose(event) {
            if (event.key === 'Escape') {
                closeDeleteModal();
            }
        }

        function onBackdropClick(event) {
            if (!panel.contains(event.target)) {
                closeDeleteModal();
            }
        }

        async function confirmDelete() {
            if (!deleteRequerimientoId) {
                closeDeleteModal();
                return;
            }

            // Estado de carga
            const prevEliminarText = btnEliminar?.textContent;
            btnEliminar.disabled = true;
            btnCancelar.disabled = true;
            btnEliminar.textContent = 'Eliminando…';

            try {
                const res = await fetch(`/requerimientos/${deleteRequerimientoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });

                // Si tu backend devuelve 204 No Content, evita .json()
                let data = null;
                const contentType = res.headers.get('Content-Type') || '';
                if (contentType.includes('application/json')) {
                    data = await res.json().catch(() => null);
                }

                if (res.ok && (!data || data.success !== false)) {
                    // éxito: recarga o elimina fila de la tabla si quieres hacerlo sin recargar
                    window.location.reload();
                    return;
                } else {
                    alert((data && data.message) ? data.message : 'Error al eliminar');
                }
            } catch (err) {
                alert('Error al eliminar');
            } finally {
                // Si no recargó (hubo error), restauro y cierro
                if (!document.hidden) {
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

        // Filtrar cargos dinámicamente en el filtro principal
        const tipoCargoFiltro = document.getElementById('tipo_cargo');
        if (tipoCargoFiltro) {
            tipoCargoFiltro.addEventListener('change', function() {
                const tipoCargoId = this.value;
                const cargoSelect = document.getElementById('cargo');

                // Solo ejecutar si existe el elemento cargo (filtro principal)
                if (!cargoSelect) return;

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
        }

        // Filtrar provincias al cambiar departamento
        const departamentoFiltro = document.getElementById('departamento');
        if (departamentoFiltro) {
            departamentoFiltro.addEventListener('change', function() {
                const depaId = this.value.padStart(2, '0');
                const provinciaSelect = document.getElementById('provincia');

                // Solo ejecutar si existe el elemento provincia (filtro principal)
                if (!provinciaSelect) return;

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
        }

        // Filtrar distritos al cambiar provincia
        const provinciaFiltro = document.getElementById('provincia');
        if (provinciaFiltro) {
            provinciaFiltro.addEventListener('change', function() {
                const provId = this.value.padStart(4, '0');
                const distritoSelect = document.getElementById('distrito');

                // Solo ejecutar si existe el elemento distrito (filtro principal)
                if (!distritoSelect) return;

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
        }

        // Form submission handler for edit modal
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
                                title: '¡Éxito!',
                                text: '¡Requerimiento actualizado correctamente!',
                                confirmButtonColor: '#3085d6',
                                timer: 1800,
                                timerProgressBar: true,
                                showConfirmButton: false
                            });
                            // Cerrar el modal usando Alpine.js
                            window.dispatchEvent(new CustomEvent('close-modal', {
                                detail: 'form-edit'
                            }));
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
                        let mensaje = 'Ocurrió un error inesperado.';
                        if (err.errors) {
                            mensaje = Object.values(err.errors).flat().join('\n');
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: mensaje,
                            confirmButtonColor: '#d33'
                        });
                        console.error('Errores de validación:', err.errors || err);
                    });
            }
        });

        function initEditForm(root) {
            // Elementos del modal
            const tipoSel = root.querySelector('#tipo_cargo_edit');
            const cargoSel = root.querySelector('#cargo_edit');
            const depaSel = root.querySelector('#departamento_edit');
            const provSel = root.querySelector('#provincia_edit');
            const distSel = root.querySelector('#distrito_edit');
            if (!tipoSel || !cargoSel || !depaSel || !provSel || !distSel) return;

            // Helpers
            const pad = (v, l) => (v == null ? '' : String(v).replace(/\D+/g, '').padStart(l, '0', ));
            const setOptions = (select, items, valueKey, labelKey, placeholder) => {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                items.forEach(it => {
                    const opt = document.createElement('option');
                    opt.value = it[valueKey];
                    opt.textContent = it[labelKey];
                    select.appendChild(opt);
                });
            };

            const fillCargos = (tipo, preselect = null) => {
                const t = pad(tipo, 2);
                const list = cargos
                    .filter(c => pad(c.TIPO_CARG, 2) === t)
                    .map(c => ({
                        value: c.CODI_CARG,
                        label: c.DESC_CARGO
                    }));
                setOptions(cargoSel, list, 'value', 'label', 'Selecciona el cargo');
                if (preselect) cargoSel.value = String(preselect);
            };

            const fillProvs = (depa, preselect = null) => {
                const d = pad(depa, 2);
                const list = provincias
                    .filter(p => pad(p.DEPA_CODIGO, 2) === d)
                    .map(p => ({
                        value: pad(p.PROVI_CODIGO, 4),
                        label: p.PROVI_DESCRIPCION
                    }));
                setOptions(provSel, list, 'value', 'label', 'Selecciona…');
                if (preselect) provSel.value = pad(preselect, 4);
            };

            const fillDists = (prov, preselect = null) => {
                const p = pad(prov, 4);
                const list = distritos
                    .filter(d => pad(d.PROVI_CODIGO, 4) === p)
                    .map(d => ({
                        value: pad(d.DIST_CODIGO, 6),
                        label: d.DIST_DESCRIPCION
                    }));
                setOptions(distSel, list, 'value', 'label', 'Selecciona…');
                if (preselect) distSel.value = pad(preselect, 6);
            };

            // Estado inicial usando los data-value del parcial
            fillCargos(tipoSel.value, cargoSel.dataset.value || null);
            fillProvs(depaSel.value, provSel.dataset.value || null);
            fillDists(provSel.value, distSel.dataset.value || null);

            // Listeners en el modal
            tipoSel.addEventListener('change', () => {
                fillCargos(tipoSel.value, null); // recarga cargos y limpia selección
            });
            depaSel.addEventListener('change', () => {
                fillProvs(depaSel.value, null); // recarga provincias
                setOptions(distSel, [], 'value', 'label', 'Selecciona…'); // limpia distritos
            });
            provSel.addEventListener('change', () => {
                fillDists(provSel.value, null); // recarga distritos
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Selecciona los campos
            const cantidadRequerida = document.getElementById('cantidad_requerida');
            const cantidadMasculino = document.getElementById('cantidad_masculino');
            const cantidadFemenino = document.getElementById('cantidad_femenino');
            const errorCantidad = document.getElementById('error-cantidad');
            const errorSexo = document.getElementById('error-sexo');

            function validarSumaSexo() {
                // Valores a enteros (o 0)
                const req = parseInt(cantidadRequerida.value) || 0;
                const masc = parseInt(cantidadMasculino.value) || 0;
                const fem = parseInt(cantidadFemenino.value) || 0;

                // Reset errores
                cantidadRequerida.classList.remove('border-red-500');
                cantidadMasculino.classList.remove('border-red-500');
                cantidadFemenino.classList.remove('border-red-500');
                errorCantidad.classList.add('hidden');
                errorSexo.classList.add('hidden');

                if (req === 0 && (masc > 0 || fem > 0)) {
                    // Si no hay cantidad requerida pero sí en sexo
                    errorCantidad.textContent = "Primero indique la cantidad requerida.";
                    errorCantidad.classList.remove('hidden');
                    cantidadRequerida.classList.add('border-red-500');
                    return false;
                }
                if ((masc + fem) > req) {
                    errorSexo.textContent = `La suma (${masc + fem}) supera la cantidad requerida (${req}).`;
                    errorSexo.classList.remove('hidden');
                    cantidadMasculino.classList.add('border-red-500');
                    cantidadFemenino.classList.add('border-red-500');
                    return false;
                }
                if ((masc + fem) < req) {
                    errorSexo.textContent = `La suma (${masc + fem}) es menor que la cantidad requerida (${req}).`;
                    errorSexo.classList.remove('hidden');
                    cantidadMasculino.classList.add('border-red-500');
                    cantidadFemenino.classList.add('border-red-500');
                    return false;
                }
                // Si es igual está correcto
                return true;
            }

            // Valida en cada cambio
            [cantidadRequerida, cantidadMasculino, cantidadFemenino].forEach(input => {
                input.addEventListener('input', validarSumaSexo);
            });

            // Si usas validación al enviar formulario, puedes impedir submit si hay error
            const form = cantidadRequerida.closest('form');
            form.addEventListener('submit', function(e) {
                if (!validarSumaSexo()) {
                    e.preventDefault();
                }
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            const tipoPersonal = document.getElementById('tipo_personal');
            const camposOperativo = document.getElementById('campos-operativo');
            if (!tipoPersonal || !camposOperativo) return;

            // Si mañana agregas Operativo 5º (03), solo lo sumas aquí.
            const CODIGOS_OPERATIVO = ['01']; // 01 = Operativo 4º

            const inputsOperativo = camposOperativo.querySelectorAll('select, input, textarea');

            function toggleCamposOperativo() {
                const esOperativo = CODIGOS_OPERATIVO.includes(tipoPersonal.value);

                camposOperativo.style.display = esOperativo ? '' : 'none';

                inputsOperativo.forEach(el => {
                    el.required = esOperativo;
                    if (!esOperativo) {
                        el.value = '';
                        // Si prefieres bloquearlos en lugar de limpiar, usa:
                        // el.disabled = true; (y quítalo cuando esOperativo)
                    } else {
                        // el.disabled = false;
                    }
                });
            }

            tipoPersonal.addEventListener('change', toggleCamposOperativo);
            toggleCamposOperativo(); // inicial (por si vienes de "editar")
        });

        document.addEventListener('DOMContentLoaded', () => {
            const selSucursal = document.getElementById('sucursal');
            const selCliente = document.getElementById('cliente');
            const URL_CLIENTES = "{{ route('requerimientos.clientes_por_sucursal') }}"; // usa tu ruta al SP

            function setOptions(select, items, placeholder) {
                select.innerHTML = '';
                const first = document.createElement('option');
                first.value = '';
                first.textContent = placeholder;
                select.appendChild(first);

                items.forEach(x => {
                    const value = (x.CODIGO_CLIENTE ?? x.codigo_cliente ?? '').toString().trim();
                    const text = (x.NOMBRE_CLIENTE ?? x.nombre_cliente ?? value);
                    const op = document.createElement('option');
                    op.value = value;
                    op.textContent = text;
                    select.appendChild(op);
                });
            }

            async function cargarClientesPorSucursal(codSucursal) {
                if (!codSucursal) {
                    setOptions(selCliente, [], 'Selecciona un cliente');
                    return;
                }
                selCliente.innerHTML = '<option value="">Cargando...</option>';

                try {
                    const u = new URL(URL_CLIENTES, window.location.origin);
                    u.searchParams.set('codigo_sucursal', codSucursal);

                    const res = await fetch(u, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await res.json();

                    if (!Array.isArray(data) || data.length === 0) {
                        setOptions(selCliente, [], 'Sin clientes para esta sucursal');
                        return;
                    }
                    setOptions(selCliente, data, 'Selecciona un cliente');
                } catch (e) {
                    console.error(e);
                    setOptions(selCliente, [], 'Error al cargar');
                }
            }

            if (selSucursal) {
                selSucursal.addEventListener('change', () => {
                    cargarClientesPorSucursal(selSucursal.value || '');
                });

                // Si ya viene seleccionada (editar / volver con old inputs)
                if (selSucursal.value) selSucursal.dispatchEvent(new Event('change'));
            }
        });

        //METODO PARA CALCULAR LA URGENCIA AUTOMÁTICA ----**
        document.addEventListener('DOMContentLoaded', function() {
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            const urgenciaBox = document.getElementById('urgenciaAutoBox');
            const urgenciaDiv = document.getElementById('urgenciaAuto');
            const urgenciaSelect = document.getElementById('urgencia');

            // Solo ejecutar si todos los elementos existen
            if (!fechaInicio || !fechaFin || !urgenciaDiv || !urgenciaSelect) return;

            function setUrgencia(valor, texto, colorClass) {
                urgenciaDiv.textContent = texto;
                urgenciaDiv.className =
                    'rounded-lg px-4 py-2 font-semibold text-center transition-all duration-300 ' + colorClass;
                urgenciaSelect.value = valor;
                urgenciaSelect.className =
                    'form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 ' +
                    colorClass;
            }

            function calcularUrgencia() {
                if (fechaInicio.value && fechaFin.value) {
                    const inicio = new Date(fechaInicio.value);
                    const fin = new Date(fechaFin.value);
                    const diffMs = fin - inicio;
                    const diffDias = diffMs / (1000 * 60 * 60 * 24);

                    if (diffDias < 0) {
                        setUrgencia("Invalida", "¡Fechas inválidas!", "bg-gray-400 text-white");
                    } else if (diffDias <= 7) {
                        setUrgencia("Alta", "Nivel de urgencia: Alta (1 semana)", "bg-red-500 text-white");
                    } else if (diffDias > 7 && diffDias <= 14) {
                        setUrgencia("Media", "Nivel de urgencia: Media (2 semanas)", "bg-yellow-400 text-gray-900");
                    } else if (diffDias > 14 && diffDias <= 31) {
                        setUrgencia("Baja", "Nivel de urgencia: Baja (1 mes)", "bg-green-500 text-white");
                    } else {
                        setUrgencia("Mayor", "Plazo mayor a 1 mes", "bg-blue-400 text-white");
                    }
                } else {
                    setUrgencia("", "NO SE SELECCIONÓ LA FECHA", "bg-gray-200 text-gray-700");
                }
            }

            // Escuchar cambios en las fechas
            fechaInicio.addEventListener('change', calcularUrgencia);
            fechaFin.addEventListener('change', calcularUrgencia);

            // Inicializar estado al cargar
            calcularUrgencia();
        });

        // Real-time validation
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('blur', function() {
                if (this.hasAttribute('required') && !this.value.trim()) {
                    this.classList.add('border-red-500');
                    const errorMessage = this.parentElement.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.textContent = 'Este campo es obligatorio';
                        errorMessage.classList.remove('hidden');
                    }
                } else {
                    this.classList.remove('border-red-500');
                    const errorMessage = this.parentElement.querySelector('.error-message');
                    if (errorMessage) {
                        errorMessage.classList.add('hidden');
                    }
                }
            });
        });

        const clienteSelect = document.getElementById('cliente');
        if (clienteSelect) {
            clienteSelect.addEventListener('change', function() {
                let clienteId = this.value;
                let sedeSelect = document.getElementById('sede');

                // Solo ejecutar si existe el elemento sede
                if (!sedeSelect) return;

                sedeSelect.innerHTML = '<option value="">Cargando...</option>';

                if (clienteId) {
                    fetch('/requerimientos/sedes-por-cliente?codigo_cliente=' + clienteId)
                        .then(response => response.json())
                        .then(sedes => {
                            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                            sedes.forEach(sede => {
                                sedeSelect.innerHTML +=
                                    `<option value="${sede.CODIGO}">${sede.SEDE}</option>`;
                            });
                        })
                        .catch(() => {
                            sedeSelect.innerHTML = '<option value="">Error al cargar</option>';
                        });
                } else {
                    sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async () => {
            const selTipoPersonal = document.getElementById('tipo_personal');
            const selTipoCargo = document.getElementById('tipo_cargo');
            const selCargoSolicitado = document.getElementById('cargo_solicitado');

            const URL_TIPOS = "{{ route('api.tipos_cargo') }}";
            const URL_CARGOS = "{{ route('api.cargos') }}";

            function setLoading(select, texto = 'Cargando...') {
                select.innerHTML = `<option value="">${texto}</option>`;
                select.disabled = true;
            }

            function setEmpty(select, texto = 'Seleccione...') {
                select.innerHTML = `<option value="">${texto}</option>`;
                select.disabled = true;
            }

            function fillSelect(select, data, placeholder = 'Seleccione...') {
                select.innerHTML = `<option value="">${placeholder}</option>`;
                data.forEach(it => {
                    const o = document.createElement('option');
                    o.value = String(it.value).trim();
                    o.textContent = it.label;
                    select.appendChild(o);
                });
                select.disabled = data.length === 0;
            }

            async function cargarTiposCargo(tipoPersonal, preselect = null) {
                if (!tipoPersonal) {
                    setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
                    setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                    return;
                }
                setLoading(selTipoCargo);
                setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                try {
                    const res = await fetch(
                        `${URL_TIPOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}`);
                    const data = await res.json(); // [{value,label}]
                    fillSelect(selTipoCargo, data, 'Seleccione el tipo de cargo');
                    if (preselect) {
                        selTipoCargo.value = preselect;
                        selTipoCargo.dispatchEvent(new Event('change'));
                    }
                } catch {
                    setEmpty(selTipoCargo, 'Error al cargar');
                }
            }

            async function cargarCargos(tipoPersonal, tipoCargo, preselect = null) {
                if (!tipoPersonal || !tipoCargo) {
                    setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                    return;
                }
                setLoading(selCargoSolicitado);
                try {
                    const res = await fetch(
                        `${URL_CARGOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}&tipo_cargo=${encodeURIComponent(tipoCargo)}`
                    );
                    const data = await res.json(); // [{value,label}]
                    fillSelect(selCargoSolicitado, data, 'Seleccione el cargo');
                    if (preselect) selCargoSolicitado.value = preselect;
                } catch {
                    setEmpty(selCargoSolicitado, 'Error al cargar');
                }
            }

            // Eventos
            selTipoPersonal.addEventListener('change', () => cargarTiposCargo(selTipoPersonal.value));
            selTipoCargo.addEventListener('change', () => cargarCargos(selTipoPersonal.value, selTipoCargo
                .value));

            // Estado inicial
            setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
            setEmpty(selCargoSolicitado, 'Seleccione el cargo');

            // Precarga por old()
            const oldTipoPersonal = "{{ old('tipo_personal') }}";
            const oldTipoCargo = "{{ old('tipo_cargo') }}";
            const oldCargoSolicitado = "{{ old('cargo_solicitado') }}";
            if (oldTipoPersonal) {
                selTipoPersonal.value = oldTipoPersonal;
                await cargarTiposCargo(oldTipoPersonal, oldTipoCargo);
                if (oldTipoCargo) {
                    await cargarCargos(oldTipoPersonal, oldTipoCargo, oldCargoSolicitado);
                }
            }
        });

        // Update scale info based on selection
        document.getElementById('beneficios').addEventListener('change', function() {
            const infoBox = document.querySelector('.bg-blue-50 p strong');
            if (infoBox && this.value) {
                infoBox.textContent = `Escala seleccionada: ${this.options[this.selectedIndex].text}`;
            }
        });

        // ============ WIZARD LOGIC ============
        let wizardStep = 0;
        const totalSteps = 4;

        function updateWizardUI() {
            // Mostrar/ocultar contenido de cada paso
            document.querySelectorAll('.step-content').forEach((el, i) => {
                el.classList.toggle('hidden', i !== wizardStep);
            });

            // Actualizar indicadores de pasos
            document.querySelectorAll('[id^="step-indicator-"]').forEach((el, i) => {
                if (i === wizardStep) {
                    el.className =
                        'w-10 h-10 flex items-center justify-center rounded-full border-2 border-M1 text-M1 bg-M6 font-semibold';
                    document.getElementById(`step-label-${i}`).className = 'mt-2 text-sm text-M1 font-semibold';
                } else if (i < wizardStep) {
                    el.className =
                        'w-10 h-10 flex items-center justify-center rounded-full border-2 border-green-500 text-white bg-green-500 font-semibold';
                    document.getElementById(`step-label-${i}`).className =
                        'mt-2 text-sm text-green-600 font-semibold';
                } else {
                    el.className =
                        'w-10 h-10 flex items-center justify-center rounded-full border-2 border-neutral text-M3 bg-white font-semibold';
                    document.getElementById(`step-label-${i}`).className = 'mt-2 text-sm text-M3';
                }
            });

            // Mostrar/ocultar botones
            document.getElementById('wizard-prev-btn').classList.toggle('hidden', wizardStep === 0);
            document.getElementById('wizard-next-btn').classList.toggle('hidden', wizardStep === totalSteps - 1);
            document.getElementById('wizard-submit-btn').classList.toggle('hidden', wizardStep !== totalSteps - 1);
        }

        function validateStep(stepIdx) {
            const container = document.getElementById(`step-content-${stepIdx}`);
            const required = container.querySelectorAll('[required]');
            let isValid = true;
            const invalidFields = [];

            required.forEach((el) => {
                // Ignorar campos que están ocultos o dentro de contenedores ocultos
                if (el.offsetParent === null || el.style.display === 'none') {
                    return; // Skip hidden fields
                }

                // Verificar si el campo está dentro de un contenedor oculto
                let parent = el.parentElement;
                let isHidden = false;
                while (parent && parent !== container) {
                    if (parent.style.display === 'none' || parent.offsetParent === null) {
                        isHidden = true;
                        break;
                    }
                    parent = parent.parentElement;
                }

                if (isHidden) return; // Skip if parent is hidden

                const val = (el.value || '').trim();
                if (!val) {
                    el.classList.add('border-red-500');
                    const err = el.parentElement?.querySelector('.error-message');
                    if (err) {
                        err.textContent = 'Este campo es obligatorio';
                        err.classList.remove('hidden');
                    }
                    invalidFields.push(el.name || el.id || 'campo desconocido');
                    isValid = false;
                } else {
                    el.classList.remove('border-red-500');
                    const err = el.parentElement?.querySelector('.error-message');
                    if (err) err.classList.add('hidden');
                }
            });

            if (!isValid) {
                console.log(`Paso ${stepIdx + 1} - Campos inválidos:`, invalidFields);
            }

            return isValid;
        }

        function wizardNext() {
            if (validateStep(wizardStep) && wizardStep < totalSteps - 1) {
                wizardStep++;
                updateWizardUI();
            }
        }

        function wizardPrev() {
            if (wizardStep > 0) {
                wizardStep--;
                updateWizardUI();
            }
        }

        function closeRequerimientoWizard() {
            document.dispatchEvent(new CustomEvent('close-modal', {
                detail: 'crearRequerimiento'
            }));
        }

        // Manejo del submit del formulario
        const wizardForm = document.getElementById('requerimiento-wizard-form');
        wizardForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            console.log('Intentando enviar formulario...');

            let allValid = true;
            for (let i = 0; i < totalSteps; i++) {
                if (!validateStep(i)) {
                    allValid = false;
                    wizardStep = i;
                    console.log(`Validación falló en el paso ${i + 1}`);
                    break;
                }
            }

            if (allValid) {
                console.log('Todos los pasos son válidos, enviando formulario...');

                // Recopilar datos del formulario
                const formData = new FormData(this);
                console.log('Datos del formulario:');
                for (let [key, value] of formData.entries()) {
                    console.log(`  ${key}: ${value}`);
                }

                try {
                    // Enviar por AJAX para capturar errores
                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                ?.getAttribute('content') || ''
                        }
                    });

                    console.log('Respuesta del servidor - Status:', response.status);

                    // Intentar parsear como JSON primero
                    let responseData;
                    const contentType = response.headers.get('content-type');

                    if (contentType && contentType.includes('application/json')) {
                        responseData = await response.json();
                        console.log('Respuesta JSON:', responseData);
                    } else {
                        const text = await response.text();
                        console.log('Respuesta de texto:', text);
                        try {
                            responseData = JSON.parse(text);
                        } catch {
                            responseData = {
                                message: text
                            };
                        }
                    }

                    if (response.ok) {
                        console.log('✓ Éxito - Requerimiento creado');
                        alert('¡Requerimiento creado exitosamente!');
                        window.location.reload();
                    } else {
                        console.error('✗ Error - Status:', response.status);

                        let errorMessage = responseData.message || 'Error al crear el requerimiento';

                        // Si hay errores de validación, mostrarlos
                        if (responseData.errors) {
                            errorMessage += '\n\nErrores de validación:\n';
                            Object.entries(responseData.errors).forEach(([field, messages]) => {
                                errorMessage +=
                                    `\n• ${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`;
                            });
                        }

                        console.error('Mensaje de error:', errorMessage);
                        alert(errorMessage);
                    }
                } catch (error) {
                    console.error('✗ Error de red:', error);
                    alert('Error de conexión. Por favor intenta nuevamente.\n\n' + error.message);
                }
            } else {
                console.log('Validación falló, mostrando paso con errores');
                updateWizardUI();
                alert('Por favor completa todos los campos requeridos en el paso ' + (wizardStep + 1));
            }
        });

        // Inicializar UI del wizard al cargar
        document.addEventListener('DOMContentLoaded', function() {
            wizardStep = 0;
            updateWizardUI();
        });

        // Lógica para cargar datos en el modal de ver requerimiento
        document.addEventListener('open-modal', function(e) {
            const modalName = e.detail?.name;
            const id = e.detail?.id;

            // Modal de Ver Requerimiento
            if (modalName === 'verRequerimiento') {
                // Función auxiliar para asignar valor solo si el elemento existe
                function setValueIfExists(elementId, value) {
                    const element = document.getElementById(elementId);
                    if (element) {
                        element.value = value || '—';
                    }
                }

                // Cargar datos desde tu ruta JSON
                fetch(`/requerimientos/${id}/detalle`)
                    .then(res => res.json())
                    .then(data => {
                        // SECCIÓN 1: INFORMACIÓN GENERAL
                        setValueIfExists('requerimiento-fecha-solicitud', data.fecha_solicitud);
                        setValueIfExists('requerimiento-usuario', data.usuario_nombre || data.user_id);
                        setValueIfExists('requerimiento-sucursal', data.sucursal_nombre);
                        setValueIfExists('requerimiento-cliente', data.cliente_nombre);

                        // SECCIÓN 2: DETALLES DEL PUESTO
                        setValueIfExists('requerimiento-tipo-personal', data.tipo_personal_nombre);
                        setValueIfExists('requerimiento-cargo', data.cargo_nombre);
                        setValueIfExists('requerimiento-cantidad', data.cantidad_requerida);
                        setValueIfExists('requerimiento-fecha-inicio', data.fecha_inicio);
                        setValueIfExists('requerimiento-fecha-fin', data.fecha_fin);

                        // SECCIÓN 3: REQUISITOS
                        setValueIfExists('requerimiento-edad-min', data.edad_minima);
                        setValueIfExists('requerimiento-edad-max', data.edad_maxima);
                        setValueIfExists('requerimiento-experiencia', data.experiencia_minima);
                        setValueIfExists('requerimiento-grado', data.grado_academico);

                        // SECCIÓN 4: REMUNERACIÓN
                        const sueldoElement = document.getElementById('requerimiento-sueldo');
                        if (sueldoElement) {
                            sueldoElement.value = data.sueldo_basico ? 'S/ ' + parseFloat(data.sueldo_basico)
                                .toFixed(2) : '—';
                        }
                        setValueIfExists('requerimiento-beneficios', data.beneficios_nombre || data.beneficios);

                        // SECCIÓN 5: ESTADO
                        setValueIfExists('requerimiento-estado', data.estado_nombre);
                        setValueIfExists('requerimiento-urgencia', data.urgencia);
                    })
                    .catch(err => {
                        console.error('Error al cargar datos del requerimiento:', err);
                        alert('Error al cargar los detalles del requerimiento');
                    });
            }

            // Modal de Editar Requerimiento
            if (modalName === 'form-edit' && id) {
                const container = document.getElementById('edit-modal-content');
                if (container) {
                    // Mostrar loader
                    container.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-spinner fa-spin text-gray-400 text-2xl"></i>
                            <p class="text-gray-500 mt-2">Cargando formulario...</p>
                        </div>
                    `;

                    // Cargar el partial desde el método edit()
                    fetch(`/requerimientos/${id}/edit`)
                        .then(res => res.text())
                        .then(html => {
                            container.innerHTML = html;
                        })
                        .catch(err => {
                            console.error('Error al cargar formulario de edición:', err);
                            container.innerHTML = `
                                <div class="text-center py-8 text-red-600">
                                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                                    <p class="mt-2">Error al cargar el formulario</p>
                                </div>
                            `;
                        });
                }
            }
        });
    </script>
@endsection
