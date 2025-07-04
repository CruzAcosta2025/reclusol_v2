<div class="relative p-6 bg-white rounded-lg">
    {{-- Cerrar --}}
    <button type="button"
        onclick="closeEditModal()"
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times fa-lg"></i>
    </button>

    <h2 class="text-2xl font-semibold mb-6">Editar Requerimiento</h2>

    <form id="form-edit"
        method="POST"
        action="{{ route('requerimientos.update', $requerimiento) }}"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Área Solicitante --}}
            <div>
                <label for="area_solicitante" class="block text-sm font-medium text-gray-700">Área Solicitante</label>
                <select id="area_solicitante" name="area_solicitante" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona el área</option>
                    @foreach(['recursos_humanos'=>'Recursos Humanos','operaciones'=>'Operaciones','administracion'=>'Administración','seguridad'=>'Seguridad','sistemas'=>'Sistemas'] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('area_solicitante', $requerimiento->area_solicitante)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('area_solicitante')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Sucursal --}}
            <div>
                <label for="sucursal" class="block text-sm font-medium text-gray-700">Sucursal</label>
                <select id="sucursal" name="sucursal" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona la sucursal</option>
                    @foreach(['lima'=>'Lima','chimbote'=>'Chimbote','trujillo'=>'Trujillo','moquegua'=>'Moquegua'] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('sucursal', $requerimiento->sucursal)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('sucursal')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Cliente --}}
            <div>
                <label for="cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                <input type="text"
                    id="cliente"
                    name="cliente"
                    value="{{ old('cliente', $requerimiento->cliente) }}"
                    required
                    maxlength="50"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('cliente')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cargo Solicitado --}}
            <div>
                <label for="cargo_solicitado" class="block text-sm font-medium text-gray-700">Cargo Solicitado</label>
                <select id="cargo_solicitado" name="cargo_solicitado" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona el cargo</option>
                    @foreach(['agente_seguridad'=>'Agente de Seguridad','supervisor'=>'Supervisor','analista'=>'Analista','secretaria'=>'Secretaria','coordinador'=>'Coordinador'] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('cargo_solicitado', $requerimiento->cargo_solicitado)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('cargo_solicitado')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Cantidad Requerida --}}
            <div>
                <label for="cantidad_requerida" class="block text-sm font-medium text-gray-700">Cantidad Requerida</label>
                <input type="number"
                    id="cantidad_requerida"
                    name="cantidad_requerida"
                    value="{{ old('cantidad_requerida', $requerimiento->cantidad_requerida) }}"
                    required
                    min="1"
                    max="255"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('cantidad_requerida')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha Límite --}}
            <div>
                <label for="fecha_limite" class="block text-sm font-medium text-gray-700">Fecha Límite</label>
                <input type="date"
                    id="fecha_limite"
                    name="fecha_limite"
                    value="{{ old('fecha_limite', \Carbon\Carbon::parse($requerimiento->fecha_limite)->format('Y-m-d')) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('fecha_limite')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Edad Mínima --}}
            <div>
                <label for="edad_minima" class="block text-sm font-medium text-gray-700">Edad Mínima</label>
                <input type="number"
                    id="edad_minima"
                    name="edad_minima"
                    value="{{ old('edad_minima', $requerimiento->edad_minima) }}"
                    required
                    min="18"
                    max="65"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('edad_minima')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Edad Máxima --}}
            <div>
                <label for="edad_maxima" class="block text-sm font-medium text-gray-700">Edad Máxima</label>
                <input type="number"
                    id="edad_maxima"
                    name="edad_maxima"
                    value="{{ old('edad_maxima', $requerimiento->edad_maxima) }}"
                    required
                    min="18"
                    max="65"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('edad_maxima')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Licencia de Conducir (Checkbox) --}}
            <div class="flex items-center space-x-2">
                <input type="checkbox"
                    id="requiere_licencia_conducir"
                    name="requiere_licencia_conducir"
                    value="1"
                    {{ old('requiere_licencia_conducir', $requerimiento->requiere_licencia_conducir) ? 'checked' : '' }}>
                <label for="requiere_licencia_conducir" class="text-sm text-gray-700">Requiere Licencia de Conducir</label>
            </div>
            @error('requiere_licencia_conducir')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror

            {{-- Sucamec (Checkbox) --}}
            <div class="flex items-center space-x-2">
                <input type="checkbox"
                    id="requiere_sucamec"
                    name="requiere_sucamec"
                    value="1"
                    {{ old('requiere_sucamec', $requerimiento->requiere_sucamec) ? 'checked' : '' }}>
                <label for="requiere_sucamec" class="text-sm text-gray-700">Requiere SUCAMEC</label>
            </div>
            @error('requiere_sucamec')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror

            {{-- Nivel de Estudios --}}
            <div>
                <label for="nivel_estudios" class="block text-sm font-medium text-gray-700">Nivel de Estudios</label>
                <select id="nivel_estudios" name="nivel_estudios" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona nivel</option>
                    @foreach(['primaria'=>'Primaria','secundaria'=>'Secundaria','tecnico'=>'Técnico','universitario'=>'Universitario','postgrado'=>'Postgrado'] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('nivel_estudios', $requerimiento->nivel_estudios)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('nivel_estudios')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Experiencia Mínima --}}
            <div>
                <label for="experiencia_minima" class="block text-sm font-medium text-gray-700">Experiencia Mínima</label>
                <select id="experiencia_minima" name="experiencia_minima" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona experiencia</option>
                    @foreach([
                    'sin_experiencia'=>'Sin experiencia',
                    'menos_1_año'=>'Menos de 1 año',
                    '1_2_años'=>'1-2 años',
                    '3_5_años'=>'3-5 años',
                    'mas_5_años'=>'Más de 5 años'
                    ] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('experiencia_minima', $requerimiento->experiencia_minima)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('experiencia_minima')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            {{-- Requisitos Adicionales --}}
            <div class="md:col-span-2">
                <label for="requisitos_adicionales" class="block text-sm font-medium text-gray-700">Requisitos Adicionales</label>
                <textarea id="requisitos_adicionales"
                    name="requisitos_adicionales"
                    rows="3"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('requisitos_adicionales', $requerimiento->requisitos_adicionales) }}</textarea>
                @error('requisitos_adicionales')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Prioridad --}}
            <div>
                <label for="prioridad" class="block text-sm font-medium text-gray-700">Prioridad</label>
                <select id="prioridad" name="prioridad"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona prioridad</option>
                    @foreach(['baja'=>'Baja','media'=>'Media','alta'=>'Alta','urgente'=>'Urgente'] as $key=>$label)
                    <option value="{{ $key }}"
                        {{ old('prioridad', $requerimiento->prioridad)===$key ? 'selected':'' }}>
                        {{ $label }}
                    </option>
                    @endforeach
                </select>
                @error('prioridad')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Botones --}}
        <div class="flex justify-end space-x-3 mt-6">
            <button type="button"
                onclick="closeEditModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button type="submit"
                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Guardar cambios
            </button>
        </div>
    </form>
</div>