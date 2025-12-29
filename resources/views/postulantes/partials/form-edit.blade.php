<div class="relative p-6 bg-white rounded-lg">
    {{-- Cerrar --}}
    <button type="button" onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times fa-lg"></i>
    </button>

    <h2 class="text-2xl font-semibold mb-6 text-gray-900">Editar Postulante</h2>

    @php
        $pad = fn($val, $len) => str_pad(preg_replace('/\D+/', '', (string) $val), $len, '0', STR_PAD_LEFT);
        $fechaP = old(
            'fecha_postula',
            optional(\Illuminate\Support\Carbon::parse($postulante->fecha_postula))->format('Y-m-d'),
        );
        $fechaN = old(
            'fecha_nacimiento',
            optional(\Illuminate\Support\Carbon::parse($postulante->fecha_nacimiento))->format('Y-m-d'),
        );

        $depCod = $pad(old('departamento', $postulante->departamento), 2);
        $provCod = $pad(old('provincia', $postulante->provincia), 4);
        $distCod = $pad(old('distrito', $postulante->distrito), 6);
        $cargoCod = $pad(old('cargo', $postulante->cargo), 4);

        $tipoPersonalCodigo = $pad($postulante->tipo_personal_codigo ?? $postulante->tipo_cargo, 2);
        $esOperativo = in_array($tipoPersonalCodigo, ['01', '02'], true);
    @endphp

    <form id="form-edit" method="POST" action="{{ route('postulantes.update', $postulante) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Datos personales --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- DNI (no editable) --}}
            <div>
                <label class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-id-card text-blue-600"></i> DNI
                </label>
                <input type="text" name="dni" value="{{ $postulante->dni }}" readonly
                    class="mt-1 block w-full bg-gray-100 text-gray-900 border border-gray-400 rounded-lg shadow-sm cursor-not-allowed">
                <p class="text-xs text-gray-600 mt-1">No editable.</p>
            </div>

            {{-- Fecha postulacion (no editable) --}}
            <div>
                <label for="fecha_postula_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-calendar-alt text-blue-600"></i> Fecha de postulacion
                </label>
                <input type="date" id="fecha_postula_edit" name="fecha_postula" value="{{ $fechaP }}" required
                    readonly
                    class="mt-1 block w-full bg-gray-100 text-gray-900 border border-gray-400 rounded-lg shadow-sm cursor-not-allowed">
                <p class="text-xs text-gray-600 mt-1">No editable.</p>
            </div>


            {{-- Nombres --}}
            <div>
                <label for="nombres_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i> Nombres
                </label>
                <input type="text" id="nombres_edit" name="nombres"
                    value="{{ old('nombres', $postulante->nombres) }}" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('nombres')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Apellidos --}}
            <div>
                <label for="apellidos_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-user text-blue-600"></i> Apellidos
                </label>
                <input type="text" id="apellidos_edit" name="apellidos"
                    value="{{ old('apellidos', $postulante->apellidos) }}" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('apellidos')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Fecha de nacimiento --}}
            <div>
                <label for="fecha_nacimiento_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-birthday-cake text-blue-600"></i> Fecha de nacimiento
                </label>
                <input type="date" id="fecha_nacimiento_edit" name="fecha_nacimiento" value="{{ $fechaN }}"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('fecha_nacimiento')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Edad --}}
            <div>
                <label for="edad_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-hashtag text-blue-600"></i> Edad
                </label>
                <input type="number" id="edad_edit" name="edad" value="{{ old('edad', $postulante->edad) }}"
                    min="18" max="120" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                @error('edad')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Nacionalidad --}}
            <div>
                <label for="nacionalidad_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-flag text-blue-600"></i> Nacionalidad
                </label>
                <select id="nacionalidad_edit" name="nacionalidad" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('nacionalidad', $postulante->nacionalidad) }}">
                    @foreach (['PERUANA', 'EXTRANJERA'] as $nat)
                        <option value="{{ $nat }}" @selected(old('nacionalidad', $postulante->nacionalidad) === $nat)>
                            {{ ucfirst(strtolower($nat)) }}</option>
                    @endforeach
                </select>
                @error('nacionalidad')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

        </div>

        {{-- Ubicacion y contacto --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Departamento (no editable) --}}
            <div>
                <label for="departamento_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-city text-blue-600"></i> Departamento
                </label>
                <select id="departamento_edit" disabled
                    class="mt-1 block w-full bg-gray-100 border border-gray-400 text-gray-900 rounded-lg shadow-sm cursor-not-allowed"
                    data-value="{{ $depCod }}">
                    <option value="">Selecciona...</option>
                    @foreach ($departamentos as $codigo => $desc)
                        @php $codigo = str_pad($codigo,2,'0',STR_PAD_LEFT); @endphp
                        <option value="{{ $codigo }}" @selected($depCod === $codigo)>{{ $desc }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="departamento" value="{{ $depCod }}">
                <p class="text-xs text-gray-600 mt-1">No editable.</p>
                @error('departamento')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Provincia (no editable) --}}
            <div>
                <label for="provincia_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-map-marker-alt text-blue-600"></i> Provincia
                </label>
                <select id="provincia_edit" disabled
                    class="mt-1 block w-full bg-gray-100 border border-gray-400 text-gray-900 rounded-lg shadow-sm cursor-not-allowed"
                    data-value="{{ $provCod }}">
                    <option value="">Selecciona...</option>
                    @foreach ($provincias->where('DEPA_CODIGO', $depCod) as $codigo => $prov)
                        <option value="{{ $codigo }}" @selected($provCod === $codigo)>
                            {{ $prov->PROVI_DESCRIPCION }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="provincia" value="{{ $provCod }}">
                <p class="text-xs text-gray-600 mt-1">No editable.</p>
                @error('provincia')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Distrito (no editable) --}}
            <div>
                <label for="distrito_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-map-pin text-blue-600"></i> Distrito
                </label>
                <select id="distrito_edit" disabled
                    class="mt-1 block w-full bg-gray-100 border border-gray-400 text-gray-900 rounded-lg shadow-sm cursor-not-allowed"
                    data-value="{{ $distCod }}">
                    <option value="">Selecciona...</option>
                    @foreach ($distritos->where('PROVI_CODIGO', $provCod) as $codigo => $dist)
                        <option value="{{ $codigo }}" @selected($distCod === $codigo)>
                            {{ $dist->DIST_DESCRIPCION }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="distrito" value="{{ $distCod }}">
                <p class="text-xs text-gray-600 mt-1">No editable.</p>
                @error('distrito')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Celular --}}
            <div>
                <label for="celular_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-phone text-blue-600"></i> Celular
                </label>
                <input type="tel" id="celular_edit" name="celular"
                    value="{{ old('celular', $postulante->celular) }}" pattern="[0-9]{9}" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">

                @error('celular')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Informacion laboral y estudios --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Tipo de cargo --}}
            <div>
                <label for="tipo_cargo_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-briefcase text-blue-600"></i> Tipo de cargo
                </label>
                <select id="tipo_cargo_edit" name="tipo_cargo" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ str_pad(old('tipo_cargo', $postulante->tipo_cargo), 2, '0', STR_PAD_LEFT) }}">
                    <option value="">Selecciona...</option>
                    @foreach ($tipoCargos as $codigo => $desc)
                        <option value="{{ str_pad($codigo, 2, '0', STR_PAD_LEFT) }}" @selected(str_pad(old('tipo_cargo', $postulante->tipo_cargo), 2, '0', STR_PAD_LEFT) === str_pad($codigo, 2, '0', STR_PAD_LEFT))>
                            {{ $desc }}
                        </option>
                    @endforeach
                </select>
                @error('tipo_cargo')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Cargo solicitado (editable) --}}
            @php
                $pad = fn($v, $len) => $v === null || $v === ''
                    ? null
                    : str_pad(preg_replace('/\D+/', '', (string) $v), $len, '0', STR_PAD_LEFT);
                $cargoSel = $pad(old('cargo', $postulante->cargo), 4);
            @endphp

            <div>
                <label for="cargo_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-briefcase text-blue-600"></i>Cargo solicitado
                </label>

                <select id="cargo_edit" name="cargo" data-value="{{ $cargoSel }}"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona...</option>

                    @foreach ($cargos as $codigo => $desc)
                        @php $codigoPad = $pad($codigo, 4); @endphp
                        <option value="{{ $codigoPad }}" @selected((string) $codigoPad === (string) $cargoSel)>
                            {{ $desc }}
                        </option>
                    @endforeach
                </select>

                @error('cargo')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Experiencia en el rubro --}}
            @php
                $opExp = ['Sin experiencia', 'Menos de 1 año', 'Entre 1 y 2 años', 'Entre 3 y 4 años', 'Mas de 4 años'];
            @endphp
            <div>
                <label for="experiencia_rubro_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-briefcase-clock text-blue-600"></i> Experiencia en el rubro
                </label>


                <select id="experiencia_rubro_edit" name="experiencia_rubro" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('experiencia_rubro', $postulante->experiencia_rubro) }}">
                    <option value="">Selecciona tu experiencia</option>
                    @foreach ($opExp as $opt)
                        <option value="{{ $opt }}" @selected(old('experiencia_rubro', $postulante->experiencia_rubro) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('experiencia_rubro')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Grado de instruccion --}}
            @php
                $opGrado = [
                    'Universitaria',
                    'Carrera Técnica',
                    'Egresado de las FFAA / FFPP',
                    '5º Grado de Secundaria',
                ];
            @endphp
            <div>
                <label for="grado_instruccion_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-blue-600"></i> Grado de instruccion
                </label>
                <select id="grado_instruccion_edit" name="grado_instruccion" required
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('grado_instruccion', $postulante->grado_instruccion) }}">
                    <option value="">Selecciona el grado</option>
                    @foreach ($opGrado as $opt)
                        <option value="{{ $opt }}" @selected(old('grado_instruccion', $postulante->grado_instruccion) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('grado_instruccion')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Bloque operativo: se muestra solo si es operativo --}}
            @php
                $opArma = ['NO', 'L1', 'L2', 'L3', 'L4', 'L5', 'L6'];
                $opConducir = [
                    'NO',
                    'A-I',
                    'A-IIa',
                    'A-IIb',
                    'A-IIIa',
                    'A-IIIb',
                    'A-IIc',
                    'B-I',
                    'B-IIa',
                    'B-IIb',
                    'B-IIc',
                ];
            @endphp


            <div class="{{ $esOperativo ? '' : 'hidden' }}" data-operativo>
                <label for="sucamec_edit" class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-id-badge text-blue-600"></i>Curso SUCAMEC
                </label>
                <select id="sucamec_edit" name="sucamec"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('sucamec', $postulante->sucamec) }}">
                    @foreach (['SI', 'NO'] as $opt)
                        <option value="{{ $opt }}" @selected(old('sucamec', $postulante->sucamec) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('sucamec')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="{{ $esOperativo ? '' : 'hidden' }}" data-operativo>
                <label for="carne_sucamec_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-id-card text-blue-600"></i>Carne SUCAMEC
                </label>
                <select id="carne_sucamec_edit" name="carne_sucamec"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('carne_sucamec', $postulante->carne_sucamec) }}">
                    @foreach (['SI', 'NO'] as $opt)
                        <option value="{{ $opt }}" @selected(old('carne_sucamec', $postulante->carne_sucamec) === $opt)>{{ $opt }}</option>
                    @endforeach
                </select>
                @error('carne_sucamec')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>


            <div class="{{ $esOperativo ? '' : 'hidden' }}" data-operativo>
                <label for="licencia_arma_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-gun text-blue-600"></i> Licencia de arma
                </label>
                <select id="licencia_arma_edit" name="licencia_arma"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('licencia_arma', $postulante->licencia_arma) }}">
                    <option value="">Seleccione...</option>
                    @foreach ($opArma as $opt)
                        <option value="{{ $opt }}" @selected(old('licencia_arma', $postulante->licencia_arma) === $opt)>{{ $opt }}
                        </option>
                    @endforeach
                </select>
                @error('licencia_arma')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="{{ $esOperativo ? '' : 'hidden' }}" data-operativo>
                <label for="licencia_conducir_edit"
                    class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-car-side text-blue-600"></i> Licencia de conducir
                </label>
                <select id="licencia_conducir_edit" name="licencia_conducir"
                    class="mt-1 block w-full border border-gray-400 text-gray-900 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    data-value="{{ old('licencia_conducir', $postulante->licencia_conducir) }}">
                    <option value="">Seleccione...</option>
                    @foreach ($opConducir as $opt)
                        <option value="{{ $opt }}" @selected(old('licencia_conducir', $postulante->licencia_conducir) === $opt)>{{ $opt }}
                        </option>
                    @endforeach
                </select>
                @error('licencia_conducir')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Campos ocultos para administrativos (para mantener valores al guardar) --}}
            @unless ($esOperativo)
                <input type="hidden" name="sucamec" value="{{ old('sucamec', $postulante->sucamec ?? 'NO') }}">
                <input type="hidden" name="carne_sucamec"
                    value="{{ old('carne_sucamec', $postulante->carne_sucamec ?? 'NO') }}">
                <input type="hidden" name="licencia_arma"
                    value="{{ old('licencia_arma', $postulante->licencia_arma ?? 'NO') }}">
                <input type="hidden" name="licencia_conducir"
                    value="{{ old('licencia_conducir', $postulante->licencia_conducir ?? 'NO') }}">
            @endunless
        </div>

        {{-- Botones --}}
        <div class="flex justify-end space-x-3">
            <button type="button" onclick="closeEditModal()"
                class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Cancelar
            </button>
            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                Guardar cambios
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-edit');
        if (!form) return;

        // Refresca los selects con su valor actual (útil si algún JS externo los resetea)
        form.querySelectorAll('select[data-value]').forEach((sel) => {
            const val = sel.dataset.value;
            if (val !== undefined && val !== null) {
                sel.value = val;
            }
        });
    });
</script>
