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
                <!-- <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                        <input type="text" name="buscar" placeholder="Buscar requerimientos..."
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                    </div> -->
                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700">Área Solicitante</label>
                    <select name="area_solicitante" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        <option value="recursos_humanos" {{ request('area_solicitante') == 'recursos_humanos' ? 'selected' : '' }}>Recursos Humanos</option>
                        <option value="operaciones" {{ request('area_solicitante') == 'operaciones' ? 'selected' : '' }}>Operaciones</option>
                        <option value="administracion" {{ request('area_solicitante') == 'administracion' ? 'selected' : '' }}>Administración</option>
                        <option value="seguridad" {{ request('area_solicitante') == 'seguridad' ? 'selected' : '' }}>Seguridad</option>
                        <option value="sistemas" {{ request('area_solicitante') == 'sistemas' ? 'selected' : '' }}>Sistemas</option>
                    </select>
                </div> -->

                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <select name="sucursal" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        @foreach($sucursales as $codigo => $sucursal)
                        <option value="{{ $codigo }}" {{ request('sucursal') == $codigo ? 'selected' : '' }}>
                            {{ $sucursal->SUCU_DESCRIPCION }}
                        </option>
                        @endforeach
                    </select>
                </div> -->

                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        <option value="cliente_a">Cliente A</option>
                        <option value="cliente_b">Cliente B</option>
                        <option value="cliente_c">Cliente C</option>
                    </select>
                </div> -->

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
                    <select name="cargo" id="cargo" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach($cargos as $codigo => $cargo)
                        <option value="{{ $codigo }}" {{ request('cargo') == $codigo ? 'selected' : '' }}>
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

        {{-- Estadísticas --}}
        <!-- <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
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
        </div> -->

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
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Cargo</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Distrito</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nombres y Apellidos</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Edad</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">DNI</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nacionalidad</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Estado Civil</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Experiencia</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">SUCAMEC</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Grado de Instrucción</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Celular</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Servicio Militar</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Arma L4</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Conducir A1</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">CUL</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">CV</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Estado</th>
                            <!-- <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Estado</th> -->
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($postulantes as $postulante)
                        <tr class="hover:bg-blue-50 transition">
                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->cargo_nombre }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-gray-700 text-center">{{$postulante->distrito_nombre}}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->apellidos }} {{ $postulante->nombres }}</p>
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
                                <p class="text-gray-700 text-center">{{ $postulante->estado_nombre }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->experiencia_rubro }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->sucamec }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->nivel_nombre }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->celular }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->servicio_militar }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->licencia_arma }}</p>
                            </td>

                            <td class="px-6 py-4">
                                <p class="text-gray-700 text-center">{{ $postulante->licencia_conducir }}</p>
                            </td>

                            <td class="px-6 py-4 text-center">
                                @if($postulante->cul)
                                <a href="{{ route('postulantes.descargarArchivo', ['id' => $postulante->id, 'tipo' => 'cul']) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                    title="Descargar CUL">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($postulante->cv)
                                <a href="{{ route('postulantes.descargarArchivo', ['id' => $postulante->id, 'tipo' => 'cv']) }}"
                                    class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                    title="Descargar CV">
                                    <i class="fa-solid fa-file-pdf"></i>
                                </a>
                                @else
                                <span class="text-gray-400">-</span>
                                @endif
                            </td>

                    
                            <td class="px-4 py-3 flex space-x-2">
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
        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));

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