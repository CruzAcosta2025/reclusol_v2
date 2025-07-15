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
                        @foreach($sucursales as $codigo => $sucursal)
                        <option value="{{ $codigo }}" {{ request('sucursal') == $codigo ? 'selected' : '' }}>
                            {{ $sucursal->SUCU_DESCRIPCION }}
                        </option>
                        @endforeach
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

                <!-- Tipo de Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                    <select name="tipo_cargo" id="tipo_cargo" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach($tipoCargos as $codigo => $tipo_cargo)
                        <option value="{{ $codigo }}" {{ request('tipo_cargo') == $codigo ? 'selected' : '' }}>
                            {{ $tipo_cargo->DESC_TIPO_CARG }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo Solicitado</label>
                    <select name="cargo_solicitado" id="cargo_solicitado" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach($cargos as $codigo => $cargo)
                        <option value="{{ $codigo }}" {{ request('cargo_solicitado') == $codigo ? 'selected' : '' }}>
                            {{ $cargo->DESC_CARGO }}
                        </option>
                        @endforeach
                    </select>
                </div>


                {{-- Departamento --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Departamento</label>
                    <select name="departamento" id="departamento" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach($departamentos as $codigo => $departamento)
                        <option value="{{ $codigo }}" {{ request('departamento') == $codigo ? 'selected' : '' }}>
                            {{ $departamento->DEPA_DESCRIPCION }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Provincia --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provincia</label>
                    <select name="provincia" id="provincia" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        @foreach($provincias as $codigo => $provincia)
                        <option value="{{ $codigo }}" {{ request('provincia') == $codigo ? 'selected' : '' }}>
                            {{ $provincia->PROVI_DESCRIPCION }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Distrito --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Distrito</label>
                    <select name="distrito" id="distrito" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach($distritos as $codigo => $distrito)
                        <option value="{{ $codigo }}" {{ request('distrito') == $codigo ? 'selected' : '' }}>
                            {{ $distrito->DIST_DESCRIPCION }}
                        </option>
                        @endforeach
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
                    <i class="fas fa-hourglass-half text-yellow-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">En Proceso</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosProcesos ?? 0 }} </p>
                </div>

                <div class="bg-green-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-check-circle text-green-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cubiertos</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosCubiertos ?? 0 }}</p>
                </div>

                <div class="bg-blue-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-times-circle text-blue-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Cancelados</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosCancelados ?? 0 }}</p>
                </div>

                <div class="bg-red-100 p-4 rounded-lg text-center flex flex-col items-center">
                    <i class="fas fa-clock text-red-500 mb-2"></i>
                    <h3 class="text-sm text-gray-600">Vencidos</h3>
                    <p class="text-2xl font-bold">{{ $requerimientosVencidos ?? 0 }} </p>
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
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Cargo Solicitado</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Área</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Sucursal</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Cliente</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Prioridad</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Estado</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Fecha Límite</th>
                                <th class="px-6 py-4 text-center font-bold text-gray-600 uppercase">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($requerimientos as $req)
                            <tr class="hover:bg-blue-50 transition">
                                <td class="px-6 py-4 text-center">{{ $req->cargo_nombre }}</td>
                                <td class="px-6 py-4 text-center">{{ ucfirst($req->area_solicitante) }}</td>
                                <td class="px-6 py-4 text-center">{{ ucfirst($req->sucursal_nombre) }}</td>
                                <td class="px-6 py-4 text-center">{{ ucfirst($req->cliente) }}</td>
                                <td class="px-6 py-4 text-center">
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
                                <td class="px-6 py-4 text-center">
                                    @php
                                    $estadoNombre = is_object($req->estado) ? $req->estado->nombre : null;
                                    $statusColors = [
                                    'en proceso' => 'bg-yellow-100 text-yellow-800',
                                    'cubierto' => 'bg-green-100 text-green-800',
                                    'cancelado' => 'bg-red-100 text-red-800',
                                    'vencido' => 'bg-gray-200 text-gray-700',
                                    null => 'bg-gray-100 text-gray-600',
                                    ];
                                    $statusClass = $statusColors[strtolower($estadoNombre)] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                        {{ ucfirst($estadoNombre ?? 'N/A') }}
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

        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));

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

        // Filtrar cargos dinámicamente en el filtro principal
        document.getElementById('tipo_cargo').addEventListener('change', function() {
            const tipoCargoId = this.value;
            const cargoSelect = document.getElementById('cargo_solicitado');

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

        // Función global para inicializar el filtrado del modal de edición
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
    </script>

</x-app-layout>