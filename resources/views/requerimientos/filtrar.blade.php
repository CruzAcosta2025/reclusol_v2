@extends('layouts.app')

@section('content')
<!-- Header -->
<div class="min-h-screen gradient-bg py-8 pt-24">
    <a href="{{ route('dashboard') }}"
        class="absolute top-6 left-6 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-lg transition-colors flex items-center space-x-3 px-6 py-3 text-lg z-10 group">
        <i class="fas fa-arrow-left text-2xl group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold">Volver al Dashboard</span>
    </a>
    <!-- Hero Section -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        {{-- Encabezado --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800">
                Listado de Requerimientos
            </h1>
            <p class="text-gray-600 mt-1">
                Gestiona y supervisa todos los requerimientos laborales de manera eficiente
            </p>
        </div>
    </div>

    <!-- Filters Section -->

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-lg">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                <select name="sucursal" id="sucursal"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                    <option value="">Todos</option>
                    @foreach ($sucursales as $codigo => $desc) {{-- $desc es STRING --}}
                    <option value="{{ $codigo }}" @selected((string)request('sucursal')===(string)$codigo)>
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
                    @foreach ($clientes as $codigo => $desc) {{-- $desc es STRING --}}
                    <option value="{{ $codigo }}" @selected((string)request('sucursal')===(string)$codigo)>
                        {{ $desc }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Tipo de Cargo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                <select name="tipo_cargo" id="tipo_cargo" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                    <option value="">Todos</option>
                    @foreach ($tipoCargos as $codigo => $desc)
                    <option value="{{ $codigo }}" {{ (string)request('tipo_cargo') === (string)$codigo ? 'selected' : '' }}>
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

            {{-- Departamento --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                <select name="departamento" id="departamento"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                    <option value="">Todos</option>
                    @foreach ($departamentos as $codigo => $desc) {{-- $desc es STRING --}}
                    <option value="{{ $codigo }}" @selected((string)request('departamento')===(string)$codigo)>
                        {{ $desc }}
                    </option>
                    @endforeach
                </select>
            </div>


            {{-- Provincia --}}
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

            {{-- Distrito --}}
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
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-yellow-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-hourglass-half text-yellow-500 mb-2"></i>
                <h3 class="text-sm text-gray-600">En Proceso</h3>
                <p class="text-2xl font-bold">{{ $stats['en_validacion']?? 0 }} </p>
            </div>

            <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-check-circle text-green-500 mb-2"></i>
                <h3 class="text-sm text-gray-600">Cubiertos</h3>
                <p class="text-2xl font-bold">{{ $stats['aprobado'] ?? 0 }}</p>
            </div>

            <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-times-circle text-blue-500 mb-2"></i>
                <h3 class="text-sm text-gray-600">Cancelados</h3>
                <p class="text-2xl font-bold">{{ $stats['cancelado'] ?? 0 }}</p>
            </div>

            <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-clock text-red-500 mb-2"></i>
                <h3 class="text-sm text-gray-600">Vencidos</h3>
                <p class="text-2xl font-bold">{{ $stats['cerrado'] ?? 0 }} </p>
            </div>
        </div>
    </div>

    {{-- Resultados --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="bg-white rounded-2xl shadow border">
            {{-- Encabezado --}}
            <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 flex justify-between items-center rounded-t-2xl">
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
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Urgencia</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Estado</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Fecha Inicio</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Fecha Final</th>
                            <th class="px-6 py-4 text-center font-bold text-gray-800 uppercase text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($requerimientos as $requerimiento)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4 text-center">
                                {{ $requerimiento->tipo_personal_nombre }}
                            </td>
                            <td class="px-6 py-4 text-center">{{ $requerimiento->cargo_nombre }}</td>
                            <td class="px-6 py-4 text-center">{{ ucfirst($requerimiento->sucursal_nombre) }}</td>
                            <td class="px-6 py-4 text-center">{{ ucfirst($requerimiento->cliente_nombre) }}</td>
                            <td class="px-6 py-4 text-center">
                                @php
                                $urg = strtolower($requerimiento->urgencia ?? '');
                                $priorityColors = [
                                'alta' => 'bg-red-100 text-red-800',
                                'media' => 'bg-yellow-100 text-yellow-800',
                                'baja' => 'bg-green-100 text-green-800',
                                ];
                                $priorityClass = $priorityColors[$urg] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $priorityClass }}">
                                    {{ ucfirst($requerimiento->urgencia ?? 'N/A') }}
                                </span>

                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                $estadoNombre = $requerimiento->estado_nombre; // ya viene del controller
                                $statusColors = [
                                'en proceso' => 'bg-yellow-100 text-yellow-800',
                                'cubierto' => 'bg-green-100 text-green-800',
                                'cancelado' => 'bg-red-100 text-red-800',
                                'vencido' => 'bg-gray-200 text-gray-700',
                                ];
                                $statusClass = $statusColors[strtolower($estadoNombre ?? '')] ?? 'bg-gray-100 text-gray-600';
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm {{ $statusClass }}">
                                    {{ $estadoNombre ?? 'N/A' }}
                                </span>

                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $requerimiento->fecha_inicio ? $requerimiento->fecha_inicio->format('d/m/Y') : '—' }}
                            </td>

                            <td class="px-6 py-4 text-center">
                                {{ $requerimiento->fecha_fin ? $requerimiento->fecha_fin->format('d/m/Y') : '—' }}
                            </td>


                            <td class="px-4 py-3 flex space-x-2">
                                <a href="#"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                    title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button
                                    data-id="{{ $requerimiento->id }}"
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
                            <td colspan="9" class="text-center py-6 text-gray-500">No hay requerimientos.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Modal de Edición --}}
            <div id="edit-modal"
                class="hidden fixed inset-0 z-50 items-center justify-center bg-black/50 backdrop-blur-sm">
                <div id="edit-panel"
                    class="bg-white w-full max-w-3xl mx-4 rounded-2xl shadow-2xl border border-gray-100
              max-h-[90vh] overflow-y-auto
              opacity-0 scale-95 transition duration-200 ease-out
              data-[open=true]:opacity-100 data-[open=true]:scale-100">
                    <div id="edit-modal-content"><!-- aquí se inyecta el formulario --></div>
                </div>
            </div>


            {{-- Modal de Eliminación --}}
            <div id="delete-modal"
                class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
                role="dialog" aria-modal="true" aria-labelledby="delete-title" aria-describedby="delete-desc">
                <!-- Panel -->
                <div class="bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl border border-gray-100 p-6
           opacity-0 translate-y-2 transition-all duration-200 ease-out
           data-[open=true]:opacity-100 data-[open=true]:translate-y-0"
                    id="delete-panel" data-open="true">
                    <!-- Encabezado -->
                    <div class="flex items-start gap-3 mb-4">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-50">
                            <i class="fa-solid fa-triangle-exclamation text-red-600"></i>
                        </div>
                        <div>
                            <h3 id="delete-title" class="text-xl font-semibold">¿Eliminar requerimiento?</h3>
                            <p id="delete-desc" class="text-sm text-gray-600 mt-1">Esta acción no se puede deshacer.</p>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div class="mt-5 flex justify-end gap-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeDeleteModal()"
                            class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-800
               focus:outline-none focus:ring focus:ring-gray-300 transition">
                            Cancelar
                        </button>

                        <button type="button" onclick="confirmDelete()"
                            class="px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white
               focus:outline-none focus:ring focus:ring-red-300 transition">
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

    const editModal = document.getElementById('edit-modal');
    const editPanel = document.getElementById('edit-panel');
    const editContent = document.getElementById('edit-modal-content');
    let eaditTriggerEl = null;


    const cargos = Object.values(@json($cargos));
    const provincias = Object.values(@json($provincias));
    const distritos = Object.values(@json($distritos));

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

    // function closeDeleteModal() {
    //   deleteRequerimientoId = null;
    // document.getElementById('delete-modal').classList.add('hidden');
    //}

    /*
    function confirmDelete() {
        if (deleteRequerimientoId) {
            fetch(`/requerimientos/${deleteRequerimientoId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error al eliminar');
                    }
                })
                .catch(() => alert('Error al eliminar'));
        }
        closeDeleteModal();
    }
    */


    // Filtrar cargos dinámicamente en el filtro principal
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
        const provId = this.value.padStart(4, '0');
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

    // Funciones para el modal de edición
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
        // Animación de salida
        if (editPanel) editPanel.setAttribute('data-open', 'false');

        // Quitar listeners y devolver scroll
        document.removeEventListener('keydown', onEditEsc);
        editModal.removeEventListener('mousedown', onEditBackdropClick);
        document.body.style.overflow = '';

        // Ocultar tras la duración de la animación (200ms)
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

    // Conectar los botones "Editar" a la nueva función
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            openEditModal(`/requerimientos/${id}/edit`, btn);
        });
    });


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


    // Función global para inicializar el filtrado del modal de edición
    /*
    window.inicializarFiltroCargosModal = function() {
        const modal = document.getElementById('edit-modal');
        const tipoCargoSelect = modal.querySelector('#tipo_cargo');
        const cargoSelect = modal.querySelector('#cargo_solicitado');
        const cargoSeleccionado = cargoSelect.dataset.selected;

        function filtrarCargos(tipoCargoId, cargoSeleccionado = null) {
            cargoSelect.innerHTML = '<option value="">Selecciona un cargo</option>';

            const cargosFiltrados = cargos.filter(p => p.TIPO_CARG === tipoCargoId);

            cargosFiltrados.forEach(p => {
                const option = document.createElement('option');
                option.value = p.CODI_CARG;
                option.textContent = p.DESC_CARGO;
                if (cargoSeleccionado && cargoSeleccionado === p.CODI_CARG) {
                    option.selected = true;
                }
                cargoSelect.appendChild(option);
            });

            if (cargosFiltrados.length === 0) {
                const option = document.createElement('option');
                option.value = "";
                option.textContent = "No hay cargos para este tipo";
                cargoSelect.appendChild(option);
            }
        }

        // Evento de cambio
        tipoCargoSelect.addEventListener('change', function() {
            filtrarCargos(this.value);
        });

        // Filtrar al cargar si hay valor
        if (tipoCargoSelect.value) {
            filtrarCargos(tipoCargoSelect.value, cargoSeleccionado);
        }
    };

    // Función global para inicializar el filtrado dinámico de Departamento/Provincia/Distrito en el modal de edición
    window.inicializarFiltroUbicacionModal = function() {
        const modal = document.getElementById('edit-modal');

        const departamentoSelect = modal.querySelector('#departamento');
        const provinciaSelect = modal.querySelector('#provincia');
        const distritoSelect = modal.querySelector('#distrito');

        const provinciaSeleccionada = provinciaSelect.dataset.selected;
        const distritoSeleccionado = distritoSelect.dataset.selected;

        // Al cambiar Departamento
        departamentoSelect.addEventListener('change', function() {
            const depaId = this.value.padStart(2, '0');
            provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
            distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';

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

        // Al cambiar Provincia
        provinciaSelect.addEventListener('change', function() {
            const provId = this.value.padStart(2, '0');
            distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';

            if (provId) {
                const distritosFiltrados = distritos.filter(d => d.PROVI_CODIGO === provId);
                distritosFiltrados.forEach(d => {
                    const option = document.createElement('option');
                    option.value = d.DIST_CODIGO;
                    option.textContent = d.DIST_DESCRIPCION;
                    distritoSelect.appendChild(option);
                });
            }
        });

        // Si hay valores seleccionados al cargar, inicializar Provincias y Distritos
        if (departamentoSelect.value) {
            const depaId = departamentoSelect.value.padStart(2, '0');
            provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
            const provinciasFiltradas = provincias.filter(p => p.DEPA_CODIGO === depaId);
            provinciasFiltradas.forEach(p => {
                const option = document.createElement('option');
                option.value = p.PROVI_CODIGO;
                option.textContent = p.PROVI_DESCRIPCION;
                if (provinciaSeleccionada === p.PROVI_CODIGO) {
                    option.selected = true;
                }
                provinciaSelect.appendChild(option);
            });
        }

        if (provinciaSelect.value) {
            const provId = provinciaSelect.value.padStart(2, '0');
            distritoSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
            const distritosFiltrados = distritos.filter(d => d.PROVI_CODIGO === provId);
            distritosFiltrados.forEach(d => {
                const option = document.createElement('option');
                option.value = d.DIST_CODIGO;
                option.textContent = d.DIST_DESCRIPCION;
                if (distritoSeleccionado === d.DIST_CODIGO) {
                    option.selected = true;
                }
                distritoSelect.appendChild(option);
            });
        }
    };
    */

    /*
    // Abrir modal y cargar el form-edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            fetch(`/requerimientos/${id}/edit`)
                .then(res => res.text())
                .then(html => {
                    document.getElementById('edit-modal-content').innerHTML = html;
                    document.getElementById('edit-modal').classList.remove('hidden');
                    // Inicializar el filtrado dinámico
                    inicializarFiltroCargosModal();
                    inicializarFiltroUbicacionModal();
                });
        });
    });

    // Cerrar modal
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
    }

    // Capturar submit y enviar por AJAX
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
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error al actualizar');
                    }
                })
                .catch(err => {
                    console.error('Errores de validación:', err.errors || err);
                    alert('Hay errores de validación. Revisa la consola.');
                });
        }
    });
    */
</script>
@endsection