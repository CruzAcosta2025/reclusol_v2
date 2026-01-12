@extends('layouts.app')

@section('module', 'entrevistas')

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
        /* texto oscuro */
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
        <div class="card glass-strong p-6 shadow-soft">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0">
                    <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">
                        Entrevistas
                    </h2>
                    <p class="text-sm text-white/70 mt-1">
                        Busque postulantes por DNI o nombre
                    </p>
                </div>
                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('postulantes.filtrar') }}"
                        class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                        <i class="fas fa-list mr-2"></i>Ver Postulantes
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                        <i class="fas fa-gauge-high mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>


    {{-- Filtros --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <form id="filter-form" method="GET" action="{{ route('entrevistas.index') }}"
            class="panel-light grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">DNI</label>
                <input type="text" name="dni" value="{{ request('dni') }}" placeholder="Ingrese DNI"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/90 text-gray-900 transition-colors">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                <input type="text" name="nombre" value="{{ request('nombre') }}" placeholder="Nombre o Apellido"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/90 text-gray-900 transition-colors">
            </div>
            <div class="flex items-end">
                <div class="flex space-x-2 w-full">
                    <button type="submit"
                        class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-search"></i>
                        <span>Buscar</span>
                    </button>
                    <button type="button" onclick="limpiarFiltros()"
                        class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#ecfdf5;">
                    <i class="fas fa-hourglass-half text-green-600 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-gray-500">En proceso</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $postulantes->count() }}</div>
                </div>
            </div>

            <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fef2f2;">
                    <i class="fas fa-check-circle text-red-600 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-gray-500">Entrevistados</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $entrevistados ?? 0 }}</div>
                </div>
            </div>

            <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#eff6ff;">
                    <i class="fas fa-times-circle text-blue-600 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-gray-500">Cancelados</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $cancelados ?? 0 }}</div>
                </div>
            </div>

            <div class="panel-light p-5 rounded-2xl flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl grid place-items-center" style="background:#fffbeb;">
                    <i class="fas fa-clock text-yellow-600 text-lg"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-semibold text-gray-500">Pendientes</div>
                    <div class="text-2xl font-extrabold text-gray-900">{{ $pendientes ?? $postulantes->count() }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ALERTA LISTA NEGRA --}}
    @if ($listaNegra && $listaNegra->count() > 0)
    @foreach ($listaNegra as $item)
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-4">
        <div
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-xl shadow-md flex items-center">
            <i class="fas fa-ban text-red-500 text-2xl mr-4"></i>
            <div>
                <span class="font-bold">¡ATENCIÓN!</span>
                El postulante
                <span class="font-semibold">{{ $item->PERSONAL ?? $item->nombre_completo }}</span>
                (DNI: <span class="font-mono">{{ $item->NRO_DOCU_IDEN ?? $item->dni }}</span>)
                se encuentra en la <span class="font-bold">lista negra</span> de la empresa.
                <br>
                Motivo de cese: <span class="italic">{{ $item->MOCE_DESCRIPCION ?? $item->motivo_cese }}</span>
                @if (!empty($item->FEC_CESE ?? $item->fecha_cese))
                <br>Fecha de cese:
                {{ \Carbon\Carbon::parse($item->FEC_CESE ?? $item->fecha_cese)->format('d/m/Y') }}
                @endif
            </div>
        </div>
    </div>
    @endforeach
    @endif


    {{-- Tabla de postulantes --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
        <div class="bg-white rounded-2xl shadow border">
            {{-- Encabezado de tabla --}}
            <div
                class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 flex justify-between items-center rounded-t-2xl">
                <h2 class="flex items-center text-lg font-semibold">
                    <i class="fas fa-list mr-2"></i>
                    Listado de Postulantes en Proceso
                </h2>
                <span class="text-sm opacity-80">
                    {{ $postulantes->count() }} postulantes encontrados
                </span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Nombre completo</th>
                            {{--
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">DNI</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Edad</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Departamento</th>
                            --}}

                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Cargo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Fecha de entrevista</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Evaluado por</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Estado</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($postulantes as $postulante)
                        <tr class="hover:bg-blue-50 transition-colors"
                            data-postulante-id="{{ $postulante->id }}"
                            data-dni="{{ strtolower($postulante->dni) }}"
                            data-nombre="{{ strtolower($postulante->nombres . ' ' . $postulante->apellidos) }}">
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-gray-900">{{ $postulante->nombres }}
                                        {{ $postulante->apellidos }}
                                    </p>
                                </div>
                            </td>

                            {{--
                                     <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded text-xs font-mono bg-gray-100 text-gray-800">
                                            {{ $postulante->dni }}
                            </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $postulante->edad }} años
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-sm text-gray-900">{{ $postulante->departamento_nombre }}</span>
                            </td>
                            --}}


                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    {{ $postulante->cargo_nombre ?? ($postulante->cargo_nombre ?? 'N/A') }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if(!empty($postulante->fecha_entrevista))
                                <span class="text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($postulante->fecha_entrevista)->format('d/m/Y H:i') }}
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Sin programar
                                </span>
                                @endif
                            </td>


                            <td class="px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ ($postulante->estado_entrevista ?? 'No evaluado') === 'No evaluado' ? 'bg-gray-100 text-gray-600' : 'bg-indigo-100 text-indigo-800' }}">
                                    {{ $postulante->evaluado_por ?? 'Aún no evaluado' }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @php
                                $cod = $postulante->estado_agenda_codigo ?? 'SIN_PROGRAMAR';
                                $nombre = $postulante->estado_agenda_nombre ?? 'Sin programar';

                                $classes = [
                                'SIN_PROGRAMAR' => 'bg-gray-100 text-gray-600',
                                'PROGRAMADA' => 'bg-blue-100 text-blue-800',
                                'REPROGRAMADA' => 'bg-indigo-100 text-indigo-800',
                                'NO_ASISTIO' => 'bg-orange-100 text-orange-800',
                                'CANCELADA' => 'bg-red-100 text-red-800',
                                'CERRADA' => 'bg-gray-200 text-gray-800',
                                'ENTREVISTADA' => 'bg-green-100 text-green-800',
                                ];
                                $cls = $classes[$cod] ?? 'bg-gray-100 text-gray-600';
                                @endphp

                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $cls }}">
                                    {{ $nombre }}
                                </span>
                            </td>


                            <td class="px-6 py-4">
                                @php
                                $agenda = $postulante->estado_agenda_codigo ?? 'SIN_PROGRAMAR';
                                $bloqueado = in_array($agenda, ['CANCELADA','CERRADA'], true);
                                @endphp

                                <div class="acciones flex items-center justify-center space-x-2">

                                    {{-- ENTREVISTAR --}}
                                    @if(!$bloqueado)
                                    <a href="{{ route('entrevistas.evaluar', $postulante->id) }}"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                        title="Entrevistar">
                                        <i class="fas fa-comments"></i>
                                    </a>
                                    @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-400"
                                        title="Entrevista {{ strtolower($postulante->estado_agenda_nombre ?? 'bloqueada') }}">
                                        <i class="fas fa-comments"></i>
                                    </span>
                                    @endif

                                    {{-- PROGRAMAR / REPROGRAMAR --}}
                                    @if(!$bloqueado)
                                    <button type="button"
                                        onclick="openProgramarModal({{ $postulante->id }}, @js($postulante->fecha_entrevista))"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Programar / Reprogramar">
                                        <i class="fas fa-calendar"></i>
                                    </button>
                                    @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-400"
                                        title="Bloqueado">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    @endif

                                    {{-- CERRAR --}}
                                    @if(!$bloqueado)
                                    <button type="button"
                                        onclick="cerrarFila({{ $postulante->id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 transition"
                                        title="Cerrar">
                                        <i class="fas fa-lock"></i>
                                    </button>
                                    @else
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-500"
                                        title="Ya cerrada">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    @endif

                                    {{-- ELIMINAR BORRADOR --}}
                                    @if(!$bloqueado && ($postulante->estado_entrevista ?? '') === 'Borrador' && !empty($postulante->entrevista_id))
                                    <button type="button"
                                        onclick="deleteEntrevista({{ $postulante->entrevista_id }})"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                        title="Eliminar borrador">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                    <p class="text-gray-500 text-lg">No hay postulantes en proceso</p>
                                    <p class="text-gray-400 text-sm">Ajusta los filtros de búsqueda</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- Paginación --}}
            @if ($postulantes->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $postulantes->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Modal de Visualización --}}
    <div id="view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" id="view-modal-content">
            {{-- Contenido del modal se carga aquí --}}
        </div>
    </div>

    {{-- Modal de Edición --}}
    <div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" id="edit-modal-content">
            {{-- Contenido del modal se carga aquí --}}
        </div>
    </div>

    {{-- Modal de Eliminación --}}
    <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-trash text-red-600 text-2xl"></i>
                </div>

                <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Eliminar borrador de entrevista?</h3>

                <p class="text-sm text-gray-600 mb-6">
                    Se eliminará <span class="font-semibold">solo el borrador</span> de la evaluación.
                    <br>
                    El postulante <span class="font-semibold">no</span> será eliminado.
                </p>

                <div class="flex space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                        Cancelar
                    </button>

                    <button type="button" onclick="confirmDelete()"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Eliminar borrador
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    let deleteEntrevistaId = null;

    // ------ Filtros y tabla ------
    function limpiarFiltros() {
        document.getElementById('filter-form').reset();
        window.location.href = window.location.pathname;
    }

    // ------ Filtrado en tiempo real ------
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

    // ------ Funciones de modales ------
    function viewPostulante(id) {
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/entrevistas/${id}/view`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('view-modal-content').innerHTML = html;
                document.getElementById('view-modal').classList.remove('hidden');
                Swal.close();
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los detalles del postulante',
                    confirmButtonColor: '#ef4444',
                });
            });
    }

    function editPostulante(id) {
        Swal.fire({
            title: 'Cargando...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        fetch(`/entrevistas/${id}/edit`)
            .then(response => response.text())
            .then(html => {
                document.getElementById('edit-modal-content').innerHTML = html;
                document.getElementById('edit-modal').classList.remove('hidden');
                Swal.close();
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar el formulario de edición',
                    confirmButtonColor: '#ef4444',
                });
            });
    }


    function deleteEntrevista(id) {
        deleteEntrevistaId = id;
        Swal.fire({
            title: '¿Eliminar Borrador?',
            text: 'Se eliminará solo el borrador de la evaluación. El postulante seguirá en el sistema.',
            icon: 'warning',
            iconColor: '#ef4444',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Eliminando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/entrevistas/borrador/${deleteEntrevistaId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Borrador Eliminado!',
                                text: 'El borrador ha sido eliminado correctamente.',
                                confirmButtonColor: '#10b981',
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Error al eliminar el borrador',
                                confirmButtonColor: '#ef4444',
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Error al eliminar el borrador',
                            confirmButtonColor: '#ef4444',
                        });
                    })
                    .finally(() => {
                        closeDeleteModal();
                    });
            }
        });
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteEntrevistaId = null;
    }

    function confirmDelete() {
        if (deleteEntrevistaId) {
            fetch(`/entrevistas/borrador/${deleteEntrevistaId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Error al eliminar al borrador');
                    }
                })
                .catch(error => {
                    alert('Error al eliminar al borrador');
                })
                .finally(() => {
                    closeDeleteModal();
                });
        }
    }

    // ------ Cerrar modales al hacer clic fuera ------
    document.addEventListener('click', function(event) {
        const modals = ['view-modal', 'edit-modal'];
        modals.forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal && event.target === modal) {
                modal.classList.add('hidden');
            }
        });
    });
    // ------ Programar/Reprogramar y Cambiar Estado ------
    let programarPostulanteId = null;

    function openProgramarModal(postulanteId, fechaActual) {
        programarPostulanteId = postulanteId;

        // Formatear fecha actual si existe
        let fechaFormateada = '';
        if (fechaActual) {
            const fecha = new Date(fechaActual);
            const año = fecha.getFullYear();
            const mes = String(fecha.getMonth() + 1).padStart(2, '0');
            const día = String(fecha.getDate()).padStart(2, '0');
            const horas = String(fecha.getHours()).padStart(2, '0');
            const minutos = String(fecha.getMinutes()).padStart(2, '0');
            fechaFormateada = `${año}-${mes}-${día}T${horas}:${minutos}`;
        }

        Swal.fire({
            title: 'Programar Entrevista',
            html: `
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 text-left">Fecha y Hora:</label>
                        <input type="datetime-local" id="fecha-entrevista" 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0"
                            value="${fechaFormateada}">
                    </div>
                    <p class="text-xs text-gray-500 text-left">Selecciona la fecha y hora para la entrevista</p>
                </div>
            `,
            icon: 'info',
            iconColor: '#3b82f6',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Programar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                const input = document.getElementById('fecha-entrevista');
                const fecha = input.value;

                if (!fecha) {
                    Swal.showValidationMessage('Por favor selecciona una fecha y hora');
                    return false;
                }

                return fecha;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const fechaSeleccionada = result.value;

                Swal.fire({
                    title: 'Guardando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/entrevistas/${postulanteId}/programar`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            fecha_entrevista: fechaSeleccionada
                        })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Entrevista Programada!',
                                text: 'La entrevista se ha programado correctamente.',
                                confirmButtonColor: '#10b981',
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: d.message || 'Error al programar la entrevista',
                                confirmButtonColor: '#ef4444',
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al programar la entrevista',
                            confirmButtonColor: '#ef4444',
                        });
                    });
            }
        });
    }

    function cambiarEstado(postulanteId, estado) {
        let titulo = '';
        let mensaje = '';
        let iconoColor = '';
        let confirmColor = '';

        if (estado === 'CANCELADA') {
            titulo = '¿Cancelar Entrevista?';
            mensaje = 'La entrevista será marcada como cancelada. El postulante seguirá en el sistema.';
            iconoColor = '#ef4444';
            confirmColor = '#ef4444';
        } else if (estado === 'CERRADA') {
            titulo = '¿Cerrar Entrevista?';
            mensaje = 'La entrevista será cerrada. No podrás hacer cambios después. Se inhabilitarán los demás botones.';
            iconoColor = '#6b7280';
            confirmColor = '#6b7280';
        }

        Swal.fire({
            title: titulo,
            text: mensaje,
            icon: 'warning',
            iconColor: iconoColor,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Confirmar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Procesando...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                fetch(`/entrevistas/${postulanteId}/estado`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            estado
                        })
                    })
                    .then(r => r.json())
                    .then(d => {
                        if (d.success) {
                            // Si se cierra la entrevista, inhabilitar los botones
                            if (estado === 'CERRADA') {
                                const row = document.querySelector(`tr[data-postulante-id="${postulanteId}"]`);
                                if (row) {
                                    const buttons = row.querySelectorAll('button, a[onclick]');
                                    buttons.forEach(btn => {
                                        // Deshabilitar el botón
                                        btn.disabled = true;
                                        btn.style.opacity = '0.5';
                                        btn.style.cursor = 'not-allowed';
                                        btn.style.pointerEvents = 'none';

                                        // Cambiar estilos para que se vea deshabilitado
                                        btn.classList.add('opacity-50', 'cursor-not-allowed');
                                        btn.title = 'Entrevista cerrada';
                                    });
                                }
                            }

                            Swal.fire({
                                icon: 'success',
                                title: '¡Cambio Guardado!',
                                text: `El estado de la entrevista ha sido actualizado a ${estado.toLowerCase()}.`,
                                confirmButtonColor: '#10b981',
                            }).then(() => {
                                if (estado !== 'CERRADA') {
                                    window.location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: d.message || 'Error al cambiar el estado',
                                confirmButtonColor: '#ef4444',
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al cambiar el estado',
                            confirmButtonColor: '#ef4444',
                        });
                    });
            }
        });
    }

    function disableRowActions(postulanteId) {
        const row = document.getElementById(`row-postulante-${postulanteId}`);
        if (!row) return;

        const els = row.querySelectorAll('.acciones button, .acciones a');
        els.forEach(el => {
            if (el.tagName === 'A') el.removeAttribute('href');
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.45';
            el.style.cursor = 'not-allowed';
            if (el.tagName === 'BUTTON') el.disabled = true;
        });
    }

    function cerrarFila(postulanteId) {
        // deshabilita al toque
        disableRowActions(postulanteId);

        // luego cambia estado en backend
        cambiarEstado(postulanteId, 'CERRADA');
    }
</script>

{{-- Estilos adicionales --}}
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .card-hover {
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
    }
</style>
@endsection