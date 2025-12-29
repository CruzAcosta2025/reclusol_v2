@php
    use Carbon\Carbon;

    $fechaInicioValue = old(
        'fecha_inicio',
        $requerimiento->fecha_inicio ? Carbon::parse($requerimiento->fecha_inicio)->format('Y-m-d') : '',
    );

    $fechaFinValue = old(
        'fecha_fin',
        $requerimiento->fecha_fin ? Carbon::parse($requerimiento->fecha_fin)->format('Y-m-d') : '',
    );
@endphp


<div class="relative p-6 bg-white rounded-lg">
    {{-- Cerrar --}}
    <button type="button" onclick="closeEditModal()" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
        <i class="fas fa-times fa-lg"></i>
    </button>

    <h2 class="text-2xl font-semibold mb-6">Editar Requerimiento</h2>

    <form id="form-edit" method="POST" action="{{ route('requerimientos.update', $requerimiento) }}" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- SECCIÓN 1: DATOS DE LA SOLICITUD --}}
        <div class="border-b pb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Datos de la Solicitud
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Sucursal --}}
                <div>
                    <label for="sucursal_edit" class="block text-sm font-medium text-gray-700">Sucursal</label>
                    <select id="sucursal_edit" name="sucursal" required
                        data-value="{{ old('sucursal', $requerimiento->sucursal) }}"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona la sucursal</option>
                        @foreach ($sucursales as $suc => $descripcion)
                            <option value="{{ $suc }}" @selected(old('sucursal', $requerimiento->sucursal) == $suc)>
                                {{ $descripcion }}
                            </option>
                        @endforeach
                    </select>
                    @error('sucursal')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cliente --}}
                <div>
                    <label for="cliente_edit" class="block text-sm font-medium text-gray-700">Cliente</label>
                    <select id="cliente_edit" name="cliente" required
                        data-value="{{ old('cliente', $requerimiento->cliente) }}"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona un cliente</option>
                        @foreach ($clientes as $codigo => $nombre)
                            <option value="{{ $codigo }}" @selected(old('cliente', $requerimiento->cliente) == $codigo)>
                                {{ $nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Personal --}}
                <div>
                    <label for="tipo_personal_edit" class="block text-sm font-medium text-gray-700">Tipo de
                        Personal</label>
                    <select id="tipo_personal_edit" name="tipo_personal" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona el tipo de personal</option>
                        @foreach ($tipoPersonal as $codigo => $desc)
                            <option value="{{ $codigo }}" @selected(old('tipo_personal', $requerimiento->tipo_personal) == $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_personal')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Cargo --}}
                <div>
                    <label for="tipo_cargo_edit" class="block text-sm font-medium text-gray-700">Tipo de Cargo</label>
                    <select id="tipo_cargo_edit" name="tipo_cargo" required
                        data-value="{{ old('tipo_cargo', $requerimiento->tipo_cargo) }}"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona el tipo de cargo</option>
                        @foreach ($tipoCargos as $codigo => $desc)
                            <option value="{{ $codigo }}" @selected(old('tipo_cargo', $requerimiento->tipo_cargo) == $codigo)>
                                {{ $desc }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_cargo')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cargo Solicitado --}}
                <div>
                    <label for="cargo_edit" class="block text-sm font-medium text-gray-700">Cargo Solicitado</label>
                    <select id="cargo_edit" name="cargo_solicitado" required
                        data-value="{{ old('cargo_solicitado', $requerimiento->cargo_solicitado) }}"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona el cargo</option>
                        @foreach ($cargos as $codigo => $desc)
                            <option value="{{ $codigo }}" @selected(old('cargo_solicitado', $requerimiento->cargo_solicitado) == $codigo)>
                                {{ $desc->DESC_CARGO }}
                            </option>
                        @endforeach
                    </select>
                    @error('cargo_solicitado')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha Inicio --}}
                <div>
                    <label for="fecha_inicio_edit" class="block text-sm font-medium text-gray-700">Fecha de
                        Inicio</label>
                    <input type="date" id="fecha_inicio_edit" name="fecha_inicio" value="{{ $fechaInicioValue }}"
                        required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_inicio')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha Fin --}}
                <div>
                    <label for="fecha_fin_edit" class="block text-sm font-medium text-gray-700">Fecha Fin</label>
                    <input type="date" id="fecha_fin_edit" name="fecha_fin" value="{{ $fechaFinValue }}" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha_fin')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                {{-- Urgencia --}}
                <div>
                    <label for="urgencia_edit" class="block text-sm font-medium text-gray-700">Urgencia</label>
                    <select id="urgencia_edit" name="urgencia" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona urgencia</option>
                        <option value="Alta" @selected(old('urgencia', $requerimiento->urgencia) == 'Alta')>Alta</option>
                        <option value="Media" @selected(old('urgencia', $requerimiento->urgencia) == 'Media')>Media</option>
                        <option value="Baja" @selected(old('urgencia', $requerimiento->urgencia) == 'Baja')>Baja</option>
                        <option value="Mayor" @selected(old('urgencia', $requerimiento->urgencia) == 'Mayor')>Mayor a 1 mes</option>
                    </select>
                    @error('urgencia')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Cantidad Requerida --}}
                <div>
                    <label for="cantidad_requerida_edit" class="block text-sm font-medium text-gray-700">Cantidad
                        Requerida</label>
                    <input type="number" id="cantidad_requerida_edit" name="cantidad_requerida"
                        value="{{ old('cantidad_requerida', $requerimiento->cantidad_requerida) }}" min="1"
                        max="255" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('cantidad_requerida')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


        {{-- SECCIÓN 2: PERFIL Y REQUISITOS --}}
        <div class="border-b pb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clipboard-list text-blue-500 mr-2"></i>Perfil y Requisitos
            </h3>

            {{-- Campos comunes (Operativo y Administrativo) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Edad Mínima --}}
                <div>
                    <label for="edad_minima_edit" class="block text-sm font-medium text-gray-700">Edad Mínima</label>
                    <input type="number" id="edad_minima_edit" name="edad_minima"
                        value="{{ old('edad_minima', $requerimiento->edad_minima) }}" min="18" max="65"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('edad_minima')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Edad Máxima --}}
                <div>
                    <label for="edad_maxima_edit" class="block text-sm font-medium text-gray-700">Edad Máxima</label>
                    <input type="number" id="edad_maxima_edit" name="edad_maxima"
                        value="{{ old('edad_maxima', $requerimiento->edad_maxima) }}" min="18" max="70"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    @error('edad_maxima')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Experiencia Mínima --}}
                @php
                    $opExp = [
                        'Sin experiencia',
                        'Menos de 1 año',
                        'Entre 1 y 2 años',
                        'Entre 3 y 4 años',
                        'Mas de 4 años',
                    ];
                @endphp

                <div>
                    <label for="experiencia_minima_edit"
                        class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-briefcase-clock text-blue-600"></i> Experiencia Mínima
                    </label>
                    <select id="experiencia_minima_edit" name="experiencia_minima" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        data-value = "{{ old('experiencia_minima', $requerimiento->experiencia_minima) }}">
                        <option value="">Selecciona la experiencia</option>
                        @foreach ($opExp as $exp)
                            <option value="{{ $exp }}" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == $exp)>
                                {{ $exp }}
                            </option>
                        @endforeach
                    </select>
                    @error('experiencia_minima')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @php
                    $gradosAcademicos = [
                        '5to Grado de Secundaria',
                        'Egresado de la FFAA/FFPP',
                        'Carrera Técnica',
                        'Carrera Universitaria',
                    ];
                @endphp

                {{-- Grado Académico --}}
                <div>
                    <label for="grado_academico_edit"
                        class="block text-sm font-semibold text-gray-900 flex items-center gap-2">
                        <i class="fas fa-graduation-cap text-blue-600"></i> Grado Académico requerido
                    </label>

                    <select id="grado_academico_edit" name="grado_academico" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona el grado</option>
                        @foreach ($gradosAcademicos as $gad)
                            <option value="{{ $gad }}" @selected(old('grado_academico', $requerimiento->grado_academico) == $gad)>
                                {{ $gad }}
                            </option>
                        @endforeach
                    </select>
                    @error('grado_academico')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- CAMPOS SOLO PARA OPERATIVO --}}
            <div id="campos-operativo-edit" class="mt-6 pt-6 border-t" style="display: none;">
                <h4 class="text-md font-semibold text-gray-700 mb-4">Requisitos Especiales (Operativo)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Curso SUCAMEC --}}
                    <div>
                        <label for="curso_sucamec_operativo_edit"
                            class="block text-sm font-medium text-gray-700">Curso SUCAMEC vigente
                        </label>

                        <select id="curso_sucamec_operativo_edit" name="curso_sucamec_operativo"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            data-value="{{ old('curso_sucamec_operativo', $requerimiento->curso_sucamec_operativo) }}">
                            @foreach (['Sí', 'No'] as $option)
                                <option value="{{ $option }}" @selected(old('curso_sucamec_operativo', $requerimiento->curso_sucamec_operativo) == $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Carné SUCAMEC --}}
                    <div>
                        <label for="carne_sucamec_operativo_edit"
                            class="block text-sm font-medium text-gray-700">Carné SUCAMEC vigente
                        </label>
                        <select id="carne_sucamec_operativo_edit" name="carne_sucamec_operativo"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach (['Sí', 'No'] as $option)
                                <option value="{{ $option }}" @selected(old('carne_sucamec_operativo', $requerimiento->carne_sucamec_operativo) == $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Licencia Armas --}}
                    <div>
                        <label for="licencia_armas_edit" class="block text-sm font-medium text-gray-700">
                            Licencia para portar armas (L4-L5)
                        </label>
                        <select id="licencia_armas_edit" name="licencia_armas"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach (['Sí', 'No'] as $option)
                                <option value="{{ $option }}" @selected(old('licencia_armas', $requerimiento->licencia_armas) == $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Licencia Conducir --}}
                    <div>
                        <label for="requiere_licencia_conducir_edit"
                            class="block text-sm font-medium text-gray-700">Licencia de conducir</label>
                        <select id="requiere_licencia_conducir_edit" name="requiere_licencia_conducir"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @foreach (['Sí', 'No'] as $option)
                                <option value="{{ $option }}" @selected(old('requiere_licencia_conducir', $requerimiento->requiere_licencia_conducir) == $option)>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Servicio Acuartelado --}}
                    @php
                        $servicioAcuarteladoSelected = old('servicio_acuartelado');
                        if ($servicioAcuarteladoSelected === null) {
                            $servicioAcuarteladoSelected =
                                json_decode($requerimiento->servicio_acuartelado ?? '[]', true) ?: [];
                        }
                    @endphp

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Servicio acuartelado</label>

                        <div class="mt-1" x-data="{
                            open: false,
                            options: ['No', 'Con habitabilidad', 'Con alimentación', 'Con movilidad de traslado'],
                            selected: @js($servicioAcuarteladoSelected),
                        }">
                            <button type="button" @click="open=!open"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-left shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <template x-if="selected.length === 0">
                                    <span class="text-gray-400">Selecciona</span>
                                </template>
                                <template x-if="selected.length > 0">
                                    <span class="text-gray-700" x-text="selected.join(', ')"></span>
                                </template>
                            </button>

                            <div x-show="open" x-transition @click.outside="open=false"
                                class="mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
                                style="display:none;">
                                <div class="max-h-56 overflow-auto p-2">
                                    <template x-for="(opt,i) in options" :key="i">
                                        <label
                                            class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                :value="opt" x-model="selected">
                                            <span class="text-sm text-gray-700" x-text="opt"></span>
                                        </label>
                                    </template>
                                </div>

                                <div class="border-t px-3 py-2 flex items-center justify-between">
                                    <button type="button" class="text-sm text-gray-500 hover:text-gray-700"
                                        @click="selected=[]">
                                        Limpiar
                                    </button>
                                    <button type="button"
                                        class="text-sm text-blue-600 font-semibold hover:text-blue-700"
                                        @click="open=false">
                                        Listo
                                    </button>
                                </div>
                            </div>

                            <!-- Enviar como array -->
                            <template x-for="(val,i) in selected" :key="i + '-' + val">
                                <input type="hidden" name="servicio_acuartelado[]" :value="val">
                            </template>
                        </div>

                        @error('servicio_acuartelado')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>
        </div>

        {{-- SECCIÓN 4: REMUNERACIÓN Y BENEFICIOS --}}
        <div class="border-b pb-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-dollar-sign text-purple-500 mr-2"></i>Remuneración y Beneficios
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Sueldo Básico --}}
                <div>
                    <label for="sueldo_basico_edit" class="block text-sm font-medium text-gray-700">Sueldo Básico
                        (S/)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 select-none">S/</span>
                        <input type="number" id="sueldo_basico_edit" name="sueldo_basico" inputmode="decimal"
                            value="{{ old('sueldo_basico', $requerimiento->sueldo_basico) }}" min="0"
                            step="0.01" required
                            class="mt-1 block w-full pl-10 border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    @error('sueldo_basico')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Beneficios --}}
                @php
                    $beneficiosSelected = old('beneficios');
                    if ($beneficiosSelected === null) {
                        $beneficiosSelected = json_decode($requerimiento->beneficios ?? '[]', true) ?: [];
                    }
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700">Beneficios Adicionales</label>

                    <div class="mt-1" x-data="{
                        open: false,
                        options: ['Seguro de Salud', 'CTS', 'Vacaciones', 'Asignación familiar', 'Utilidades', 'Gratificación'],
                        selected: @js($beneficiosSelected), }">
                        <button type="button" @click="open=!open"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white text-left shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <template x-if="selected.length === 0">
                                <span class="text-gray-400">Selecciona</span>
                            </template>
                            <template x-if="selected.length > 0">
                                <span class="text-gray-700" x-text="selected.join(', ')"></span>
                            </template>
                        </button>

                        <div x-show="open" x-transition @click.outside="open=false"
                            class="mt-2 w-full bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
                            style="display:none;">
                            <div class="max-h-56 overflow-auto p-2">
                                <template x-for="(opt,i) in options" :key="i">
                                    <label
                                        class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-blue-50 cursor-pointer">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                            :value="opt" x-model="selected">
                                        <span class="text-sm text-gray-700" x-text="opt"></span>
                                    </label>
                                </template>
                            </div>

                            <div class="border-t px-3 py-2 flex items-center justify-between">
                                <button type="button" class="text-sm text-gray-500 hover:text-gray-700"
                                    @click="selected=[]">
                                    Limpiar
                                </button>
                                <button type="button" class="text-sm text-blue-600 font-semibold hover:text-blue-700"
                                    @click="open=false">
                                    Listo
                                </button>
                            </div>
                        </div>

                        <!-- Enviar como array -->
                        <template x-for="(val,i) in selected" :key="i + '-' + val">
                            <input type="hidden" name="beneficios[]" :value="val">
                        </template>
                    </div>

                    @error('beneficios')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>

        {{-- SECCIÓN 5: ESTADO --}}
        <div>
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 mr-2"></i>Estado del Requerimiento
            </h3>

            <div>
                <label for="estado_edit" class="block text-sm font-medium text-gray-700">Estado</label>
                <select id="estado_edit" name="estado" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Selecciona un estado</option>
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}" @selected(old('estado', $requerimiento->estado) == $estado->id)>
                            {{ $estado->nombre }}
                        </option>
                    @endforeach
                </select>
                @error('estado')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- BOTONES --}}
        <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
            <button type="button" onclick="closeEditModal()"
                class="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-400 transition">
                Cancelar
            </button>
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2">
                <i class="fas fa-save"></i> Guardar Cambios
            </button>
        </div>
    </form>
</div>

<style>
    #form-edit input[type="text"],
    #form-edit input[type="number"],
    #form-edit input[type="date"],
    #form-edit select,
    #form-edit textarea {
        background-color: #ffffff !important;
        color: #111827 !important;
        border: 1px solid #d1d5db !important;
        transition: border-color 0.2s;
    }

    #form-edit input:focus,
    #form-edit select:focus,
    #form-edit textarea:focus {
        border-color: #3b82f6 !important;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    #form-edit label {
        color: #374151;
        font-weight: 500;
        font-size: 0.875rem;
    }

    #form-edit h3 {
        color: #111827;
        font-weight: 700;
    }

    #form-edit h4 {
        color: #374151;
    }
</style>
