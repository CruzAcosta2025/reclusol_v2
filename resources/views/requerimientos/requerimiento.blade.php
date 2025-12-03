@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        {{-- Botón volver --}}
        <a href="{{ route('requerimientos.filtrar') }}"
            class="bg-M6 hover:bg-neutral-lightest text-M2 rounded-lg border border-neutral shadow-sm p-5 font-semibold  transition-colors flex items-center space-x-3 px-6 py-3 text-sm z-10 group">
            <i class="fas fa-arrow-left text-M2 group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-bold">Volver</span>
        </a>

        <!-- Header -->
        <x-block>
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-M2">
                    Nueva Solicitud
                </h1>
                <p class="text-M3 mt-1 text-base">
                    Complete la información para crear una nueva solicitud de personal
                </p>
            </div>
        </x-block>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('requerimientos.store') }}">
            @csrf
            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <!-- Información General -->
                <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full"> 
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-M2 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-M2">Datos de la Solicitud</h2>
                            <p class="text-sm text-M3">Datos básicos del requerimiento de personal</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 w-full">
                        <!-- Fecha Límite de Reclutamiento -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-M3">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha de Solicitud
                            </label>
                            <input type="text" value="{{ now()->format('d-m-Y') }}" disabled
                                class="form-input w-full px-4 py-3 border border-gray-300 bg-gray-100 text-sm text-gray-600 rounded-lg focus:outline-none cursor-not-allowed">
                            <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d') }}">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-M3">
                                <i class="fas fa-user-alt mr-2 text-blue-500"></i>
                                Solicitado por *
                            </label>
                            <input type="text" value="{{ Auth::user()->name ?? 'INVITADO' }}" disabled
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg focus:outline-none cursor-not-allowed">
                            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                            <input type="hidden" name="cargo_usuario"
                                value="{{ Auth::user()->cargoInfo?->DESC_TIPO_CARG ?? 'Sin rol' }}">
                        </div>

                        <!-- Sucursal -->
                        <div class="space-y-2">
                            <label for="sucursal" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Sucursal *
                            </label>
                            <select id="sucursal" name="sucursal" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option class="text-neutral" value="">Selecciona la sucursal</option>
                                @foreach ($sucursales as $suc => $descripcion)
                                    <option value="{{ $suc }}">{{ $descripcion }}</option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cliente -->
                        <div class="space-y-2">
                            <label for="cliente" class="block text-sm font-semibold text-M3">
                                <i class="fa-solid fa-user-tie mr-2 text-blue-500"></i>
                                Cliente *
                            </label>
                            <select id="cliente" name="cliente" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona un cliente</option>
                                @foreach ($clientes as $c)
                                    <option value="{{ $c->CODIGO_CLIENTE }}" @selected(old('cliente') == $c->CODIGO_CLIENTE)>
                                        {{ $c->NOMBRE_CLIENTE }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-M3">
                                <i class="fas fa-user-alt mr-2 text-blue-500"></i>
                                Tipo de Personal *
                            </label>
                            <select name="tipo_personal" id="tipo_personal" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-neutral focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el tipo de personal</option>
                                @foreach ($tipoPersonal as $codigo => $desc)
                                    <option value="{{ $codigo }}" @selected(old('tipo_personal') == $codigo)>
                                        {{ $desc }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Tipo Cargo -->
                        <div class="space-y-2">
                            <label for="tipo_cargo" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                Tipo de Cargo *
                            </label>
                            <select id="tipo_cargo" name="tipo_cargo" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cargo Especifico solicitado -->
                        <div class="space-y-2">
                            <label for="cargo_solicitado" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-briefcase mr-2 text-M2"></i>
                                Cargo solicitado *
                            </label>
                            <select id="cargo_solicitado" name="cargo_solicitado" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Ubicación servicio -->
                        {{-- <div class="space-y-2">
                        <label for="ubicacion_servicio" class="block text-sm font-semibold text-gray-700">
                            <i class="fa-solid fa-map-location-dot mr-2 text-blue-500"></i>
                            Ubicación del Servicio *
                        </label>
                        <input type="text" name="ubicacion_servicio" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                        </div> --}}

                        <!-- Fechas y urgencia -->
                        <div class="space-y-2">
                            <label for="cliente" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                                Fecha de Inicio *
                            </label>
                            <input type="date" name="fecha_inicio" id="fecha_inicio"
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                required>
                        </div>

                        <div class="space-y-2">
                            <label for="cliente" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                                Fecha Fin *
                            </label>
                            <input type="date" name="fecha_fin" id="fecha_fin"
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                required>
                        </div>

                        <!-- Urgencia automática por fechas -->
                        <div id="urgenciaAutoBox" class="mt-2">
                            <div id="urgenciaAuto"
                                class="text-sm rounded-lg px-4 py-3 font-semibold text-center transition-all duration-300 bg-gray-200 text-gray-700">
                                NO SE SELECCIONÓ LA FECHA
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="urgencia" class="block text-sm font-semibold text-M3">
                                <i class="fas fa-exclamation-triangle mr-2 text-M2"></i>
                                Urgencia *
                            </label>
                            <select name="urgencia" id="urgencia" required
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 bg-gray-200 text-gray-700"
                                readonly tabindex="-1" style="pointer-events: none;">
                                <option value="">NO HAY URGENCIA</option>
                                <option value="Alta">Alta (1 semana)</option>
                                <option value="Media">Media (2 semanas)</option>
                                <option value="Baja">Baja (1 mes)</option>
                                <option value="Mayor">Plazo mayor a 1 mes</option>
                                <option value="Invalida">¡Fechas inválidas!</option>
                            </select>
                        </div>


                        <!-- Cantidad requerida -->
                        <div class="space-y-2">
                            <label for="cantidad_requerida" class="block text-sm font-semibold text-M3">
                                <i class="fa-solid fa-users mr-2 text-M2"></i>
                                Cantidad requerida *
                            </label>
                            <input type="number" id="cantidad_requerida" name="cantidad_requerida" required
                                min="1" max="999"
                                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Número de personas">
                            <span id="error-cantidad" class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cantidad por sexo -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-M3">
                                <i class="fa-solid fa-venus-mars text-M2"></i>
                                Sexo requerido *
                            </label>
                            <div class="flex gap-2">
                                <input type="number" id="cantidad_masculino" name="cantidad_masculino"
                                    placeholder="Masculino" min="0" max="999"
                                    class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                    required>
                                <input type="number" id="cantidad_femenino" name="cantidad_femenino"
                                    placeholder="Femenino" min="0" max="999"
                                    class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                    required>
                            </div>
                            <span id="error-sexo" class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Requisitos del Puesto -->
                <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-M2">Perfil</h2>
                            <p class="text-sm text-M3">Especificaciones y requisitos para el cargo</p>
                        </div>
                    </div>

                    <div class="space-y-6 w-full">
                        <!-- Edad mínima y máxima -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="edad_minima" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    Edad mínima
                                </label>
                                <input type="number" id="edad_minima" name="edad_minima" min="18" max="65"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                    placeholder="Ej: 21" required>
                            </div>
                            <div class="space-y-2">
                                <label for="edad_maxima" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    Edad máxima
                                </label>
                                <input type="number" id="edad_maxima" name="edad_maxima" min="18" max="70"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                    placeholder="Ej: 45" required>
                            </div>
                        </div>

                        <!-- CAMPOS SOLO PARA OPERATIVO -->
                        <div id="campos-operativo" class="space-y-2" style="display: none;">
                            <!-- Curso SUCAMEC vigente -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-id-card mr-2 text-green-500"></i>
                                    Curso SUCAMEC vigente *
                                </label>
                                <select id="curso_sucamec_operativo" name="curso_sucamec_operativo"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Seleccione...</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <!-- Carné SUCAMEC vigente -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-id-badge mr-2 text-green-500"></i>
                                    Carné SUCAMEC vigente *
                                </label>
                                <select id="carne_sucamec_operativo" name="carne_sucamec_operativo"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Seleccione...</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <!-- Licencia para portar armas (L4-L5) -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    Licencia para portar armas (L4-L5) *
                                </label>
                                <select id="licencia_armas" name="licencia_armas"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Seleccione...</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <!-- Licencia para portar armas (L4-L5) -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    Licencia de conducir *
                                </label>
                                <select id="requiere_licencia_conducir" name="requiere_licencia_conducir"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Seleccione...</option>
                                    <option value="si">Sí</option>
                                    <option value="no">No</option>
                                </select>
                            </div>
                            <!-- Servicio acuartelado -->
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-campground mr-2 text-green-500"></i>
                                    Servicio acuartelado *
                                </label>
                                <select id="servicio_acuartelado" name="servicio_acuartelado"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Seleccione el servicio</option>
                                    <option value="no">No</option>
                                    <option value="con_habitabilidad">Con habitabilidad</option>
                                    <option value="con_alimentacion">Con alimentación</option>
                                    <option value="con_movilidad">Con movilidad de traslado</option>
                                </select>
                            </div>
                        </div>

                        <!-- Experiencia mínima -->
                        <div class="space-y-2">
                            <label for="experiencia_minima" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Experiencia mínima *
                            </label>
                            <select id="experiencia_minima" name="experiencia_minima" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona experiencia</option>
                                <option value="6_meses">Sin experiencia</option>
                                <option value="1_anio">Menos de 1 año</option>
                                <option value="2_anios">Entre 1 y 2 años</option>
                                <option value="sin_experiencia">Entre 3 y 4 años</option>
                                <option value="sin_experiencia">Más de 4 años</option>
                            </select>
                        </div>

                        <!-- Grado académico mínimo requerido -->
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                Grado académico mínimo requerido *
                            </label>
                            <select id="grado_academico" name="grado_academico"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                required>
                                <option value="">Seleccione el grado</option>
                                <option value="secundaria">5to Grado de Secundaria</option>
                                <option value="ffaa_ffpp">Egresado de la FFAA/FFPP</option>
                                <option value="tecnica">Carrera Técnica</option>
                                <option value="universitaria">Carrera Universitaria</option>
                            </select>
                        </div>

                        <!-- Formación adicional -->
                        {{-- 
                        <div class="space-y-2">
                        <label for="nivel_estudios" class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                            Formacion adicional *
                        </label>
                        <select
                            id="formacion_adicional"
                            name="formacion_adicional"
                            required
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                            <option value="">Selecciona nivel</option>
                            @foreach ($niveles as $nive)
                            <option value="{{ $nive->NIED_CODIGO }}">{{ $nive->NIED_DESCRIPCION }}</option>
                        @endforeach
                        </select>
                        </div> --}}

                    </div>
                </div>

                <div class="grid lg:grid-cols-1 gap-6 ">
                    <!-- Validaciones y Remuneración -->
                    <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-M2">Remuneración y Beneficios</h2>
                            </div>
                        </div>

                        <div class="space-y-6 w-full">
                            <label for="sueldo_basico" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                                Sueldo básico (S/) *
                            </label>

                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 select-none">S/</span>
                                <input type="number" id="sueldo_basico" name="sueldo_basico" inputmode="decimal"
                                    min="0" step="0.01" placeholder="0.00" value="{{ old('sueldo_basico') }}"
                                    required
                                    class="form-input w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
                            </div>

                            <div class="space-y-2">
                                <label for="beneficios" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                                    Beneficios adicionales incluidos *
                                </label>
                                <select id="beneficios" name="beneficios" required
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
                                    <option value="">Selecciona</option>
                                    <option value="escala_a">Seguro de Salud</option>
                                    <option value="escala_b">CTS</option>
                                    <option value="escala_c">Vacaciones</option>
                                    <option value="escala_d">Asignación familiar</option>
                                    <option value="escala_d">Utilidades</option>
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Estado -->
                <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-5 flex flex-col items-start justify-start mx-auto w-full">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-M2">Estado</h2>
                            <p class="text-sm text-M3">Define el estado actual de la solicitud</p>
                        </div>
                    </div>
                    <!-- ESTADO -->
                    <div class="space-y-2 w-full">
                        <label for="estado" class="block text-sm font-medium text-gray-700">Estado del
                            Requerimiento</label>
                        <select id="estado" name="estado" required
                            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
                            <option value="">Selecciona un estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}" {{ old('estado') == $estado->id ? 'selected' : '' }}>
                                    {{ $estado->nombre }}
                                </option>
                            @endforeach
                        </select>
                        <span class="error-message text-red-500 text-sm hidden"></span>
                    </div>
                </div>
            </div>


            <!-- Save Button -->
            <div class="flex justify-center">
                <button type="submit"
                    class="btn-primary bg-blue-600 hover:bg-blue-700 text-white px-16 py-4 rounded-lg font-semibold text-lg flex items-center space-x-3 shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-save text-xl"></i>
                    <span>Guardar Requerimiento</span>
                </button>
            </div>
        </form>
        <x-alerts /> {{-- SweetAlert success / error --}}

        <!-- Loading Overlay -->
        <div id="loading-overlay"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Guardando requerimiento...</span>
            </div>
        </div>


        <style>
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
            }
        </style>

        <script>
            function validateForm() {
                const requiredFields = document.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    const errorMessage = field.parentElement.querySelector('.error-message');
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        if (errorMessage) {
                            errorMessage.textContent = 'Este campo es obligatorio';
                            errorMessage.classList.remove('hidden');
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                        if (errorMessage) {
                            errorMessage.classList.add('hidden');
                        }
                    }
                });

                return isValid;
            }


            document.addEventListener('DOMContentLoaded', function() {
                // Selecciona los campos
                const cantidadRequerida = document.getElementById('cantidad_requerida');
                const cantidadMasculino = document.getElementById('cantidad_masculino');
                const cantidadFemenino = document.getElementById('cantidad_femenino');
                const errorCantidad = document.getElementById('error-cantidad');
                const errorSexo = document.getElementById('error-sexo');

                function validarSumaSexo() {
                    // Valores a enteros (o 0)
                    const req = parseInt(cantidadRequerida.value) || 0;
                    const masc = parseInt(cantidadMasculino.value) || 0;
                    const fem = parseInt(cantidadFemenino.value) || 0;

                    // Reset errores
                    cantidadRequerida.classList.remove('border-red-500');
                    cantidadMasculino.classList.remove('border-red-500');
                    cantidadFemenino.classList.remove('border-red-500');
                    errorCantidad.classList.add('hidden');
                    errorSexo.classList.add('hidden');

                    if (req === 0 && (masc > 0 || fem > 0)) {
                        // Si no hay cantidad requerida pero sí en sexo
                        errorCantidad.textContent = "Primero indique la cantidad requerida.";
                        errorCantidad.classList.remove('hidden');
                        cantidadRequerida.classList.add('border-red-500');
                        return false;
                    }
                    if ((masc + fem) > req) {
                        errorSexo.textContent = `La suma (${masc + fem}) supera la cantidad requerida (${req}).`;
                        errorSexo.classList.remove('hidden');
                        cantidadMasculino.classList.add('border-red-500');
                        cantidadFemenino.classList.add('border-red-500');
                        return false;
                    }
                    if ((masc + fem) < req) {
                        errorSexo.textContent = `La suma (${masc + fem}) es menor que la cantidad requerida (${req}).`;
                        errorSexo.classList.remove('hidden');
                        cantidadMasculino.classList.add('border-red-500');
                        cantidadFemenino.classList.add('border-red-500');
                        return false;
                    }
                    // Si es igual está correcto
                    return true;
                }

                // Valida en cada cambio
                [cantidadRequerida, cantidadMasculino, cantidadFemenino].forEach(input => {
                    input.addEventListener('input', validarSumaSexo);
                });

                // Si usas validación al enviar formulario, puedes impedir submit si hay error
                const form = cantidadRequerida.closest('form');
                form.addEventListener('submit', function(e) {
                    if (!validarSumaSexo()) {
                        e.preventDefault();
                    }
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                const tipoPersonal = document.getElementById('tipo_personal');
                const camposOperativo = document.getElementById('campos-operativo');
                if (!tipoPersonal || !camposOperativo) return;

                // Si mañana agregas Operativo 5º (03), solo lo sumas aquí.
                const CODIGOS_OPERATIVO = ['01']; // 01 = Operativo 4º

                const inputsOperativo = camposOperativo.querySelectorAll('select, input, textarea');

                function toggleCamposOperativo() {
                    const esOperativo = CODIGOS_OPERATIVO.includes(tipoPersonal.value);

                    camposOperativo.style.display = esOperativo ? '' : 'none';

                    inputsOperativo.forEach(el => {
                        el.required = esOperativo;
                        if (!esOperativo) {
                            el.value = '';
                            // Si prefieres bloquearlos en lugar de limpiar, usa:
                            // el.disabled = true; (y quítalo cuando esOperativo)
                        } else {
                            // el.disabled = false;
                        }
                    });
                }

                tipoPersonal.addEventListener('change', toggleCamposOperativo);
                toggleCamposOperativo(); // inicial (por si vienes de "editar")
            });

            document.addEventListener('DOMContentLoaded', () => {
                const selSucursal = document.getElementById('sucursal');
                const selCliente = document.getElementById('cliente');
                const URL_CLIENTES = "{{ route('requerimientos.clientes_por_sucursal') }}"; // usa tu ruta al SP

                function setOptions(select, items, placeholder) {
                    select.innerHTML = '';
                    const first = document.createElement('option');
                    first.value = '';
                    first.textContent = placeholder;
                    select.appendChild(first);

                    items.forEach(x => {
                        const value = (x.CODIGO_CLIENTE ?? x.codigo_cliente ?? '').toString().trim();
                        const text = (x.NOMBRE_CLIENTE ?? x.nombre_cliente ?? value);
                        const op = document.createElement('option');
                        op.value = value;
                        op.textContent = text;
                        select.appendChild(op);
                    });
                }

                async function cargarClientesPorSucursal(codSucursal) {
                    if (!codSucursal) {
                        setOptions(selCliente, [], 'Selecciona un cliente');
                        return;
                    }
                    selCliente.innerHTML = '<option value="">Cargando...</option>';

                    try {
                        const u = new URL(URL_CLIENTES, window.location.origin);
                        u.searchParams.set('codigo_sucursal', codSucursal);

                        const res = await fetch(u, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await res.json();

                        if (!Array.isArray(data) || data.length === 0) {
                            setOptions(selCliente, [], 'Sin clientes para esta sucursal');
                            return;
                        }
                        setOptions(selCliente, data, 'Selecciona un cliente');
                    } catch (e) {
                        console.error(e);
                        setOptions(selCliente, [], 'Error al cargar');
                    }
                }

                if (selSucursal) {
                    selSucursal.addEventListener('change', () => {
                        cargarClientesPorSucursal(selSucursal.value || '');
                    });

                    // Si ya viene seleccionada (editar / volver con old inputs)
                    if (selSucursal.value) selSucursal.dispatchEvent(new Event('change'));
                }
            });


            document.addEventListener('DOMContentLoaded', function() {
                const fechaInicio = document.getElementById('fecha_inicio');
                const fechaFin = document.getElementById('fecha_fin');
                const urgenciaBox = document.getElementById('urgenciaAutoBox');
                const urgenciaDiv = document.getElementById('urgenciaAuto');
                const urgenciaSelect = document.getElementById('urgencia');

                function setUrgencia(valor, texto, colorClass) {
                    urgenciaDiv.textContent = texto;
                    urgenciaDiv.className =
                        'rounded-lg px-4 py-2 font-semibold text-center transition-all duration-300 ' + colorClass;
                    urgenciaSelect.value = valor;
                    urgenciaSelect.className =
                        'form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 ' +
                        colorClass;
                }

                function calcularUrgencia() {
                    if (fechaInicio.value && fechaFin.value) {
                        const inicio = new Date(fechaInicio.value);
                        const fin = new Date(fechaFin.value);
                        const diffMs = fin - inicio;
                        const diffDias = diffMs / (1000 * 60 * 60 * 24);

                        if (diffDias < 0) {
                            setUrgencia("Invalida", "¡Fechas inválidas!", "bg-gray-400 text-white");
                        } else if (diffDias <= 7) {
                            setUrgencia("Alta", "Nivel de urgencia: Alta (1 semana)", "bg-red-500 text-white");
                        } else if (diffDias > 7 && diffDias <= 14) {
                            setUrgencia("Media", "Nivel de urgencia: Media (2 semanas)", "bg-yellow-400 text-gray-900");
                        } else if (diffDias > 14 && diffDias <= 31) {
                            setUrgencia("Baja", "Nivel de urgencia: Baja (1 mes)", "bg-green-500 text-white");
                        } else {
                            setUrgencia("Mayor", "Plazo mayor a 1 mes", "bg-blue-400 text-white");
                        }
                    } else {
                        setUrgencia("", "NO SE SELECCIONÓ LA FECHA", "bg-gray-200 text-gray-700");
                    }
                }

                // Escuchar cambios en las fechas
                fechaInicio.addEventListener('change', calcularUrgencia);
                fechaFin.addEventListener('change', calcularUrgencia);

                // Inicializar estado al cargar
                calcularUrgencia();
            });

            // Real-time validation
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('border-red-500');
                        const errorMessage = this.parentElement.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.textContent = 'Este campo es obligatorio';
                            errorMessage.classList.remove('hidden');
                        }
                    } else {
                        this.classList.remove('border-red-500');
                        const errorMessage = this.parentElement.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.classList.add('hidden');
                        }
                    }
                });
            });

            document.getElementById('cliente').addEventListener('change', function() {
                let clienteId = this.value;
                let sedeSelect = document.getElementById('sede');
                sedeSelect.innerHTML = '<option value="">Cargando...</option>';

                if (clienteId) {
                    fetch('/requerimientos/sedes-por-cliente?codigo_cliente=' + clienteId)
                        .then(response => response.json())
                        .then(sedes => {
                            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                            sedes.forEach(sede => {
                                sedeSelect.innerHTML +=
                                    `<option value="${sede.CODIGO}">${sede.SEDE}</option>`;
                            });
                        })
                        .catch(() => {
                            sedeSelect.innerHTML = '<option value="">Error al cargar</option>';
                        });
                } else {
                    sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                }
            });


            document.addEventListener('DOMContentLoaded', async () => {
                const selTipoPersonal = document.getElementById('tipo_personal');
                const selTipoCargo = document.getElementById('tipo_cargo');
                const selCargoSolicitado = document.getElementById('cargo_solicitado');

                const URL_TIPOS = "{{ route('api.tipos_cargo') }}";
                const URL_CARGOS = "{{ route('api.cargos') }}";

                function setLoading(select, texto = 'Cargando...') {
                    select.innerHTML = `<option value="">${texto}</option>`;
                    select.disabled = true;
                }

                function setEmpty(select, texto = 'Seleccione...') {
                    select.innerHTML = `<option value="">${texto}</option>`;
                    select.disabled = true;
                }

                function fillSelect(select, data, placeholder = 'Seleccione...') {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    data.forEach(it => {
                        const o = document.createElement('option');
                        o.value = String(it.value).trim();
                        o.textContent = it.label;
                        select.appendChild(o);
                    });
                    select.disabled = data.length === 0;
                }

                async function cargarTiposCargo(tipoPersonal, preselect = null) {
                    if (!tipoPersonal) {
                        setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
                        setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                        return;
                    }
                    setLoading(selTipoCargo);
                    setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                    try {
                        const res = await fetch(
                            `${URL_TIPOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}`);
                        const data = await res.json(); // [{value,label}]
                        fillSelect(selTipoCargo, data, 'Seleccione el tipo de cargo');
                        if (preselect) {
                            selTipoCargo.value = preselect;
                            selTipoCargo.dispatchEvent(new Event('change'));
                        }
                    } catch {
                        setEmpty(selTipoCargo, 'Error al cargar');
                    }
                }

                async function cargarCargos(tipoPersonal, tipoCargo, preselect = null) {
                    if (!tipoPersonal || !tipoCargo) {
                        setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                        return;
                    }
                    setLoading(selCargoSolicitado);
                    try {
                        const res = await fetch(
                            `${URL_CARGOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}&tipo_cargo=${encodeURIComponent(tipoCargo)}`
                        );
                        const data = await res.json(); // [{value,label}]
                        fillSelect(selCargoSolicitado, data, 'Seleccione el cargo');
                        if (preselect) selCargoSolicitado.value = preselect;
                    } catch {
                        setEmpty(selCargoSolicitado, 'Error al cargar');
                    }
                }

                // Eventos
                selTipoPersonal.addEventListener('change', () => cargarTiposCargo(selTipoPersonal.value));
                selTipoCargo.addEventListener('change', () => cargarCargos(selTipoPersonal.value, selTipoCargo
                    .value));

                // Estado inicial
                setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
                setEmpty(selCargoSolicitado, 'Seleccione el cargo');

                // Precarga por old()
                const oldTipoPersonal = "{{ old('tipo_personal') }}";
                const oldTipoCargo = "{{ old('tipo_cargo') }}";
                const oldCargoSolicitado = "{{ old('cargo_solicitado') }}";
                if (oldTipoPersonal) {
                    selTipoPersonal.value = oldTipoPersonal;
                    await cargarTiposCargo(oldTipoPersonal, oldTipoCargo);
                    if (oldTipoCargo) {
                        await cargarCargos(oldTipoPersonal, oldTipoCargo, oldCargoSolicitado);
                    }
                }
            });



            // Update scale info based on selection
            document.getElementById('beneficios').addEventListener('change', function() {
                const infoBox = document.querySelector('.bg-blue-50 p strong');
                if (infoBox && this.value) {
                    infoBox.textContent = `Escala seleccionada: ${this.options[this.selectedIndex].text}`;
                }
            });
        </script>
    @endsection
