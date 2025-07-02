<div class="relative p-6 bg-white rounded-lg">
    {{-- Cerrar --}}
    <button type="button"
        onclick="closeEditModal()"
        class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times fa-lg"></i>
    </button>

    <h2 class="text-2xl font-semibold mb-6">Editar Postulante</h2>

    <form id="form-edit"
        method="POST"
        action="{{ route('postulantes.update', $postulante) }}"
        enctype="multipart/form-data"
        class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Nombres --}}
            <div>
                <label for="nombres_edit" class="block text-sm font-medium text-gray-700">Nombres</label>
                <input type="text"
                    id="nombres_edit"
                    name="nombres"
                    value="{{ old('nombres', $postulante->nombres) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('nombres')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Apellidos --}}
            <div>
                <label for="apellidos_edit" class="block text-sm font-medium text-gray-700">Apellidos</label>
                <input type="text"
                    id="apellidos_edit"
                    name="apellidos"
                    value="{{ old('apellidos', $postulante->apellidos) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('apellidos')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- DNI --}}
            <div>
                <label for="dni_edit" class="block text-sm font-medium text-gray-700">DNI</label>
                <input type="text"
                    id="dni_edit"
                    name="dni"
                    value="{{ old('dni', $postulante->dni) }}"
                    pattern="\d{8}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('dni')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Edad --}}
            <div>
                <label for="edad_edit" class="block text-sm font-medium text-gray-700">Edad</label>
                <input type="number"
                    id="edad_edit"
                    name="edad"
                    value="{{ old('edad', $postulante->edad) }}"
                    min="18" max="120"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('edad')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Ciudad --}}
            <div>
                <label for="ciudad_edit" class="block text-sm font-medium text-gray-700">Ciudad</label>
                <select id="ciudad_edit"
                    name="ciudad"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Selecciona --</option>
                    @foreach(['lima','chimbote','trujillo','moquegua'] as $ciu)
                    <option value="{{ $ciu }}"
                        {{ old('ciudad', $postulante->ciudad) === $ciu ? 'selected' : '' }}>
                        {{ ucfirst($ciu) }}
                    </option>
                    @endforeach
                </select>
                @error('ciudad')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Distrito --}}
            <div>
                <label for="distrito_edit" class="block text-sm font-medium text-gray-700">Distrito</label>
                <input type="text"
                    id="distrito_edit"
                    name="distrito"
                    value="{{ old('distrito', $postulante->distrito) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('distrito')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Celular --}}
            <div>
                <label for="celular_edit" class="block text-sm font-medium text-gray-700">Celular</label>
                <input type="tel"
                    id="celular_edit"
                    name="celular"
                    value="{{ old('celular', $postulante->celular) }}"
                    pattern="\d{9}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('celular')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Celular de referencia --}}
            <div>
                <label for="celular_referencia_edit" class="block text-sm font-medium text-gray-700">
                    Celular de referencia (opcional)
                </label>
                <input type="tel"
                    id="celular_referencia_edit"
                    name="celular_referencia"
                    value="{{ old('celular_referencia', $postulante->celular_referencia) }}"
                    pattern="\d{9}"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('celular_referencia')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Estado civil --}}
            <div>
                <label for="estado_civil_edit" class="block text-sm font-medium text-gray-700">Estado civil</label>
                <select id="estado_civil_edit"
                    name="estado_civil"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['soltero','casado','divorciado','viudo'] as $est)
                    <option value="{{ $est }}"
                        {{ old('estado_civil', $postulante->estado_civil) === $est ? 'selected':'' }}>
                        {{ ucfirst($est) }}
                    </option>
                    @endforeach
                </select>
                @error('estado_civil')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nacionalidad --}}
            <div>
                <label for="nacionalidad_edit" class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                <select id="nacionalidad_edit"
                    name="nacionalidad"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['peruana','extranjera'] as $nat)
                    <option value="{{ $nat }}"
                        {{ old('nacionalidad', $postulante->nacionalidad) === $nat ? 'selected':'' }}>
                        {{ ucfirst($nat) }}
                    </option>
                    @endforeach
                </select>
                @error('nacionalidad')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cargo --}}
            <div>
                <label for="cargo_edit" class="block text-sm font-medium text-gray-700">Cargo</label>
                <input type="text"
                    id="cargo_edit"
                    name="cargo"
                    value="{{ old('cargo', $postulante->cargo) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('cargo')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            @php
            $fecha = old(
            'fecha_postula',
            \Carbon\Carbon::parse($postulante->fecha_postula)->format('Y-m-d')
            );
            @endphp

            {{-- Fecha de postulación --}}
            <div>
                <label for="fecha_postula_edit" class="block text-sm font-medium text-gray-700">Fecha de postulación</label>
                <input type="date"
                    id="fecha_postula_edit"
                    name="fecha_postula"
                    value="{{ $fecha }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('fecha_postula')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Experiencia --}}
            <div>
                <label for="experiencia_rubro_edit" class="block text-sm font-medium text-gray-700">
                    Experiencia en rubro
                </label>
                <input type="text"
                    id="experiencia_rubro_edit"
                    name="experiencia_rubro"
                    value="{{ old('experiencia_rubro', $postulante->experiencia_rubro) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('experiencia_rubro')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- SUCAMEC --}}
            <div>
                <label for="sucamec_edit" class="block text-sm font-medium text-gray-700">SUCAMEC</label>
                <select id="sucamec_edit"
                    name="sucamec"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['vigente','no_vigente','no_tiene'] as $s)
                    <option value="{{ $s }}"
                        {{ old('sucamec', $postulante->sucamec) === $s ? 'selected':'' }}>
                        {{ ucwords(str_replace('_',' ',$s)) }}
                    </option>
                    @endforeach
                </select>
                @error('sucamec')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grado de instrucción --}}
            <div>
                <label for="grado_instruccion_edit" class="block text-sm font-medium text-gray-700">
                    Grado de instrucción
                </label>
                <select id="grado_instruccion_edit"
                    name="grado_instruccion"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['primaria','secundaria','tecnico','universitario','postgrado'] as $g)
                    <option value="{{ $g }}"
                        {{ old('grado_instruccion', $postulante->grado_instruccion) === $g ? 'selected':'' }}>
                        {{ ucfirst($g) }}
                    </option>
                    @endforeach
                </select>
                @error('grado_instruccion')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Servicio militar --}}
            <div>
                <label for="servicio_militar_edit" class="block text-sm font-medium text-gray-700">
                    Servicio militar
                </label>
                <select id="servicio_militar_edit"
                    name="servicio_militar"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['cumplido','no_cumplido','exonerado'] as $sm)
                    <option value="{{ $sm }}"
                        {{ old('servicio_militar', $postulante->servicio_militar) === $sm ? 'selected':'' }}>
                        {{ ucfirst(str_replace('_',' ',$sm)) }}
                    </option>
                    @endforeach
                </select>
                @error('servicio_militar')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Licencia de arma --}}
            <div>
                <label for="licencia_arma_edit" class="block text-sm font-medium text-gray-700">
                    Licencia de arma
                </label>
                <select id="licencia_arma_edit"
                    name="licencia_arma"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['vigente','vencida','no_tiene'] as $la)
                    <option value="{{ $la }}"
                        {{ old('licencia_arma', $postulante->licencia_arma) === $la ? 'selected':'' }}>
                        {{ ucfirst(str_replace('_',' ',$la)) }}
                    </option>
                    @endforeach
                </select>
                @error('licencia_arma')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Licencia de conducir --}}
            <div>
                <label for="licencia_conducir_edit" class="block text-sm font-medium text-gray-700">
                    Licencia de conducir
                </label>
                <select id="licencia_conducir_edit"
                    name="licencia_conducir"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['a1','a2a','a2b','b1','b2a','b2b','no_tiene'] as $cd)
                    <option value="{{ $cd }}"
                        {{ old('licencia_conducir', $postulante->licencia_conducir) === $cd ? 'selected':'' }}>
                        {{ strtoupper($cd) }}
                    </option>
                    @endforeach
                </select>
                @error('licencia_conducir')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Botones --}}
        <div class="flex justify-end space-x-3">
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