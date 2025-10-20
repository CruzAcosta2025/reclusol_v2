@extends('layouts.app')

@section('content')
<div class="min-h-screen gradient-bg py-8 pt-24">
    {{-- Botón volver --}}
    <a href="{{ route('dashboard') }}"
        class="absolute top-6 left-6 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-lg transition-colors flex items-center space-x-3 px-6 py-3 text-lg z-10 group">
        <i class="fas fa-arrow-left text-2xl group-hover:-translate-x-1 transition-transform"></i>
        <span class="font-bold">Volver al Dashboard</span>
    </a>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        {{-- Encabezado --}}
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h1 class="text-3xl font-bold text-gray-800">Listado de Postulantes</h1>
            <p class="text-gray-600 mt-1">Ver y filtrar postulantes según diferentes criterios</p>
        </div>
    </div>

    {{-- Filtro --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <form id="filter-form" method="GET"
            class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" value="{{ request('dni') }}" placeholder="Ingrese DNI"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ request('nombre') }}" placeholder="Nombre o Apellido"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 transition-colors">
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Aptos --}}
            <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-check-circle fa-2x text-green-500 mb-2"></i>
                <p class="text-sm text-gray-600">Postulantes Aptos</p>
                <p class="text-2xl font-bold">{{ $stats['aptos'] ?? 0 }}</p>
            </div>
            {{-- No Aptos --}}
            <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fas fa-times-circle fa-2x text-red-500 mb-2"></i>
                <p class="text-sm text-gray-600">Postulantes No Aptos</p>
                <p class="text-2xl font-bold">{{ $stats['no_aptos'] ?? 0 }}</p>
            </div>

            {{-- Total --}}
            <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                <i class="fa-solid fa-users fa-2x text-blue-500 mb-2"></i>
                <p class="text-sm text-gray-600">Total Postulantes</p>
                <p class="text-2xl font-bold">{{ $stats['total'] ?? 0 }}</p>
            </div>
        </div>
    </div>


    {{-- Resultados --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
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
            <table class="w-full">
                <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                    <tr class="text-left">
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Cargo</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Distrito</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nombres y Apellidos
                        </th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Edad</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">DNI</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nacionalidad</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Experiencia</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">SUCAMEC</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Grado de Instrucción</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Celular</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Arma L4</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Conducir A1</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">CV</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">CUL</th>
                        <!-- <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Estado</th> -->
                        <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($postulantes as $postulante)
                    <tr
                        class="hover:bg-blue-50 transition
                            @if ($postulante->decision === 'apto') bg-green-100
                            @elseif ($postulante->decision === 'no_apto') bg-red-100 @endif
                            ">
                        <td class="px-6 py-4">
                            <p class="text-gray-700 text-center">{{ $postulante->cargo_nombre }}</p>
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
                            <p class="text-gray-700 text-center">{{ ucfirst($postulante->edad) }} años</p>
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
                            <p class="text-gray-700 text-center">{{ $postulante->sucamec }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-gray-700 text-center">{{ $postulante->grado_instruccion }}</p>
                        </td>

                        <td class="px-6 py-4">
                            <p class="text-gray-700 text-center">{{ $postulante->celular }}</p>
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $postulante->licencia_arma ?: '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            {{ $postulante->licencia_conducir ?: '-' }}
                        </td>


                        {{-- <td class="px-6 py-4 text-center">
                            {{ $postulante->licencia_arma ? implode(', ', $postulante->licencia_arma) : '-' }}
                        </td>

                        <td class="px-6 py-4 text-center">
                            {{ $postulante->licencia_conducir ? implode(', ', $postulante->licencia_conducir) : '-' }}
                        </td> --}}

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


                        <td class="h-full">
                            <div class="flex flex-col justify-center items-center gap-2 h-full">
                                <!-- Botón Validar -->
                                <button data-id="{{ $postulante->id }}"
                                    data-nombre="{{ $postulante->nombres }} {{ $postulante->apellidos }}"
                                    class="btn-validar flex items-center justify-center w-24 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                    title="Validar documentos">
                                    <i class="fa-solid fa-clipboard-check mr-2"></i> Validar
                                </button>

                                <!-- Botón Editar -->
                                <button data-id="{{ $postulante->id }}"
                                    class="btn-edit flex items-center justify-center w-24 py-1 rounded-lg bg-green-50 hover:bg-green-100 text-green-600 transition"
                                    title="Editar">
                                    <i class="fas fa-edit mr-2"></i> Editar
                                </button>

                                <!-- Botón Eliminar -->
                                <button onclick="eliminarPostulante({{ $postulante->id }})"
                                    class="flex items-center justify-center w-24 py-1 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 transition"
                                    title="Eliminar">
                                    <i class="fas fa-trash mr-2"></i> Eliminar
                                </button>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-6 text-center text-gray-500">No se encontraron
                            resultados.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                        <h3 id="delete-title" class="text-xl font-semibold">¿Eliminar postulante?</h3>
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

        {{-- Modal de Validación --}}
        <div id="validar-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
            role="dialog" aria-modal="true" aria-labelledby="validar-title" aria-describedby="validar-desc">
            <div
                class="bg-white p-6 rounded-2xl w-full max-w-md shadow-2xl border border-gray-100 mx-4
              focus:outline-none focus-visible:ring focus-visible:ring-emerald-200">

                <!-- Encabezado -->
                <h3 id="validar-title" class="text-lg font-semibold mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-emerald-600"></i>
                    Validar: <span id="val-nombre" class="font-normal text-gray-700"></span>
                </h3>
                <p id="validar-desc" class="sr-only">Selecciona el resultado de validación y agrega comentario si
                    corresponde.</p>

                <!-- Formulario -->
                <form id="form-validar" method="POST" class="space-y-4" autocomplete="off">
                    @csrf

                    <!-- Opciones de radio -->
                    <fieldset class="space-y-2">
                        <legend class="sr-only">Resultado</legend>
                        <label
                            class="flex items-center gap-2 p-2 rounded-lg border hover:bg-gray-50 cursor-pointer transition">
                            <input type="radio" name="decision" value="apto" class="accent-emerald-600" checked>
                            <span class="text-gray-800 font-medium">Apto</span>
                        </label>
                        <label
                            class="flex items-center gap-2 p-2 rounded-lg border hover:bg-gray-50 cursor-pointer transition">
                            <input type="radio" name="decision" value="no_apto" class="accent-red-600">
                            <span class="text-gray-800 font-medium">No apto</span>
                        </label>
                    </fieldset>

                    <!-- Comentario -->
                    <div>
                        <label for="comentario-no-apto" class="block text-sm font-medium text-gray-600 mb-1">
                            Comentario <span class="text-gray-400">(obligatorio si es No apto)</span>
                        </label>
                        <textarea name="comentario" id="comentario-no-apto" rows="3" maxlength="300"
                            class="w-full border border-gray-300 rounded-lg p-2 placeholder:text-gray-400
                 focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition resize-none"
                            placeholder="Motivo o notas (máx. 300 caracteres)"></textarea>
                    </div>

                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-100">
                        <button type="button" onclick="cerrarValidar()"
                            class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 text-gray-700
                       focus:outline-none focus:ring focus:ring-gray-300 transition">
                            Cancelar
                        </button>
                        <button type="submit"
                            class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white
                       focus:outline-none focus:ring focus:ring-emerald-300 transition">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

{{-- Scripts --}}
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

    // Mantengo tu firma para compatibilidad con el onclick anterior
    function eliminarPostulante(id) {
        openDeleteModal(id);
    }

    function closeDeleteModal() {
        deletePostulanteId = null;

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

        // Estado de carga
        const prevEliminarText = btnEliminar?.textContent;
        btnEliminar.disabled = true;
        btnCancelar.disabled = true;
        btnEliminar.textContent = 'Eliminando…';

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

        // Skeleton mientras carga
        editContent.innerHTML = `
    <div class="p-6 space-y-3">
      <div class="h-6 w-40 bg-gray-200 rounded animate-pulse"></div>
      <div class="h-4 w-3/4 bg-gray-200 rounded animate-pulse"></div>
      <div class="h-32 w-full bg-gray-200 rounded animate-pulse"></div>
    </div>`;

        // Mostrar overlay + bloquear scroll
        editModal.classList.remove('hidden');
        editModal.classList.add('flex');
        document.body.style.overflow = 'hidden';

        // Animación de entrada del panel
        if (editPanel) {
            void editPanel.offsetWidth; // reflow
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

            // Foco en el primer campo útil
            const firstEl = editContent.querySelector('[autofocus], input, select, textarea, button');
            if (firstEl && typeof firstEl.focus === 'function') firstEl.focus();

            // Listeners de cierre UX
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
                            title: '¡Éxito!',
                            text: '¡Postulante actualizado correctamente!',
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

            // abrir modal
            const m = document.getElementById('validar-modal');
            m.classList.remove('hidden');
            m.classList.add('flex');
        });
    });

    function cerrarValidar() {
        const m = document.getElementById('validar-modal');
        m.classList.add('hidden');
        m.classList.remove('flex');
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

        if (!file) return; // usuario canceló

        if (file.size > maxBytes) {
            Swal.fire({
                icon: "error",
                title: "Archivo demasiado grande",
                text: `El archivo supera el límite de ${maxMB} MB. Por favor elige otro.`,
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
</script>
@endsection