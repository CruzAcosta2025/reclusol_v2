@extends('layouts.app')

@section('content')
    <div class="space-y-4">

        {{-- Encabezado --}}
        <x-block class="flex flex-col">
            <h1 class="text-xl font-bold text-M2">Entrevistas</h1>
            <p class="text-M3 mt-1">Busque postulantes por DNI o nombre</p>
        </x-block>

        {{-- Filtros --}}
        <x-block class="flex flex-col">
            <form id="filter-form" method="GET" action="{{ route('entrevistas.index') }}"
                class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
        </x-block>

        {{-- Estadísticas --}}
        <x-block class="flex flex-col">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- En Proceso --}}
                <div class="bg-yellow-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-hourglass-half text-yellow-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">En Proceso</h3>
                    <p class="text-2xl font-bold">{{ $postulantes->count() }}</p>
                </div>
                {{-- Entrevistados --}}
                <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-check-circle text-green-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Entrevistados</h3>
                    <p class="text-2xl font-bold">{{ $entrevistados ?? 0 }}</p>
                </div>
                {{-- Cancelados --}}
                <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-times-circle text-blue-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cancelados</h3>
                    <p class="text-2xl font-bold">{{ $cancelados ?? 0 }}</p>
                </div>
                {{-- Pendientes --}}
                <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-clock text-red-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Pendientes</h3>
                    <p class="text-2xl font-bold">{{ $pendientes ?? $postulantes->count() }}</p>
                </div>
            </div>
        </x-block>

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
        <div class="rounded-2xl border">
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

                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Cargo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Fecha de postulación</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Evaluado por</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Estado</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">
                                Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($postulantes as $postulante)
                            <tr class="hover:bg-blue-50 transition-colors" data-dni="{{ strtolower($postulante->dni) }}"
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
                                    <span
                                        class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($postulante->fecha_postula)->format('d/m/Y') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ ($postulante->estado_entrevista ?? 'No evaluado') === 'No evaluado' ? 'bg-gray-100 text-gray-600' : 'bg-indigo-100 text-indigo-800' }}">
                                        {{ $postulante->evaluado_por ?? 'Aún no evaluado' }}
                                    </span>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @php
                                        $estado = $postulante->estado_entrevista ?? 'No evaluado';
                                    @endphp

                                    @if ($estado === 'Evaluado')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Evaluado
                                        </span>
                                    @elseif($estado === 'Borrador')
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Borrador
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            No evaluado
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button onclick="viewPostulante({{ $postulante->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                            title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <a href="{{ route('entrevistas.evaluar', $postulante->id) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                            title="Entrevistar">
                                            <i class="fas fa-comments"></i>
                                        </a>
                                        <button onclick="editPostulante({{ $postulante->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="deletePostulante({{ $postulante->id }})"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
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
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Eliminar Postulante?</h3>
                    <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer. El postulante será eliminado
                        permanentemente.</p>
                    <div class="flex space-x-3">
                        <button onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                            Cancelar
                        </button>
                        <button onclick="confirmDelete()"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deletePostulanteId = null;

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
            fetch(`/entrevistas/${id}/view`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('view-modal-content').innerHTML = html;
                    document.getElementById('view-modal').classList.remove('hidden');
                })
                .catch(error => {
                    alert('Error al cargar los detalles del postulante');
                });
        }

        function editPostulante(id) {
            fetch(`/entrevistas/${id}/edit`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('edit-modal-content').innerHTML = html;
                    document.getElementById('edit-modal').classList.remove('hidden');
                })
                .catch(error => {
                    alert('Error al cargar el formulario de edición');
                });
        }

        function deletePostulante(id) {
            deletePostulanteId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
            deletePostulanteId = null;
        }

        function confirmDelete() {
            if (deletePostulanteId) {
                fetch(`/entrevistas/${deletePostulanteId}`, {
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
                            alert(data.message || 'Error al eliminar el postulante');
                        }
                    })
                    .catch(error => {
                        alert('Error al eliminar el postulante');
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
