<x-app-layout>
    <!-- Header -->
    <div class="min-h-screen gradient-bg py-8">
        <a href="{{ route('dashboard') }}"
            class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al Dashboard</span>
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
                <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="buscar" placeholder="Buscar requerimientos..."
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                    </div> -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Área Solicitante</label>
                    <select name="area_solicitante" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        <option value="recursos_humanos" {{ request('area_solicitante') == 'recursos_humanos' ? 'selected' : '' }}>Recursos Humanos</option>
                        <option value="operaciones" {{ request('area_solicitante') == 'operaciones' ? 'selected' : '' }}>Operaciones</option>
                        <option value="administracion" {{ request('area_solicitante') == 'administracion' ? 'selected' : '' }}>Administración</option>
                        <option value="seguridad" {{ request('area_solicitante') == 'seguridad' ? 'selected' : '' }}>Seguridad</option>
                        <option value="sistemas" {{ request('area_solicitante') == 'sistemas' ? 'selected' : '' }}>Sistemas</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <select name="sucursal" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        <option value="lima" {{ request('sucursal') == 'lima' ? 'selected' : '' }}>Lima</option>
                        <option value="chimbote" {{ request('sucursal') == 'chimbote' ? 'selected' : '' }}>Chimbote</option>
                        <option value="trujillo" {{ request('sucursal') == 'trujillo' ? 'selected' : '' }}>Trujillo</option>
                        <option value="moquegua" {{ request('sucursal') == 'moquegua' ? 'selected' : '' }}>Moquegua</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        <option value="cliente_a">Cliente A</option>
                        <option value="cliente_b">Cliente B</option>
                        <option value="cliente_c">Cliente C</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cargo Solicitado</label>
                    <select name="cargo_solicitado" class="mt-1 w-full border-gray-300 rounded-md">
                        <option value="">Todos</option>
                        <option value="agente_seguridad" {{ request('cargo_solicitado') == 'agente_seguridad' ? 'selected' : '' }}>Agente Seguridad</option>
                        <option value="supervisor" {{ request('cargo_solicitado') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
                        <option value="analista" {{ request('cargo_solicitado') == 'analista' ? 'selected' : '' }}>Analista</option>
                        <option value="secretaria" {{ request('cargo_solicitado') == 'secretaria' ? 'selected' : '' }}>Secretaria</option>
                        <option value="coordinador" {{ request('cargo_solicitado') == 'coordinador' ? 'selected' : '' }}>Coordinador</option>
                    </select>
                </div>

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


        <!-- Stats Section -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-yellow-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-clipboard-list text-yellow-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Total Requerimientos</h3>
                    <p class="text-2xl font-bold">{{ $totalRequerimientos ?? 0 }} </p>
                </div>

                <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-check-circle text-green-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Activos</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosActivos ?? 0 }}</p>
                </div>

                <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-clock text-blue-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Pendientes</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosPendientes ?? 0 }}</p>
                </div>

                <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-close text-red-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cerrados</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosCerrados ?? 6 }} </p>
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
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">#</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Cargo Solicitado</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Área</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Sucursal</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Cliente</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Prioridad</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Fecha Límite</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($requerimientos as $req)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4">{{ $req->id }}</td>
                                <td class="px-6 py-4">{{ $req->cargo_solicitado }}</td>
                                <td class="px-6 py-4">{{ ucfirst($req->area_solicitante) }}</td>
                                <td class="px-6 py-4">{{ ucfirst($req->sucursal) }}</td>
                                <td class="px-6 py-4">{{ ucfirst($req->cliente) }}</td>
                                <td class="px-6 py-4">
                                    @php
                                    $priorityColors = [
                                    'alta' => 'bg-red-100 text-red-800',
                                    'media' => 'bg-yellow-100 text-yellow-800',
                                    'baja' => 'bg-green-100 text-green-800',
                                    null => 'bg-gray-100 text-gray-600'
                                    ];
                                    $priorityClass = $priorityColors[$req->prioridad ?? null] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $priorityClass }}">
                                        {{ ucfirst($req->prioridad ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                    $statusColors = [
                                    'activo' => 'bg-green-100 text-green-800',
                                    'pendiente' => 'bg-yellow-100 text-yellow-800',
                                    'cerrado' => 'bg-red-100 text-red-800',
                                    null => 'bg-gray-100 text-gray-600'
                                    ];
                                    $statusClass = $statusColors[$req->estado ?? null] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($req->estado ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($req->fecha_limite)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 flex space-x-2">
                                    <a href="#"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                        title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button
                                        data-id="{{ $req->id }}"
                                        class="btn-edit inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="eliminarRequerimiento({{ $req->id }})"
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
                <div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-xl w-full mx-auto" id="edit-modal-content">
                        {{-- Aquí se inyectará el formulario de edición --}}
                    </div>
                </div>

                {{-- Modal de Eliminación --}}
                <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                    <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm mx-auto">
                        <h3 class="text-lg font-semibold mb-4">¿Eliminar requerimiento?</h3>
                        <p class="text-sm text-gray-600 mb-4">Esta acción no se puede deshacer.</p>
                        <div class="flex justify-end space-x-2">
                            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gray-200 rounded">Cancelar</button>
                            <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded">Eliminar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #60a5fa, #1d4ed8);
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .filter-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }

        .priority-high {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .priority-medium {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .priority-low {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .status-active {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-pending {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .status-closed {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }
    </style>
    <script>
        let deleteRequerimientoId = null;

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        function eliminarRequerimiento(id) {
            deleteRequerimientoId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }

        function closeDeleteModal() {
            deleteRequerimientoId = null;
            document.getElementById('delete-modal').classList.add('hidden');
        }

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
                fetch(`/requerimientos/${id}/edit`)
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
    </script>

</x-app-layout>