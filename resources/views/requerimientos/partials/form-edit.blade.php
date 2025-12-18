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
                <div>
                    <label for="experiencia_minima_edit" class="block text-sm font-medium text-gray-700">Experiencia
                        Mínima</label>
                    <select id="experiencia_minima_edit" name="experiencia_minima"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona experiencia</option>
                        <option value="6_meses" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == '6_meses')>Sin experiencia</option>
                        <option value="1_anio" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == '1_anio')>Menos de 1 año</option>
                        <option value="2_anios" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == '2_anios')>Entre 1 y 2 años</option>
                        <option value="3_4_anios" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == '3_4_anios')>Entre 3 y 4 años</option>
                        <option value="4_anios" @selected(old('experiencia_minima', $requerimiento->experiencia_minima) == '4_anios')>Más de 4 años</option>
                    </select>
                    @error('experiencia_minima')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Grado Académico --}}
                <div>
                    <label for="grado_academico_edit" class="block text-sm font-medium text-gray-700">Grado
                        Académico requerido</label>
                    <select id="grado_academico_edit" name="grado_academico"
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona grado</option>
                        <option value="secundaria" @selected(old('grado_academico', $requerimiento->grado_academico) == 'secundaria')>5to Grado de Secundaria</option>
                        <option value="ffaa_ffpp" @selected(old('grado_academico', $requerimiento->grado_academico) == 'ffaa_ffpp')>Egresado de la FFAA/FFPP</option>
                        <option value="tecnica" @selected(old('grado_academico', $requerimiento->grado_academico) == 'tecnica')>Carrera Técnica</option>
                        <option value="universitaria" @selected(old('grado_academico', $requerimiento->grado_academico) == 'universitaria')>Carrera Universitaria</option>
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
                            class="block text-sm font-medium text-gray-700">Curso SUCAMEC vigente</label>
                        <select id="curso_sucamec_operativo_edit" name="curso_sucamec_operativo"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona</option>
                            <option value="si" @selected(old('curso_sucamec_operativo', $requerimiento->curso_sucamec_operativo) == 'si')>Sí</option>
                            <option value="no" @selected(old('curso_sucamec_operativo', $requerimiento->curso_sucamec_operativo) == 'no')>No</option>
                        </select>
                    </div>

                    {{-- Carné SUCAMEC --}}
                    <div>
                        <label for="carne_sucamec_operativo_edit"
                            class="block text-sm font-medium text-gray-700">Carné SUCAMEC vigente</label>
                        <select id="carne_sucamec_operativo_edit" name="carne_sucamec_operativo"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona</option>
                            <option value="si" @selected(old('carne_sucamec_operativo', $requerimiento->carne_sucamec_operativo) == 'si')>Sí</option>
                            <option value="no" @selected(old('carne_sucamec_operativo', $requerimiento->carne_sucamec_operativo) == 'no')>No</option>
                        </select>
                    </div>

                    {{-- Licencia Armas --}}
                    <div>
                        <label for="licencia_armas_edit" class="block text-sm font-medium text-gray-700">Licencia para
                            portar armas (L4-L5)</label>
                        <select id="licencia_armas_edit" name="licencia_armas"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona</option>
                            <option value="si" @selected(old('licencia_armas', $requerimiento->licencia_armas) == 'si')>Sí</option>
                            <option value="no" @selected(old('licencia_armas', $requerimiento->licencia_armas) == 'no')>No</option>
                        </select>
                    </div>

                    {{-- Licencia Conducir --}}
                    <div>
                        <label for="requiere_licencia_conducir_edit"
                            class="block text-sm font-medium text-gray-700">Licencia de conducir</label>
                        <select id="requiere_licencia_conducir_edit" name="requiere_licencia_conducir"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona</option>
                            <option value="si" @selected(old('requiere_licencia_conducir', $requerimiento->requiere_licencia_conducir) == 'si')>Sí</option>
                            <option value="no" @selected(old('requiere_licencia_conducir', $requerimiento->requiere_licencia_conducir) == 'no')>No</option>
                        </select>
                    </div>

                    {{-- Servicio Acuartelado --}}
                    <div class="md:col-span-2">
                        <label for="servicio_acuartelado_edit"
                            class="block text-sm font-medium text-gray-700">Servicio acuartelado</label>
                        <select id="servicio_acuartelado_edit" name="servicio_acuartelado"
                            class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Selecciona</option>
                            <option value="no" @selected(old('servicio_acuartelado', $requerimiento->servicio_acuartelado) == 'no')>No</option>
                            <option value="con_habitabilidad" @selected(old('servicio_acuartelado', $requerimiento->servicio_acuartelado) == 'con_habitabilidad')>Con habitabilidad</option>
                            <option value="con_alimentacion" @selected(old('servicio_acuartelado', $requerimiento->servicio_acuartelado) == 'con_alimentacion')>Con alimentación</option>
                            <option value="con_movilidad" @selected(old('servicio_acuartelado', $requerimiento->servicio_acuartelado) == 'con_movilidad')>Con movilidad de traslado
                            </option>
                        </select>
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
                <div>
                    <label for="beneficios_edit" class="block text-sm font-medium text-gray-700">Beneficios
                        Adicionales</label>
                    <select id="beneficios_edit" name="beneficios" required
                        class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona</option>
                        <option value="escala_a" @selected(old('beneficios', $requerimiento->beneficios) == 'escala_a')>Seguro de Salud</option>
                        <option value="escala_b" @selected(old('beneficios', $requerimiento->beneficios) == 'escala_b')>CTS</option>
                        <option value="escala_c" @selected(old('beneficios', $requerimiento->beneficios) == 'escala_c')>Vacaciones</option>
                        <option value="escala_d" @selected(old('beneficios', $requerimiento->beneficios) == 'escala_d')>Asignación familiar</option>
                    </select>
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
