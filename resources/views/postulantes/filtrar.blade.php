<x-app-layout>
    <div class="min-h-screen gradient-bg py-8">
        {{-- Botón volver --}}
        <a href="{{ route('dashboard') }}"
            class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al Dashboard</span>
        </a>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            {{-- Encabezado --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h1 class="text-3xl font-bold text-gray-800">Listado de Postulantes</h1>
                <p class="text-gray-600 mt-1">Ver y filtrar postulantes según diferentes criterios</p>
            </div>
        </div>

        {{-- Filtro --}}

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <form id="filter-form" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-lg">
                {{-- Sucursal --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Sucursal</label>
                    <select name="sucursal" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Seleccionar</option>
                        {{-- Opciones dinámicas --}}
                    </select>
                </div>

                {{-- Cliente --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select name="cliente" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Seleccionar</option>
                    </select>
                </div>

                {{-- Localidad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Localidad</label>
                    <select name="localidad" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Seleccionar</option>
                    </select>
                </div>

                {{-- Ciudad --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ciudad</label>
                    <select name="ciudad" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Todas</option>
                        <option value="chimbote" {{ request('ciudad') == 'chimbote' ? 'selected' : '' }}>Chimbote</option>
                        <option value="lima" {{ request('ciudad') == 'lima' ? 'selected' : '' }}>Lima</option>
                        <option value="moquegua" {{ request('ciudad') == 'moquegua' ? 'selected' : '' }}>Moquegua</option>
                        <option value="trujillo" {{ request('ciudad') == 'trujillo' ? 'selected' : '' }}>Trujillo</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <select name="estado" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="aprobado">Aprobado</option>
                        <option value="rechazado">Rechazado</option>
                    </select>
                </div>

                {{-- Botones --}}
                <div class="md:col-span-4 flex flex-wrap gap-2 mt-4">
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-filter mr-2"></i> Filtrar
                    </button>
                    <button type="button"
                        onclick="limpiarFiltros()"
                        class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition">
                        <i class="fas fa-times"></i> Limpiar filtros
                    </button>
                </div>

            </form>
        </div>

        {{-- Estadísticas --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- En Proceso --}}
                <div class="bg-yellow-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-spinner fa-2x text-yellow-500 mb-2"></i>
                    <p class="text-sm text-gray-600">En Proceso</p>
                    <p class="text-2xl font-bold">{{ $stats['en_proceso'] ?? 0 }}</p>
                </div>
                {{-- Aprobados --}}
                <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-check-circle fa-2x text-green-500 mb-2"></i>
                    <p class="text-sm text-gray-600">Aprobados</p>
                    <p class="text-2xl font-bold">{{ $stats['aprobados'] ?? 0 }}</p>
                </div>
                {{-- Pendientes --}}
                <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-hourglass-half fa-2x text-blue-500 mb-2"></i>
                    <p class="text-sm text-gray-600">Pendientes</p>
                    <p class="text-2xl font-bold">{{ $stats['pendientes'] ?? 0 }}</p>
                </div>
                {{-- Rechazados --}}
                <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-times-circle fa-2x text-red-500 mb-2"></i>
                    <p class="text-sm text-gray-600">Rechazados</p>
                    <p class="text-2xl font-bold">{{ $stats['rechazados'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            {{-- Encabezado --}}
            <div class="flex items-center justify-between bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 rounded-t-xl shadow-md">
                <h2 class="flex items-center text-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>
                    Listado de Requerimientos
                </h2>
                <span class="text-sm opacity-80">
                    {{-- {{ $postulantes->total() }} resultados --}}
                </span>
            </div>

            <div class="overflow-x-auto bg-white rounded-b-xl shadow-md">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Postulante</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Cargo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Contacto</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Ubicación</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Estado</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($postulantes as $postulante)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0 w-8 h-8 bg-emerald-500 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                        {{ strtoupper(substr($postulante->nombres,0,1)) }}
                                        {{ strtoupper(substr($postulante->apellidos,0,1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $postulante->nombres }} {{ $postulante->apellidos }}</p>
                                        <p class="text-xs text-gray-500">DNI: {{ $postulante->dni }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700">{{ $postulante->cargo }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700">{{ $postulante->celular }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700">{{ ucfirst($postulante->distrito) }} - {{ ucfirst($postulante->ciudad) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($postulante->estado == 'en_proceso')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-medium">
                                    En Proceso
                                </span>
                                @elseif($postulante->estado == 'aprobado')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-green-100 text-green-800 text-xs font-medium">
                                    Aprobado
                                </span>
                                @elseif($postulante->estado == 'rechazado')
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-red-100 text-red-800 text-xs font-medium">
                                    Rechazado
                                </span>
                                @else
                                <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-medium">
                                    Pendiente
                                </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 flex space-x-2">
                                <a href="#"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                    title="Ver">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <button
                                    data-id="{{ $postulante->id }}"
                                    class="btn-edit inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                    title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="eliminarPostulante({{ $postulante->id }})"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                    title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
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

            {{-- Modal de Eliminación --}}
            <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-auto">
                    <h3 class="text-lg font-semibold mb-4">¿Eliminar postulante?</h3>
                    <p class="text-sm text-gray-600 mb-4">Esta acción no se puede deshacer.</p>
                    <div class="flex justify-end space-x-2">
                        <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
                        <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded">Eliminar</button>
                    </div>
                </div>
            </div>

            {{-- Modal de Edición --}}
            <div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full mx-auto" id="edit-modal-content">
                    {{-- Aquí se inyectará el formulario de edición --}}
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    <script>
        let deletePostulanteId = null;

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        function eliminarPostulante(id) {
            deletePostulanteId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }

        function closeDeleteModal() {
            deletePostulanteId = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function confirmDelete() {
            if (deletePostulanteId) {
                fetch(`/postulantes/${deletePostulanteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else alert('Error al eliminar');
                    })
                    .catch(() => alert('Error al eliminar'));
            }
            closeDeleteModal();
        }

        // Abrir modal y cargar el form-edit
        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                fetch(`/postulantes/${id}/edit`)
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('edit-modal-content').innerHTML = html;
                        document.getElementById('edit-modal').classList.remove('hidden');
                    });
            });
        });

        // Cerrar modal
        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }

        // Capturar submit y enviar por AJAX usando POST + override
        document.addEventListener('submit', function(e) {
            if (e.target.id === 'form-edit') {
                e.preventDefault();
                const form = e.target;
                fetch(form.action, {
                        method: 'POST', // enviamos como POST
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-HTTP-Method-Override': 'PUT', // override a PUT
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

        function exportarResultados() {
            const form = document.getElementById('filter-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.open(`/postulantes/exportar?${params}`, '_blank');
        }
    </script>

</x-app-layout>