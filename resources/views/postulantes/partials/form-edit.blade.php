<form id="form-edit" method="POST" action="{{ route('postulantes.update', $postulante) }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- DNI (bloqueado) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">DNI</label>
                <input type="text"
                    value="{{ $postulante->dni }}"
                    readonly disabled
                    class="mt-1 block w-full bg-gray-100 text-gray-700 border-gray-300 rounded-lg shadow-sm">
                <p class="text-xs text-gray-500 mt-1">No editable.</p>
            </div>

            {{-- Nombres --}}
            <div>
                <label for="nombres_edit" class="block text-sm font-medium text-gray-700">Nombres</label>
                <input type="text" id="nombres_edit" name="nombres"
                    value="{{ old('nombres', $postulante->nombres) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('nombres') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Apellidos --}}
            <div>
                <label for="apellidos_edit" class="block text-sm font-medium text-gray-700">Apellidos</label>
                <input type="text" id="apellidos_edit" name="apellidos"
                    value="{{ old('apellidos', $postulante->apellidos) }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('apellidos') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Edad --}}
            <div>
                <label for="edad_edit" class="block text-sm font-medium text-gray-700">Edad</label>
                <input type="number" id="edad_edit" name="edad"
                    value="{{ old('edad', $postulante->edad) }}" min="18" max="120" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('edad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Nacionalidad --}}
            <div>
                <label for="nacionalidad_edit" class="block text-sm font-medium text-gray-700">Nacionalidad</label>
                <select id="nacionalidad_edit" name="nacionalidad" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['PERUANA','EXTRANJERA'] as $nat)
                    <option value="{{ $nat }}" @selected(old('nacionalidad', $postulante->nacionalidad)===$nat)>{{ ucfirst(strtolower($nat)) }}</option>
                    @endforeach
                </select>
                @error('nacionalidad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Celular --}}
            <div>
                <label for="celular_edit" class="block text-sm font-medium text-gray-700">Celular</label>
                <input type="tel" id="celular_edit" name="celular"
                    value="{{ old('celular', $postulante->celular) }}" pattern="\d{9}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('celular') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Fecha de postulación --}}
            @php
            $fecha = old('fecha_postula', optional(\Illuminate\Support\Carbon::parse($postulante->fecha_postula))->format('Y-m-d'));
            @endphp
            <div>
                <label for="fecha_postula_edit" class="block text-sm font-medium text-gray-700">Fecha de postulación</label>
                <input type="date" id="fecha_postula_edit" name="fecha_postula" value="{{ $fecha }}" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('fecha_postula') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Tipo de cargo --}}
            <div>
                <label for="tipo_cargo_edit" class="block text-sm font-medium text-gray-700">Tipo de cargo</label>
                <select id="tipo_cargo_edit" name="tipo_cargo" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona...</option>
                    @foreach($tipoCargos as $codigo => $desc)
                    <option value="{{ str_pad($codigo,2,'0',STR_PAD_LEFT) }}"
                        @selected(str_pad(old('tipo_cargo', $postulante->tipo_cargo),2,'0',STR_PAD_LEFT) === str_pad($codigo,2,'0',STR_PAD_LEFT))>
                        {{ $desc }}
                    </option>
                    @endforeach
                </select>
                @error('tipo_cargo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Cargo (dependiente de tipo) --}}
            <div>
                <label for="cargo_edit" class="block text-sm font-medium text-gray-700">Cargo</label>
                <select id="cargo_edit" name="cargo" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona el cargo</option>
                    {{-- Se rellena por JS según tipo_cargo --}}
                </select>
                @error('cargo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Departamento --}}
            <div>
                <label for="departamento_edit" class="block text-sm font-medium text-gray-700">Departamento</label>
                <select id="departamento_edit" name="departamento" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona...</option>
                    @foreach($departamentos as $codigo => $desc)
                    @php $codigo = str_pad($codigo,2,'0',STR_PAD_LEFT); @endphp
                    <option value="{{ $codigo }}" @selected(str_pad(old('departamento',$postulante->departamento),2,'0',STR_PAD_LEFT)===$codigo)>
                        {{ $desc }}
                    </option>
                    @endforeach
                </select>
                @error('departamento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Provincia (depende de departamento) --}}
            <div>
                <label for="provincia_edit" class="block text-sm font-medium text-gray-700">Provincia</label>
                <select id="provincia_edit" name="provincia" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona...</option>
                </select>
                @error('provincia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Distrito (depende de provincia) --}}
            <div>
                <label for="distrito_edit" class="block text-sm font-medium text-gray-700">Distrito</label>
                <select id="distrito_edit" name="distrito" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona...</option>
                </select>
                @error('distrito') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Experiencia en el rubro --}}
            <div>
                <label for="experiencia_rubro_edit" class="block text-sm font-medium text-gray-700">Experiencia en el rubro</label>
                @php
                $opExp = [
                'Sin experiencia',
                'Menos de 1 año',
                'Entre 1 y 2 años',
                'Entre 3 y 4 años',
                'Más de 4 años',
                ];
                @endphp
                <select id="experiencia_rubro_edit" name="experiencia_rubro" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona tu experiencia</option>
                    @foreach($opExp as $opt)
                    <option value="{{ $opt }}" @selected(old('experiencia_rubro',$postulante->experiencia_rubro)===$opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('experiencia_rubro') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Grado de instrucción --}}
            <div>
                <label for="grado_instruccion_edit" class="block text-sm font-medium text-gray-700">Grado de instrucción</label>
                @php
                $opGrado = [
                'Universitaria',
                'Carrera Técnica',
                'Egresado de las FFAA / FFPP',
                '5º Grado de Secundaria',
                ];
                @endphp
                <select id="grado_instruccion_edit" name="grado_instruccion" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona el grado</option>
                    @foreach($opGrado as $opt)
                    <option value="{{ $opt }}" @selected(old('grado_instruccion',$postulante->grado_instruccion)===$opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('grado_instruccion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Licencia de arma --}}
            <div>
                <label for="licencia_arma_edit" class="block text-sm font-medium text-gray-700">Licencia de arma</label>
                @php
                $opArma = ['NO','L1','L2','L3','L4','L5','L6'];
                @endphp
                <select id="licencia_arma_edit" name="licencia_arma" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccione...</option>
                    @foreach($opArma as $opt)
                    <option value="{{ $opt }}" @selected(old('licencia_arma',$postulante->licencia_arma)===$opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('licencia_arma') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Licencia de conducir --}}
            <div>
                <label for="licencia_conducir_edit" class="block text-sm font-medium text-gray-700">Licencia de conducir</label>
                @php
                $opConducir = ['NO','A-I','A-IIa','A-IIb','A-IIIa','A-IIIb','A-IIC','B-I','B-IIa','B-IIb','B-IIC'];
                @endphp
                <select id="licencia_conducir_edit" name="licencia_conducir" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Seleccione...</option>
                    @foreach($opConducir as $opt)
                    <option value="{{ $opt }}" @selected(old('licencia_conducir',$postulante->licencia_conducir)===$opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('licencia_conducir') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- SUCAMEC (SI/NO) --}}
            <div>
                <label for="sucamec_edit" class="block text-sm font-medium text-gray-700">SUCAMEC</label>
                <select id="sucamec_edit" name="sucamec" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @foreach(['SI','NO'] as $opt)
                    <option value="{{ $opt }}" @selected(old('sucamec',$postulante->sucamec)===$opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('sucamec') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

</form>
