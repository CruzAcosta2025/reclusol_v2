@extends('layouts.app')

@section('content')
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
            <form id="filter-form" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-lg">
                {{-- 
                <div>
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
                 
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sucursal</label>
                    <select name="sucursal" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todas</option>
                        @foreach ($sucursales as $codigo => $sucursal)
                        <option value="{{ $codigo }}" {{ request('sucursal') == $codigo ? 'selected' : '' }}>
                            {{ $sucursal->SUCU_DESCRIPCION }}
                        </option>
                        @endforeach
                    </select>
                </div> 

                <!-- <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente</label>
                    <select name="cliente" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        <option value="cliente_a">Cliente A</option>
                        <option value="cliente_b">Cliente B</option>
                        <option value="cliente_c">Cliente C</option>
                    </select>
                </div> -->

            --}}

                <!-- Tipo de Cargo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cargo</label>
                    <select name="tipo_cargo" id="tipo_cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        <option value="">Selecciona el cargo</option>
                        @foreach ($tipoCargos as $cod => $desc)
                            <option value="{{ $cod }}" {{ request('tipo_cargo') == $cod ? 'selected' : '' }}>
                                {{ is_object($desc) ? $desc->DESC_TIPO_CARG ?? (string) $desc : $desc }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cargo Solicitado</label>
                    <select name="cargo" id="cargo"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 bg-white/80">
                        <option value="">Todos</option>
                        @foreach ($cargos as $cod => $desc)
                            <option value="{{ $cod }}" {{ request('cargo') == $cod ? 'selected' : '' }}>
                                {{ is_object($desc) ? $desc->DESC_CARGO ?? (string) $desc : $desc }}
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
                        @foreach ($departamentos as $codigo => $descripcion)
                            <option value="{{ $codigo }}" {{ request('departamento') == $codigo ? 'selected' : '' }}>
                                {{ is_object($descripcion) ? $descripcion->DEPA_DESCRIPCION ?? (string) $descripcion : $descripcion }}
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
                        @foreach ($provincias as $codigo => $descripcion)
                            <option value="{{ $codigo }}" {{ request('provincia') == $codigo ? 'selected' : '' }}>
                                {{ is_object($descripcion) ? $descripcion->PROVI_DESCRIPCION ?? (string) $descripcion : $descripcion }}
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
                        @foreach ($distritos as $codigo => $descripcion)
                            <option value="{{ $codigo }}" {{ request('distrito') == $codigo ? 'selected' : '' }}>
                                {{ is_object($descripcion) ? $descripcion->DIST_DESCRIPCION ?? (string) $descripcion : $descripcion }}
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
            <div
                class="flex items-center justify-between bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-4 py-3 rounded-t-xl shadow-md">
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
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nombres y Apellidos
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Edad</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">DNI</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Nacionalidad</th>
                            {{-- <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Estado Civil</th> --}}
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Experiencia</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">SUCAMEC</th>
                            {{-- <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Grado de Instrucción </th>  --}}
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Celular</th>
                            {{-- <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Servicio Militar </th> --}}
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Arma L4
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase text-center">Licencia de Conducir
                                A1</th>
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
                                    <span class="text-gray-700 text-center">{{ $postulante->distrito_nombre }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-gray-700 text-center">{{ $postulante->apellidos }}
                                        {{ $postulante->nombres }}</p>
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
                                    <p class="text-gray-700 text-center">{{ $postulante->celular }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-gray-700 text-center">{{ $postulante->licencia_arma }}</p>
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-gray-700 text-center">{{ $postulante->licencia_conducir }}</p>
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if ($postulante->cul)
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
                                    @if ($postulante->cv)
                                        <a href="{{ route('postulantes.descargarArchivo', ['id' => $postulante->id, 'tipo' => 'cv']) }}"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-600 transition"
                                            title="Descargar CV">
                                            <i class="fa-solid fa-file-pdf"></i>
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4">
                                    <p class="text-gray-700 text-center">{{ $postulante->estado_nombre }}</p>
                                </td>

                                <td class="px-4 py-3 flex space-x-2">
                                    <button data-id="{{ $postulante->id }}"
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
        // Datos pasados desde el servidor
        const cargos = Object.values(@json($cargos));
        const provincias = Object.values(@json($provincias));
        const distritos = Object.values(@json($distritos));

        // Valores seleccionados (si vienen en la query string)
        const selected = {
            tipo_cargo: "{{ request('tipo_cargo') }}",
            cargo: "{{ request('cargo') }}",
            departamento: "{{ request('departamento') }}",
            provincia: "{{ request('provincia') }}",
            distrito: "{{ request('distrito') }}",
            sucursal: "{{ request('sucursal') }}"
        };

        // Utilidades
        function clearSelect(selectEl, placeholderText = 'Selecciona') {
            selectEl.innerHTML = '';
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = placeholderText;
            selectEl.appendChild(opt);
        }

        // Poblador de cargos según tipo de cargo
        function populateCargos(tipoCargoId, preselect = '') {
            const cargoSelect = document.getElementById('cargo');
            clearSelect(cargoSelect, 'Todos');

            let filtered = cargos;
            if (tipoCargoId) {
                filtered = cargos.filter(p => {
                    const tipo = p.TIPO_CARG ?? p.tipo_carg ?? p.tipoCargo ?? p.tipo;
                    return String(tipo) === String(tipoCargoId);
                });
            }

            if (filtered.length === 0) {
                const opt = document.createElement('option');
                opt.value = "";
                opt.textContent = tipoCargoId ? 'No hay cargos para este tipo' : 'Todos';
                cargoSelect.appendChild(opt);
                return;
            }

            filtered.forEach(p => {
                const code = p.CODI_CARG ?? p.cod ?? p.code ?? p.id;
                const desc = p.DESC_CARGO ?? p.desc ?? p.descripcion ?? p.name;
                const option = document.createElement('option');
                option.value = code;
                option.textContent = desc;
                if (String(preselect) !== '' && String(preselect) === String(option.value)) {
                    option.selected = true;
                }
                cargoSelect.appendChild(option);
            });

            if (!preselect && selected.cargo) {
                const opt = Array.from(cargoSelect.options).find(o => String(o.value) === String(selected.cargo));
                if (opt) opt.selected = true;
            }
        }

        // Poblador de provincias por departamento
        function populateProvincias(depaId, preselect = '') {
            const provinciaSelect = document.getElementById('provincia');
            clearSelect(provinciaSelect, 'Todas');

            if (!depaId) return;

            const filtered = provincias.filter(p => {
                const dep = p.DEPA_CODIGO ?? p.depa_codigo ?? p.depa ?? p.departamento;
                return String(dep) === String(depaId);
            });

            if (filtered.length === 0) {
                const opt = document.createElement('option');
                opt.value = "";
                opt.textContent = "No hay provincias para este departamento";
                provinciaSelect.appendChild(opt);
                return;
            }

            filtered.forEach(p => {
                const code = p.PROVI_CODIGO ?? p.provi_codigo ?? p.code ?? p.id;
                const desc = p.PROVI_DESCRIPCION ?? p.provi_descripcion ?? p.desc ?? p.name;
                const option = document.createElement('option');
                option.value = code;
                option.textContent = desc;
                if (String(preselect) !== '' && String(preselect) === String(option.value)) {
                    option.selected = true;
                }
                provinciaSelect.appendChild(option);
            });

            if (!preselect && selected.provincia) {
                const opt = Array.from(provinciaSelect.options).find(o => String(o.value) === String(selected.provincia));
                if (opt) opt.selected = true;
            }
        }

        // Poblador de distritos por provincia
        function populateDistritos(provId, preselect = '') {
            const distritoSelect = document.getElementById('distrito');
            clearSelect(distritoSelect, 'Todos');

            if (!provId) return;

            const filtered = distritos.filter(p => {
                const prov = p.PROVI_CODIGO ?? p.provi_codigo ?? p.provincia;
                return String(prov) === String(provId);
            });

            if (filtered.length === 0) {
                const opt = document.createElement('option');
                opt.value = "";
                opt.textContent = "No hay distritos para esta provincia";
                distritoSelect.appendChild(opt);
                return;
            }

            filtered.forEach(p => {
                const code = p.DIST_CODIGO ?? p.dist_codigo ?? p.code ?? p.id;
                const desc = p.DIST_DESCRIPCION ?? p.dist_descripcion ?? p.desc ?? p.name;
                const option = document.createElement('option');
                option.value = code;
                option.textContent = desc;
                if (String(preselect) !== '' && String(preselect) === String(option.value)) {
                    option.selected = true;
                }
                distritoSelect.appendChild(option);
            });

            if (!preselect && selected.distrito) {
                const opt = Array.from(distritoSelect.options).find(o => String(o.value) === String(selected.distrito));
                if (opt) opt.selected = true;
            }
        }

        // DOM Ready
        document.addEventListener('DOMContentLoaded', () => {
            const tipoCargoEl = document.getElementById('tipo_cargo');
            const depEl = document.getElementById('departamento');
            const provEl = document.getElementById('provincia');

            // Cargar cargos/provincias/distritos según filtros actuales
            if (selected.tipo_cargo) {
                tipoCargoEl.value = selected.tipo_cargo;
                populateCargos(selected.tipo_cargo, selected.cargo || '');
            } else {
                populateCargos('', selected.cargo || '');
            }

            if (selected.departamento) {
                depEl.value = selected.departamento;
                populateProvincias(selected.departamento, selected.provincia || '');
            }

            if (selected.provincia) {
                provEl.value = selected.provincia;
                populateDistritos(selected.provincia, selected.distrito || '');
            }

            // Listeners
            tipoCargoEl.addEventListener('change', function() {
                populateCargos(this.value);
            });

            depEl.addEventListener('change', function() {
                populateProvincias(this.value);
                // limpiar distrito si cambia departamento
                document.getElementById('distrito').innerHTML = '<option value="">Todos</option>';
            });

            provEl.addEventListener('change', function() {
                populateDistritos(this.value);
            });

            // Abrir modal edición
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.dataset.id;
                    fetch(`/postulantes/${id}/edit`)
                        .then(res => {
                            if (!res.ok) throw new Error('Error al cargar formulario');
                            return res.text();
                        })
                        .then(html => {
                            document.getElementById('edit-modal-content').innerHTML = html;
                            const modal = document.getElementById('edit-modal');
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        })
                        .catch(err => {
                            console.error(err);
                            alert('No se pudo cargar el formulario de edición.');
                        });
                });
            });

            // cerrar modales si clic fuera del contenido
            document.getElementById('edit-modal').addEventListener('click', function(e) {
                if (e.target === this) closeEditModal();
            });
            document.getElementById('delete-modal').addEventListener('click', function(e) {
                if (e.target === this) closeDeleteModal();
            });
        });

        // Eliminar postulante
        let deletePostulanteId = null;

        function eliminarPostulante(id) {
            deletePostulanteId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }

        function closeDeleteModal() {
            deletePostulanteId = null;
            const m = document.getElementById('delete-modal');
            m.classList.remove('flex');
            m.classList.add('hidden');
        }

        function confirmDelete() {
            if (!deletePostulanteId) return closeDeleteModal();

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
                    else alert(data.message || 'Error al eliminar');
                })
                .catch(() => alert('Error al eliminar'))
                .finally(() => closeDeleteModal());
        }

        // Envío del form-edit por AJAX (delegado)
        document.addEventListener('submit', function(e) {
            if (e.target && e.target.id === 'form-edit') {
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
                        if (data.success) window.location.reload();
                        else alert(data.message || 'Error al actualizar');
                    })
                    .catch(err => {
                        console.error('Errores de validación:', err.errors || err);
                        alert('Hay errores de validación. Revisa la consola.');
                    });
            }
        });

        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        function exportarResultados() {
            const form = document.getElementById('filter-form');
            const params = new URLSearchParams(new FormData(form)).toString();
            window.open(`/postulantes/exportar?${params}`, '_blank');
        }
    </script>
@endsection
