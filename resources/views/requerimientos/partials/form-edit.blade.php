<form id="form-edit" method="POST" action="{{ route('requerimientos.update', $requerimiento) }}"
    enctype="multipart/form-data" class="space-y-5 text-xs">
    @csrf
    @method('PUT')

    {{-- Área, Sucursal, Cliente --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="area_solicitante" class="block font-medium text-gray-700 mb-1">Área Solicitante</label>
            <select id="area_solicitante" name="area_solicitante" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona el área</option>
                @foreach (['recursos_humanos' => 'Recursos Humanos', 'operaciones' => 'Operaciones', 'administracion' => 'Administración', 'seguridad' => 'Seguridad', 'sistemas' => 'Sistemas'] as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('area_solicitante', $requerimiento->area_solicitante) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('area_solicitante')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="sucursal" class="block font-medium text-gray-700 mb-1">Sucursal</label>
            <select id="sucursal" name="sucursal" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona la sucursal</option>
                @foreach ($sucursales as $key => $sucursal)
                    @php
                        $sucuCodigo = is_object($sucursal)
                            ? ($sucursal->SUCU_CODIGO ?? $key)
                            : (is_array($sucursal) ? ($sucursal['SUCU_CODIGO'] ?? $key) : $key);
                        $sucuDescripcion = is_object($sucursal)
                            ? ($sucursal->SUCU_DESCRIPCION ?? $sucursal->descripcion ?? $sucuCodigo)
                            : (is_array($sucursal) ? ($sucursal['SUCU_DESCRIPCION'] ?? $sucursal['descripcion'] ?? $sucuCodigo) : $sucursal);
                    @endphp
                    <option value="{{ $sucuCodigo }}"
                        {{ old('sucursal', $requerimiento->sucursal) == $sucuCodigo ? 'selected' : '' }}>
                        {{ $sucuDescripcion }}
                    </option>
                @endforeach
            </select>
            @error('sucursal')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="cliente" class="block font-medium text-gray-700 mb-1">Cliente</label>
            <input type="text" id="cliente" name="cliente" value="{{ old('cliente', $requerimiento->cliente) }}"
                required maxlength="50"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
            @error('cliente')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Departamento, Provincia, Distrito --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="departamento" class="block font-medium text-gray-700 mb-1">Departamento</label>
            <select id="departamento" name="departamento" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona el departamento</option>
                @foreach ($departamentos as $key => $departamento)
                    @php
                        $depaCodigo = is_object($departamento)
                            ? ($departamento->DEPA_CODIGO ?? $key)
                            : (is_array($departamento) ? ($departamento['DEPA_CODIGO'] ?? $key) : $key);
                        $depaDescripcion = is_object($departamento)
                            ? ($departamento->DEPA_DESCRIPCION ?? $departamento->descripcion ?? $depaCodigo)
                            : (is_array($departamento) ? ($departamento['DEPA_DESCRIPCION'] ?? $departamento['descripcion'] ?? $depaCodigo) : $departamento);
                    @endphp
                    <option value="{{ $depaCodigo }}"
                        {{ old('departamento', $requerimiento->departamento) == $depaCodigo ? 'selected' : '' }}>
                        {{ $depaDescripcion }}
                    </option>
                @endforeach
            </select>
            @error('departamento')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="provincia" class="block font-medium text-gray-700 mb-1">Provincia</label>
            <select id="provincia" name="provincia" required
                data-selected="{{ old('provincia', $requerimiento->provincia) }}"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona la provincia</option>
                @foreach ($provincias as $key => $provincia)
                    @php
                        $proviCodigo = is_object($provincia)
                            ? ($provincia->PROVI_CODIGO ?? $key)
                            : (is_array($provincia) ? ($provincia['PROVI_CODIGO'] ?? $key) : $key);
                        $proviDescripcion = is_object($provincia)
                            ? ($provincia->PROVI_DESCRIPCION ?? $provincia->descripcion ?? $proviCodigo)
                            : (is_array($provincia) ? ($provincia['PROVI_DESCRIPCION'] ?? $provincia['descripcion'] ?? $proviCodigo) : $provincia);
                    @endphp
                    <option value="{{ $proviCodigo }}"
                        {{ old('provincia', $requerimiento->provincia) == $proviCodigo ? 'selected' : '' }}>
                        {{ $proviDescripcion }}
                    </option>
                @endforeach
            </select>
            @error('provincia')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="distrito" class="block font-medium text-gray-700 mb-1">Distrito</label>
            <select id="distrito" name="distrito" required
                data-selected="{{ old('distrito', $requerimiento->distrito) }}"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona el distrito</option>
                @foreach ($distritos as $key => $distrito)
                    @php
                        $distCodigo = is_object($distrito)
                            ? ($distrito->DIST_CODIGO ?? $key)
                            : (is_array($distrito) ? ($distrito['DIST_CODIGO'] ?? $key) : $key);
                        $distDescripcion = is_object($distrito)
                            ? ($distrito->DIST_DESCRIPCION ?? $distrito->descripcion ?? $distCodigo)
                            : (is_array($distrito) ? ($distrito['DIST_DESCRIPCION'] ?? $distrito['descripcion'] ?? $distCodigo) : $distrito);
                    @endphp
                    <option value="{{ $distCodigo }}"
                        {{ old('distrito', $requerimiento->distrito) == $distCodigo ? 'selected' : '' }}>
                        {{ $distDescripcion }}
                    </option>
                @endforeach
            </select>
            @error('distrito')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Tipo de Cargo, Cargo Solicitado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="tipo_cargo" class="block font-medium text-gray-700 mb-1">Tipo de Cargo</label>
            <select id="tipo_cargo" name="tipo_cargo" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona el tipo de cargo</option>
                @foreach ($tipoCargos as $key => $tipo)
                    @php
                        $tipoCodigo = is_object($tipo)
                            ? ($tipo->CODI_TIPO_CARG ?? $key)
                            : (is_array($tipo) ? ($tipo['CODI_TIPO_CARG'] ?? $key) : $key);
                        $tipoDescripcion = is_object($tipo)
                            ? ($tipo->DESC_TIPO_CARG ?? $tipo->descripcion ?? $tipoCodigo)
                            : (is_array($tipo) ? ($tipo['DESC_TIPO_CARG'] ?? $tipo['descripcion'] ?? $tipoCodigo) : $tipo);
                    @endphp
                    <option value="{{ $tipoCodigo }}"
                        {{ old('tipo_cargo', $requerimiento->tipo_cargo) == $tipoCodigo ? 'selected' : '' }}>
                        {{ $tipoDescripcion }}
                    </option>
                @endforeach
            </select>
            @error('tipo_cargo')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="cargo_solicitado" class="block font-medium text-gray-700 mb-1">Cargo Solicitado</label>
            <select id="cargo_solicitado" name="cargo_solicitado" required
                data-selected="{{ old('cargo_solicitado', $requerimiento->cargo_solicitado) }}"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona el cargo</option>
            </select>
            @error('cargo_solicitado')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Fecha, Edad, Cantidad --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label for="fecha_limite" class="block font-medium text-gray-700 mb-1">Fecha Límite</label>
            <input type="date" id="fecha_limite" name="fecha_limite"
                value="{{ old('fecha_limite', \Carbon\Carbon::parse($requerimiento->fecha_limite)->format('Y-m-d')) }}"
                required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
            @error('fecha_limite')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="edad_minima" class="block font-medium text-gray-700 mb-1">Edad Mínima</label>
            <input type="number" id="edad_minima" name="edad_minima"
                value="{{ old('edad_minima', $requerimiento->edad_minima) }}" required min="18" max="65"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
            @error('edad_minima')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="edad_maxima" class="block font-medium text-gray-700 mb-1">Edad Máxima</label>
            <input type="number" id="edad_maxima" name="edad_maxima"
                value="{{ old('edad_maxima', $requerimiento->edad_maxima) }}" required min="18" max="65"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
            @error('edad_maxima')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Cantidad Requerida --}}
    <div>
        <label for="cantidad_requerida" class="block font-medium text-gray-700 mb-1">Cantidad Requerida</label>
        <input type="number" id="cantidad_requerida" name="cantidad_requerida"
            value="{{ old('cantidad_requerida', $requerimiento->cantidad_requerida) }}" required min="1"
            max="255"
            class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
        @error('cantidad_requerida')
            <p class="mt-1 text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Checkboxes --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="flex items-center">
            <input type="checkbox" id="requiere_licencia_conducir" name="requiere_licencia_conducir" value="1"
                {{ old('requiere_licencia_conducir', $requerimiento->requiere_licencia_conducir) ? 'checked' : '' }}
                class="mr-2">
            <label for="requiere_licencia_conducir" class="text-gray-700">Requiere Licencia de Conducir</label>
        </div>
        <div class="flex items-center">
            <input type="checkbox" id="requiere_sucamec" name="requiere_sucamec" value="1"
                {{ old('requiere_sucamec', $requerimiento->requiere_sucamec) ? 'checked' : '' }} class="mr-2">
            <label for="requiere_sucamec" class="text-gray-700">Requiere SUCAMEC</label>
        </div>
    </div>

    {{-- Nivel de Estudios y Experiencia Mínima --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="nivel_estudios" class="block font-medium text-gray-700 mb-1">Nivel de Estudios</label>
            <select id="nivel_estudios" name="nivel_estudios" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona nivel</option>
                @foreach ($nivelEducativo as $nivel)
                    <option value="{{ $nivel->NIED_CODIGO }}"
                        {{ old('nivel_estudios', $requerimiento->nivel_estudios) === $nivel->NIED_CODIGO ? 'selected' : '' }}>
                        {{ $nivel->NIED_DESCRIPCION }}
                    </option>
                @endforeach
            </select>
            @error('nivel_estudios')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="experiencia_minima" class="block font-medium text-gray-700 mb-1">Experiencia
                Mínima</label>
            <select id="experiencia_minima" name="experiencia_minima" required
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona experiencia</option>
                @foreach ([
        'sin_experiencia' => 'Sin experiencia',
        'menos_1_año' => 'Menos de 1 año',
        '1_2_años' => '1-2 años',
        '3_5_años' => '3-5 años',
        'mas_5_años' => 'Más de 5 años',
    ] as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('experiencia_minima', $requerimiento->experiencia_minima) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('experiencia_minima')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Requisitos Adicionales --}}
    <div>
        <label for="requisitos_adicionales" class="block font-medium text-gray-700 mb-1">Requisitos
            Adicionales</label>
        <textarea id="requisitos_adicionales" name="requisitos_adicionales" rows="3"
            class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">{{ old('requisitos_adicionales', $requerimiento->requisitos_adicionales) }}</textarea>
        @error('requisitos_adicionales')
            <p class="mt-1 text-red-600">{{ $message }}</p>
        @enderror
    </div>

    {{-- Prioridad y Estado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="prioridad" class="block font-medium text-gray-700 mb-1">Prioridad</label>
            <select id="prioridad" name="prioridad"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona prioridad</option>
                @foreach (['baja' => 'Baja', 'media' => 'Media', 'alta' => 'Alta', 'urgente' => 'Urgente'] as $key => $label)
                    <option value="{{ $key }}"
                        {{ old('prioridad', $requerimiento->prioridad) === $key ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('prioridad')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
        <div>
            <label for="estado" class="block font-medium text-gray-700 mb-1">Estado</label>
            <select id="estado" name="estado"
                class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                <option value="">Selecciona un estado</option>
                @foreach ($estados as $estado)
                    <option value="{{ $estado->id }}"
                        {{ old('estado', $requerimiento->estado) == $estado->id ? 'selected' : '' }}>
                        {{ $estado->nombre }}
                    </option>
                @endforeach
            </select>
            @error('estado')
                <p class="mt-1 text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Botones --}}
    <div class="flex justify-end space-x-2 pt-4">
        <button type="button" onclick="closeEditModal()"
            class="px-3 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition text-xs">
            Cancelar
        </button>
        <button type="submit"
            class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition text-xs">
            Guardar cambios
        </button>
    </div>
</form>