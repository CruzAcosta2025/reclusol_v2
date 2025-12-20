@extends('layouts.app')

@section('module', 'requerimientos')

@section('content')
    <div class="space-y-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Encabezado --}}
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">Listado de Requerimientos
                        </h1>
                        <p class="text-sm text-white/70 mt-1">Gestiona y supervisa todos los requerimientos laborales de
                            manera eficiente</p>
                    </div>

                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('requerimientos.requerimiento') }}"
                            class="px-4 py-2 rounded-xl font-extrabold text-sm text-white"
                            style="background:linear-gradient(135deg,#3b82f6,#4f46e5);">
                            <i class="fas fa-user-plus mr-2"></i>Crear Requerimiento
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition text-white">
                            <i class="fas fa-gauge-high mr-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 panel-light p-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <select name="sucursal" id="sucursal"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($sucursales as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('sucursal') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente" id="cliente"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($clientes as $codigo => $desc)
                            {{-- $desc es STRING --}}
                            <option value="{{ $codigo }}" @selected((string) request('sucursal') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                    <select name="tipo_cargo" id="tipo_cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($tipoCargos as $codigo => $desc)
                            <option value="{{ $codigo }}"
                                {{ (string) request('tipo_cargo') === (string) $codigo ? 'selected' : '' }}>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo Solicitado</label>
                    <select name="cargo" id="cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($cargos as $codigo => $cargo)
                            <option value="{{ $codigo }}" {{ request('cargo') == $codigo ? 'selected' : '' }}>
                                {{ $cargo->DESC_CARGO }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{--
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select name="departamento" id="departamento"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($departamentos as $codigo => $desc)
                            <option value="{{ $codigo }}" @selected((string) request('departamento') === (string) $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>
        
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                    <select name="provincia" id="provincia"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        @foreach ($provincias as $codigo => $provincia)
                            <option value="{{ $codigo }}" {{ request('provincia') == $codigo ? 'selected' : '' }}>
                                {{ $provincia->PROVI_DESCRIPCION }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                    <select name="distrito" id="distrito"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($distritos as $codigo => $distrito)
                            <option value="{{ $codigo }}" {{ request('distrito') == $codigo ? 'selected' : '' }}>
                                {{ $distrito->DIST_DESCRIPCION }}
                            </option>
                        @endforeach
                    </select>
                </div>
                --}}

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

        <!-- Stats Section -->

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#ecfdf5;">
                        <i class="fas fa-hourglass-half text-green-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">En Proceso</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['en_validacion'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fef2f2;">
                        <i class="fas fa-check-circle text-red-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Cubiertos</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['aprobado'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#eff6ff;">
                        <i class="fa-solid fa-times-circle  text-blue-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Cancelados</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['cancelado'] ?? 0 }}</div>
                    </div>
                </div>

                <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                    <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fffbeb;">
                        <i class="fas fa-clock text-yellow-600 text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <div class="text-xs font-semibold text-gray-500">Vencidos</div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $stats['cerrado'] ?? 0 }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 mt-6">
        <div class="bg-white rounded-2xl shadow border">
            {{-- Encabezado --}}
            <div
                class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 flex justify-between items-center rounded-t-2xl">
                <h2 class="flex items-center text-lg font-semibold">
                    <i class="fas fa-list mr-2"></i>
                    Lista de Requerimientos
                </h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Tipo de Personal</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Cargo Solicitado</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Sucursal</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Cliente</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center"> Urgencia</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Estado </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Fecha Inicio </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Fecha Final </th>

                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Edad Minima </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Edad Maxima </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Experiencia Minima </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Grado Academico </th>

                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Sueldo </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Beneficios </th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($requerimientos as $requerimiento)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->tipo_personal_nombre }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">{{ $requerimiento->cargo_nombre }}</td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ ucfirst($requerimiento->sucursal_nombre) }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ ucfirst($requerimiento->cliente_nombre) }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    @php
                                        $urg = strtolower($requerimiento->urgencia ?? '');
                                        $priorityColors = [
                                            'alta' => 'bg-red-100 text-red-800',
                                            'media' => 'bg-yellow-100 text-yellow-800',
                                            'baja' => 'bg-green-100 text-green-800',
                                        ];
                                        $priorityClass = $priorityColors[$urg] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $priorityClass }}">
                                        {{ ucfirst($requerimiento->urgencia ?? 'N/A') }}
                                    </span>

                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    @php
                                        $estadoNombre = $requerimiento->estado_nombre; // ya viene del controller
                                        $statusColors = [
                                            'en proceso' => 'bg-yellow-100 text-yellow-800',
                                            'aprobado' => 'bg-green-100 text-green-800',
                                            'cancelado' => 'bg-red-100 text-red-800',
                                            'cerrado' => 'bg-gray-200 text-gray-700',
                                        ];
                                        $statusClass =
                                            $statusColors[strtolower($estadoNombre ?? '')] ??
                                            'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $statusClass }}">
                                        {{ $estadoNombre ?? 'N/A' }}
                                    </span>
                                </td>


                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->fecha_inicio ? $requerimiento->fecha_inicio->format('d/m/Y') : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->fecha_fin ? $requerimiento->fecha_fin->format('d/m/Y') : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->edad_minima ? $requerimiento->edad_minima . ' años' : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->edad_maxima ? $requerimiento->edad_maxima . ' años' : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->experiencia_minima ? $requerimiento->experiencia_minima : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->grado_academico ? $requerimiento->grado_academico : '—' }}
                                </td>
                                
                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->sueldo_basico ? 'S/' . $requerimiento->sueldo_basico : '—' }}
                                </td>

                                <td class="px-6 py-4 text-gray-700 text-center">
                                    {{ $requerimiento->beneficios ? $requerimiento->beneficios: '—' }}
                                </td>

                                <td class="px-4 py-3 flex space-x-2">
                                    <button data-id="{{ $requerimiento->id }}"
                                        class="btn-edit inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="eliminarRequerimiento({{ $requerimiento->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                        title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-6 text-gray-500">No hay requerimientos.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Modal de Edición --}}
            <div id="edit-modal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300">
                <div id="edit-panel"
                    class="bg-white w-full max-w-2xl mx-4 rounded-lg shadow-xl border border-gray-200
              max-h-[90vh] overflow-y-auto
              opacity-0 scale-90 transition-all duration-300 ease-out
              data-[open=true]:opacity-100 data-[open=true]:scale-100">
                    <div id="edit-modal-content"><!-- aquí se inyecta el formulario --></div>
                </div>
            </div>


            {{-- Modal de Eliminación --}}
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
                        <h3 id="delete-title" class="text-2xl font-bold text-gray-900 mb-2">¿Eliminar requerimiento?</h3>
                        <p id="delete-desc" class="text-base text-gray-700 leading-relaxed">Esta acción no se puede deshacer. Por favor, confirma que deseas eliminar este requerimiento.</p>
                    </div>

                    <!-- Línea separadora -->
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

        const editModal = document.getElementById('edit-modal');
        const editPanel = document.getElementById('edit-panel');
        const editContent = document.getElementById('edit-modal-content');
        let editTriggerEl = null;

        // Datos base que ya usas (provincias y distritos sí se aprovechan)
        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));

        // === ENDPOINTS (ajusta las URLs si tus rutas son distintas) ===
        // === ENDPOINTS (usa tus rutas reales) ===
        const CLIENTES_POR_SUCURSAL_URL = @json(route('requerimientos.clientes_por_sucursal'));
        const TIPOS_POR_TIPO_PERSONAL_URL = '/api/tipos-cargo';
        const CARGOS_POR_TIPO_URL = '/api/cargos';



        /* ==================== UTILIDADES GENERALES ==================== */

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        const setOptionsSimple = (select, placeholder, items, selectedValue = '') => {
            if (!select) return;
            select.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach(it => {
                const opt = document.createElement('option');
                opt.value = String(it.value ?? '');
                opt.textContent = String(it.label ?? '');
                if (selectedValue && String(it.value) === String(selectedValue)) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        };

        const padDigits = (v, len) => {
            const raw = String(v ?? '').replace(/\D+/g, '');
            return raw ? raw.padStart(len, '0') : '';
        };

        // Mapea ADMIN/OPER -> 02/01 (y si ya viene 01/02 lo deja igual)
        const mapTipoPersonal = (v) => {
            const x = String(v ?? '').trim().toUpperCase();
            if (x === '01' || x === 'OPERATIVO 4º' || x.includes('OPERATIVO 4º')) return '01';
            if (x === '02' || x === 'ADMINISTRADOR 4º' || x.includes('ADMINISTRADOR 4º')) return '02';
            return x;
        };

        // Crea/obtiene un div de mensaje debajo del select
        function ensureMsgEl(selectEl, key = 'msg') {
            if (!selectEl) return null;
            const id = `${selectEl.id || key}-msg`;
            let el = document.getElementById(id);
            if (!el) {
                el = document.createElement('div');
                el.id = id;
                el.className = 'mt-1 text-xs text-red-600';
                el.style.display = 'none';
                selectEl.insertAdjacentElement('afterend', el);
            }
            return el;
        }

        function showMsg(msgEl, text) {
            if (!msgEl) return;
            if (!text) {
                msgEl.textContent = '';
                msgEl.style.display = 'none';
            } else {
                msgEl.textContent = text;
                msgEl.style.display = 'block';
            }
        }


        /* ==================== BORRADO (MODAL DELETE) ==================== */

        function openDeleteModal(id, triggerEl = null) {
            deleteRequerimientoId = id;
            deleteTriggerEl = triggerEl || document.activeElement;

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            document.body.style.overflow = 'hidden';

            void panel.offsetWidth;
            panel.setAttribute('data-open', 'true');

            setTimeout(() => {
                btnCancelar?.focus();
            }, 0);

            document.addEventListener('keydown', onEscClose);
            modal.addEventListener('mousedown', onBackdropClick);
        }

        function eliminarRequerimiento(id) {
            openDeleteModal(id);
        }

        function closeDeleteModal() {
            deleteRequerimientoId = null;

            panel.setAttribute('data-open', 'false');
            restoreButtons();

            document.removeEventListener('keydown', onEscClose);
            modal.removeEventListener('mousedown', onBackdropClick);

            document.body.style.overflow = '';

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
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

                let data = null;
                const contentType = res.headers.get('Content-Type') || '';
                if (contentType.includes('application/json')) {
                    data = await res.json().catch(() => null);
                }

                if (res.ok && (!data || data.success !== false)) {
                    window.location.reload();
                    return;
                } else {
                    alert((data && data.message) ? data.message : 'Error al eliminar');
                }
            } catch (err) {
                alert('Error al eliminar');
            } finally {
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

        /* ==================== FILTRO: TIPO_CARGO → CARGO ==================== */

        // Esto lo mantengo como ya lo tenías (cliente-side con el array cargos)
        document.getElementById('tipo_cargo')?.addEventListener('change', function() {
            const tipoCargoId = this.value;
            const cargoSelect = document.getElementById('cargo');

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


        /* ==================== Sucursal → Cliente (filtro y modal) ==================== */
        async function loadClientesPorSucursalEnSelect(
            codigoSucursal,
            selectEl,
            placeholder = 'Todos',
            selectedValue = '',
            msgEl = null
        ) {
            if (!selectEl) return;

            msgEl = msgEl || ensureMsgEl(selectEl, 'clientes');
            showMsg(msgEl, null);

            setOptionsSimple(selectEl, placeholder, [], '');
            selectEl.disabled = true;

            if (!codigoSucursal) return;

            try {
                const url = `${CLIENTES_POR_SUCURSAL_URL}?codigo_sucursal=${encodeURIComponent(codigoSucursal)}`;
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                if (!res.ok) throw new Error('Error al cargar clientes');

                const data = await res.json();
                const items = (data || []).map(cli => ({
                    value: cli.CODIGO_CLIENTE ?? cli.codigo_cliente ?? '',
                    label: cli.NOMBRE_CLIENTE ?? cli.nombre_cliente ?? ''
                }));

                setOptionsSimple(selectEl, placeholder, items, selectedValue);

                if (items.length === 0) {
                    selectEl.disabled = true;
                    showMsg(msgEl, 'No se encontraron clientes para esta sucursal.');
                } else {
                    selectEl.disabled = false;
                    showMsg(msgEl, null);
                }
            } catch (e) {
                console.error('Error cargando clientes por sucursal', e);
                setOptionsSimple(selectEl, placeholder, [], '');
                selectEl.disabled = true;
                showMsg(msgEl, 'No se pudieron cargar clientes. Intenta nuevamente.');
            }
        }


        // Inicializar Sucursal→Cliente en el formulario de filtro (arriba)
        document.addEventListener('DOMContentLoaded', () => {
            const sucursalFilter = document.getElementById('sucursal');
            const clienteFilter = document.getElementById('cliente');

            if (sucursalFilter && clienteFilter) {
                const msgEl = ensureMsgEl(clienteFilter, 'cliente-filter');

                const initialSucursal = sucursalFilter.value;
                const initialCliente = clienteFilter.value;

                sucursalFilter.addEventListener('change', () => {
                    loadClientesPorSucursalEnSelect(sucursalFilter.value, clienteFilter, 'Todos', '',
                        msgEl);
                });

                if (initialSucursal) {
                    loadClientesPorSucursalEnSelect(initialSucursal, clienteFilter, 'Todos', initialCliente, msgEl);
                }
            }
        });


        /* ========== TipoPersonal → TipoCargo → CargoSolicitado (solo MODAL) ========== */

        async function fetchTiposCargoPorTipoPersonal(tipoPersonal) {
            if (!tipoPersonal) return [];

            // OJO: aquí NO mapeo ADMIN/OPER porque tú dices que ya usas 01/02.
            // Si tu select manda texto, ahí sí se mapea (te dejo abajo un extra).
            try {
                const url = `${TIPOS_POR_TIPO_PERSONAL_URL}?tipo_personal=${encodeURIComponent(tipoPersonal)}`;
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                if (!res.ok) throw new Error('Error al cargar tipos de cargo');

                const data = await res.json();

                // ✅ tu SP devuelve value/label
                return (data || []).map(row => ({
                    value: (row.value ?? row.VALUE ?? '').toString().trim(),
                    label: (row.label ?? row.LABEL ?? '').toString().trim(),
                })).filter(x => x.value);
            } catch (e) {
                console.error('Error fetchTiposCargoPorTipoPersonal', e);
                return [];
            }
        }

        async function fetchCargosPorTipo(tipoPersonal, tipoCargo) {
            if (!tipoPersonal || !tipoCargo) return [];

            try {
                const params = new URLSearchParams({
                    tipo_personal: tipoPersonal,
                    tipo_cargo: tipoCargo,
                });

                const url = `${CARGOS_POR_TIPO_URL}?${params.toString()}`;
                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!res.ok) throw new Error('Error al cargar cargos');

                const data = await res.json();

                // Soporta APIs que devuelven value/label o CODI_CARG/DESC_CARGO
                return (data || []).map(row => ({
                    value: (row.value ?? row.VALUE ?? row.CODI_CARG ?? row.codi_carg ?? row.codigo ?? '')
                        .toString().trim(),
                    label: (row.label ?? row.LABEL ?? row.DESC_CARGO ?? row.desc_cargo ?? row.nombre ?? '')
                        .toString().trim(),
                })).filter(x => x.value && x.label);
            } catch (e) {
                console.error('Error fetchCargosPorTipo', e);
                return [];
            }
        }



        function initTipoPersonalChain(tipoPersonalSelect, tipoCargoSelect, cargoSelect) {
            if (!tipoPersonalSelect || !tipoCargoSelect || !cargoSelect) return;

            const placeholderTipo = 'Selecciona el tipo de cargo';
            const placeholderCargo = 'Selecciona el cargo';

            const msgTipo = ensureMsgEl(tipoCargoSelect, 'tipo-cargo');
            const msgCargo = ensureMsgEl(cargoSelect, 'cargo');

            async function cargarTiposYResetCargos(tipoPersonal, selectedTipo = '', selectedCargo = '') {
                showMsg(msgTipo, null);
                showMsg(msgCargo, null);

                tipoCargoSelect.disabled = true;
                cargoSelect.disabled = true;

                const tipos = await fetchTiposCargoPorTipoPersonal(tipoPersonal);
                setOptionsSimple(tipoCargoSelect, placeholderTipo, tipos, selectedTipo);

                // reset cargos siempre
                setOptionsSimple(cargoSelect, placeholderCargo, [], '');
                cargoSelect.disabled = true;

                if (tipos.length === 0) {
                    showMsg(msgTipo, 'No hay tipos de cargo para el tipo de personal seleccionado.');
                    return;
                }

                tipoCargoSelect.disabled = false;

                if (selectedTipo) {
                    await cargarCargos(tipoPersonal, selectedTipo, selectedCargo);
                }
            }

            async function cargarCargos(tipoPersonal, tipoCargo, selectedCargo = '') {
                showMsg(msgCargo, null);
                cargoSelect.disabled = true;

                const cargosList = await fetchCargosPorTipo(tipoPersonal, tipoCargo);
                setOptionsSimple(cargoSelect, placeholderCargo, cargosList, selectedCargo);

                if (cargosList.length === 0) {
                    showMsg(msgCargo, 'No hay cargos para el tipo de cargo seleccionado.');
                    cargoSelect.disabled = true;
                    return;
                }

                cargoSelect.disabled = false;
            }

            tipoPersonalSelect.addEventListener('change', () => {
                const tp = tipoPersonalSelect.value;
                if (!tp) {
                    setOptionsSimple(tipoCargoSelect, placeholderTipo, [], '');
                    setOptionsSimple(cargoSelect, placeholderCargo, [], '');
                    tipoCargoSelect.disabled = true;
                    cargoSelect.disabled = true;
                    showMsg(msgTipo, null);
                    showMsg(msgCargo, null);
                    return;
                }
                cargarTiposYResetCargos(tp);
            });

            tipoCargoSelect.addEventListener('change', () => {
                const tp = tipoPersonalSelect.value;
                const tc = tipoCargoSelect.value;
                if (!tp || !tc) {
                    setOptionsSimple(cargoSelect, placeholderCargo, [], '');
                    cargoSelect.disabled = true;
                    showMsg(msgCargo, null);
                    return;
                }
                cargarCargos(tp, tc);
            });

            // estado inicial
            const initialTipoPersonal = tipoPersonalSelect.value;
            const initialTipoCargo = tipoCargoSelect.value;
            const initialCargo = cargoSelect.value;

            if (initialTipoPersonal) {
                cargarTiposYResetCargos(initialTipoPersonal, initialTipoCargo, initialCargo);
            }
        }


        /* ==================== MODAL DE EDICIÓN ==================== */

        async function openEditModal(url, triggerEl = null) {
            editTriggerEl = triggerEl || document.activeElement;

            // Skeleton
            editContent.innerHTML = `
            <div class="p-6 space-y-3">
                <div class="h-6 w-40 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-4 w-3/4 bg-gray-200 rounded animate-pulse"></div>
                <div class="h-32 w-full bg-gray-200 rounded animate-pulse"></div>
            </div>`;

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

                // Inicializar lógica del modal (dependencias + toggles)
                initEditModal(editContent);

                const firstEl = editContent.querySelector('[autofocus], input, select, textarea, button');
                if (firstEl) firstEl.focus();

                editModal.addEventListener('mousedown', onEditBackdropClick);
                document.addEventListener('keydown', onEditEsc);
            } catch (e) {
                console.error(e);
                editContent.innerHTML = `<div class="p-6 text-sm text-red-600">No se pudo cargar el formulario.</div>`;
            }
        }

        function closeEditModal() {
            if (editPanel) editPanel.setAttribute('data-open', 'false');

            document.removeEventListener('keydown', onEditEsc);
            editModal.removeEventListener('mousedown', onEditBackdropClick);
            document.body.style.overflow = '';

            setTimeout(() => {
                editModal.classList.add('hidden');
                editModal.classList.remove('flex');
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

        // Conectar botones "Editar"
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                openEditModal(`/requerimientos/${id}/edit`, btn);
            });
        });

        // Submit AJAX del form dentro del modal
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
                            closeEditModal();
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

        /* ========== Inicialización interna del MODAL (incluye tus 2 cadenas pedidas) ========== */

        function initEditModal(root) {
            if (!root) return;

            const tipoCargoSelect = root.querySelector('#tipo_cargo_edit');
            const cargoSelect = root.querySelector('#cargo_edit');

            const depaSelect = root.querySelector('#departamento_edit');
            const provSelect = root.querySelector('#provincia_edit');
            const distSelect = root.querySelector('#distrito_edit');

            const tipoPersonalSel = root.querySelector('#tipo_personal_edit');
            const camposOperativoEl = root.querySelector('#campos-operativo-edit');

            const sucursalEdit = root.querySelector('#sucursal_edit');
            const clienteEdit = root.querySelector('#cliente_edit');

            // Campos comunes de perfil (operativo + administrativo)
            const edadMin = root.querySelector('#edad_minima_edit');
            const edadMax = root.querySelector('#edad_maxima_edit');
            const expMin = root.querySelector('#experiencia_minima_edit');
            const grado = root.querySelector('#grado_academico_edit');

            // Campos para urgencia automática
            // Campos para urgencia automática
            const fechaInicioEdit = root.querySelector('#fecha_inicio_edit');
            const fechaFinEdit = root.querySelector('#fecha_fin_edit');
            const urgenciaSelect = root.querySelector('#urgencia_edit');
            const urgenciaDiv = root.querySelector('#urgencia_auto_edit'); // pequeño texto debajo del select (opcional)

            /*
            const fechaInicioEdit = root.querySelector('#fecha_inicio_edit');
            const fechaFinEdit = root.querySelector('#fecha_fin_edit');
            const urgenciaSelect = root.querySelector('#urgencia_edit');
            const urgenciaLabel = root.querySelector('#urgencia_auto_edit'); // opcional, solo si lo creas
            const baseUrgClasses = urgenciaSelect ? urgenciaSelect.className : '';
            /*

            /* ================== Sucursal → Cliente (modal) ================== */
            if (sucursalEdit && clienteEdit) {
                const msgEl = ensureMsgEl(clienteEdit, 'cliente-edit');
                const clienteInicial = clienteEdit.dataset.value || clienteEdit.value || '';

                if (sucursalEdit.value) {
                    loadClientesPorSucursalEnSelect(
                        sucursalEdit.value,
                        clienteEdit,
                        'Selecciona cliente',
                        clienteInicial,
                        msgEl
                    );
                }

                sucursalEdit.addEventListener('change', () => {
                    loadClientesPorSucursalEnSelect(
                        sucursalEdit.value,
                        clienteEdit,
                        'Selecciona cliente',
                        '',
                        msgEl
                    );
                });
            }

            /* ===== TipoPersonal → TipoCargo → CargoSolicitado (modal) ===== */
            if (tipoPersonalSel && tipoCargoSelect && cargoSelect) {
                initTipoPersonalChain(tipoPersonalSel, tipoCargoSelect, cargoSelect);
            }

            /* ===== Ubicación (Departamento → Provincia → Distrito) ===== */
            const setOptions = (select, list, placeholder = 'Selecciona…') => {
                if (!select) return;
                select.innerHTML = `<option value="">${placeholder}</option>`;
                list.forEach(it => {
                    const opt = document.createElement('option');
                    opt.value = String(it.value ?? '');
                    opt.textContent = String(it.label ?? '');
                    select.appendChild(opt);
                });
            };

            const fillProvincias = (depa, preselect = null) => {
                if (!provSelect) return;
                const d = padDigits(depa, 2);
                const list = provincias
                    .filter(p => padDigits(p.DEPA_CODIGO, 2) === d)
                    .map(p => ({
                        value: padDigits(p.PROVI_CODIGO, 4),
                        label: p.PROVI_DESCRIPCION
                    }));
                setOptions(provSelect, list, 'Selecciona…');
                if (preselect) provSelect.value = padDigits(preselect, 4);
            };

            const fillDistritos = (prov, preselect = null) => {
                if (!distSelect) return;
                const p = padDigits(prov, 4);
                const list = distritos
                    .filter(d => padDigits(d.PROVI_CODIGO, 4) === p)
                    .map(d => ({
                        value: padDigits(d.DIST_CODIGO, 6),
                        label: d.DIST_DESCRIPCION
                    }));
                setOptions(distSelect, list, 'Selecciona…');
                if (preselect) distSelect.value = padDigits(preselect, 6);
            };

            if (depaSelect && provSelect && distSelect) {
                fillProvincias(depaSelect.value, provSelect.dataset.value || null);
                fillDistritos(provSelect.value, distSelect.dataset.value || null);

                depaSelect.addEventListener('change', () => {
                    fillProvincias(depaSelect.value, null);
                    setOptions(distSelect, [], 'Selecciona…');
                });

                provSelect.addEventListener('change', () => {
                    fillDistritos(provSelect.value, null);
                });
            }

            /* ===== Toggle Operativo / Administrativo ===== */
            const togglePerfilPorTipoPersonal = () => {
                if (!tipoPersonalSel) return;

                const tipo = String(tipoPersonalSel.value);
                const esOperativo = tipo === '01';
                const esAdmin = tipo === '02';

                const comunesRequired = esOperativo || esAdmin;
                [edadMin, edadMax, expMin, grado].forEach(el => {
                    if (!el) return;
                    el.required = comunesRequired;
                });

                if (camposOperativoEl) {
                    camposOperativoEl.style.display = esOperativo ? '' : 'none';
                    camposOperativoEl
                        .querySelectorAll('select, input, textarea')
                        .forEach(el => {
                            el.required = esOperativo;
                        });
                }
            };

            if (tipoPersonalSel) {
                tipoPersonalSel.addEventListener('change', togglePerfilPorTipoPersonal);
                togglePerfilPorTipoPersonal(); // inicial
            }

            /* ===== Urgencia automática según fechas (solo modal) ===== */
            if (fechaInicioEdit && fechaFinEdit && urgenciaSelect) {

                // Hacer que NO sea editable, pero que igual se envíe en el form
                urgenciaSelect.style.pointerEvents = 'none'; // no se puede abrir con el mouse
                urgenciaSelect.addEventListener('mousedown', e => e.preventDefault());
                urgenciaSelect.addEventListener('keydown', e => e.preventDefault());
                urgenciaSelect.style.backgroundColor = '#f3f4f6'; // estilo de "solo lectura"
                urgenciaSelect.style.color = '#4b5563';

                function setUrgencia(valor, texto, colorClass) {
                    // Mensaje visual debajo del select (si existe)
                    if (urgenciaDiv) {
                        urgenciaDiv.textContent = texto;
                        urgenciaDiv.className =
                            'mt-1 text-xs font-semibold rounded px-2 py-1 inline-block ' + colorClass;
                    }

                    // Valor real que se enviará en el formulario
                    // (si quieres guardar "Invalida" cámbialo por valor directamente)
                    if (valor === 'Invalida') {
                        urgenciaSelect.value = '';
                    } else {
                        urgenciaSelect.value = valor || '';
                    }
                }

                function calcularUrgencia() {
                    if (fechaInicioEdit.value && fechaFinEdit.value) {
                        const inicio = new Date(fechaInicioEdit.value);
                        const fin = new Date(fechaFinEdit.value);
                        const diffMs = fin - inicio;
                        const diffDias = diffMs / (1000 * 60 * 60 * 24);

                        if (isNaN(diffDias) || diffDias < 0) {
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
                fechaInicioEdit.addEventListener('change', calcularUrgencia);
                fechaFinEdit.addEventListener('change', calcularUrgencia);

                // Inicializar al abrir el modal (por si ya trae fechas)
                calcularUrgencia();
            }

        }
    </script>

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

        /* Estilos para el Modal de Edición - Mejorados */
        #edit-modal-content {
            background-color: #ffffff;
            color: #111827;
        }

        #edit-modal-content h3 {
            color: #111827;
            font-weight: 700;
            font-size: 0.875rem;
        }

        #edit-modal-content input[type="text"],
        #edit-modal-content input[type="number"],
        #edit-modal-content input[type="date"],
        #edit-modal-content select,
        #edit-modal-content textarea {
            background-color: #ffffff !important;
            color: #111827 !important;
            border: 1px solid #d1d5db !important;
            transition: border-color 0.2s;
        }

        #edit-modal-content input[type="text"]:focus,
        #edit-modal-content input[type="number"]:focus,
        #edit-modal-content input[type="date"]:focus,
        #edit-modal-content select:focus,
        #edit-modal-content textarea:focus {
            border-color: #3b82f6 !important;
            outline: none;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #edit-modal-content select option {
            background-color: #ffffff;
            color: #111827;
            padding: 0.5rem;
        }

        #edit-modal-content input[type="checkbox"] {
            cursor: pointer;
            width: 1rem;
            height: 1rem;
            accent-color: #3b82f6;
        }

        #edit-modal-content label {
            color: #374151;
            font-weight: 500;
            font-size: 0.875rem;
        }

        #edit-modal-content h2 {
            color: #111827;
            font-weight: 700;
        }

        #edit-modal-content .text-red-600 {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }

        #edit-modal-content button {
            color: white;
            font-weight: 600;
            font-size: 0.875rem;
            padding: 0.5rem 1rem;
            transition: all 0.2s;
        }

        #edit-modal-content button[type="submit"] {
            background-color: #3b82f6;
            box-shadow: 0 1px 3px rgba(59, 130, 246, 0.3);
        }

        #edit-modal-content button[type="submit"]:hover {
            background-color: #2563eb;
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
        }

        #edit-modal-content button[type="button"] {
            background-color: #d1d5db;
            color: #111827;
        }

        #edit-modal-content button[type="button"]:hover {
            background-color: #9ca3af;
            color: white;
        }

        #edit-modal-content .btn-primary {
            background-color: #3b82f6;
            color: white;
        }

        #edit-modal-content .btn-primary:hover {
            background-color: #2563eb;
        }
    </style>

@endsection
