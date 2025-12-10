@extends('layouts.app')
@section('module', 'postulantes')

@section('content')
    <div class="space-y-6">

        <!-- Encabezado del módulo -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">
                            Formulario de Postulación
                        </h2>
                        <p class="text-sm text-white/70 mt-1">
                            Complete todos los campos para registrar al postulante.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('postulantes.filtrar') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-list mr-2"></i>Ver Postulantes
                        </a>
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-gauge-high mr-2"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card glass-strong p-5 shadow-soft">
                <div class="flex items-center justify-between gap-3 flex-wrap mb-3">
                    <div class="text-sm font-semibold text-white">Progreso</div>
                    <div class="text-xs text-white/70">Etapas del registro</div>
                </div>

                <div class="w-full h-2 rounded-full bg-white/10 overflow-hidden">
                    <div id="progress-bar" class="h-2 rounded-full transition-all duration-500"
                        style="background: linear-gradient(135deg, rgba(250,204,21,.95), rgba(34,197,94,.95)); width: 33.33%;">
                    </div>
                </div>

                <div class="flex justify-between mt-2 text-white/80 text-xs sm:text-sm">
                    <span>Información Personal</span>
                    <span>Información Profesional</span>
                    <span>Documentos</span>
                </div>
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('postulantes.storeInterno') }}" enctype="multipart/form-data"
            class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="postulanteForm">
            @csrf
            <!-- Step 1: Información Personal -->
            <div id="step-1" class="form-step">
                <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Información Personal</h2>
                            <p class="text-gray-600">Ingresa tus datos personales básicos</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Fecha que postula -->
                        <div class="space-y-2">
                            <label for="fecha_postula" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha que postula *
                            </label>
                            <input type="date" id="fecha_postula" name="fecha_postula" readonly
                                value="{{ date('Y-m-d') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 bg-gray-100 text-gray-800 rounded-lg focus:outline-none cursor-not-allowed">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                        <!-- DNI -->
                        <div class="space-y-2">
                            <label for="dni" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                DNI *
                            </label>
                            <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{8}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="12345678">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Nombres -->
                        <div class="space-y-2">
                            <label for="nombres" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Nombres *
                            </label>
                            <input type="text" id="nombres" name="nombres"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tus nombres">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Apellidos -->
                        <div class="space-y-2">
                            <label for="apellidos" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Apellidos *
                            </label>
                            <input type="text" id="apellidos" name="apellidos"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tus apellidos">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Fecha que postula -->
                        <div class="space-y-2">
                            <label for="fecha_postula" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha de Nacimiento *
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Edad -->
                        <div class="space-y-2">
                            <label for="edad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                Edad *
                            </label>
                            <input type="number" id="edad" name="edad" min="18" max="45"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="25">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Departamento -->
                        <div class="space-y-2">
                            <label for="departamento" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-city mr-2 text-blue-500"></i>
                                Departamento *
                            </label>
                            <select id="departamento" name="departamento"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona un departamento</option>
                                @foreach ($departamentos as $codigo => $descripcion)
                                    <option value="{{ $codigo }}">{{ $descripcion }}</option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Provincia -->
                        <div class="space-y-2">
                            <label for="provincia" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Provincia *
                            </label>
                            <select id="provincia" name="provincia"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona una provincia</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Distrito -->
                        <div class="space-y-2">
                            <label for="distrito" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Distrito *
                            </label>
                            <select id="distrito" name="distrito"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona un distrito</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Nacionalidad -->
                        <div class="space-y-2">
                            <label for="nacionalidad" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-flag mr-2 text-blue-500"></i>
                                Nacionalidad *
                            </label>
                            <select id="nacionalidad" name="nacionalidad"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu nacionalidad</option>
                                <option value="PERUANA">PERUANA</option>
                                <option value="EXTRANJERA">EXTRANJERA</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="space-y-2">
                            <label for="estado_civil" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-heart mr-2 text-blue-500"></i>
                                Grado de instrucción *
                            </label>
                            <select id="grado_instruccion" name="grado_instruccion"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                                <option value="Universitaria">Universitaria</option>
                                <option value="Carrera Técnica">Carrera Técnica</option>
                                <option value="Egresado de las FFAA / FFPP">Egresado de las FFAA / FFPP</option>
                                <option value="5º Grado de Secundaria">5º Grado de Secundaria</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular -->
                        <div class="space-y-2">
                            <label for="celular" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>
                                Celular *
                            </label>
                            <input type="tel" id="celular" name="celular" pattern="[0-9]{9}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="button" onclick="nextStep()"
                                class="btn-primary px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center space-x-2">
                                <span>Siguiente</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Step 2: Información Profesional -->
            <div id="step-2" class="form-step hidden">
                <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-briefcase text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Información Profesional</h2>
                            <p class="text-gray-600">Completa tu información laboral y educativa</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        {{--
                        <div class="space-y-2">
                            <label for="tipo_cargo" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-briefcase mr-2 text-green-500"></i>
                                Tipo de Cargo *
                            </label>
                            <select id="tipo_cargo" name="tipo_cargo"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                                @foreach ($tipoCargos as $cod => $desc)
                                <option value="{{ $cod }}">{{ $desc }}</option>
                    @endforeach
                    </select>
                    <span class="error-message text-red-500 text-sm hidden"></span>
                </div>
                <!-- Cargo Especifico solicitado -->
                <div class="space-y-2">
                    <label for="cargo" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-briefcase mr-2 text-green-500"></i>
                        Cargo solicitado *
                    </label>
                    <select id="cargo" name="cargo"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                        <option value="">Selecciona el cargo</option>
                    </select>
                    <span class="error-message text-red-500 text-sm hidden"></span>
                </div>
                --}}

                        {{-- Vacante / Requerimiento --}}
                        <div class="space-y-2 md:col-span-2">
                            <label for="requerimiento_id" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-bullseye mr-2 text-green-500"></i>
                                Puesto al que postula
                            </label>
                            <select id="requerimiento_id" name="requerimiento_id"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona una vacante</option>
                                @foreach ($requerimientos as $req)
                                    <option value="{{ $req->id }}"
                                        data-tipo-personal="{{ $req->tipo_personal ?? $req->tipo_personal_codigo }}">
                                        {{ $req->label }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Tiempo de experiencia -->
                        <div class="space-y-2">
                            <label for="experiencia_rubro" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Tiempo de experiencia en el cargo *
                            </label>
                            <select id="experiencia_rubro" name="experiencia_rubro"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu experiencia</option>
                                <option value="Sin experiencia">Sin experiencia</option>
                                <option value="Menos de 1 año">Menos de 1 año</option>
                                <option value="Entre 1 y 2 años">Entre 1 y 2 años</option>
                                <option value="Entre 3 y 4 años">Entre 3 y 4 años</option>
                                <option value="Más de 4 años">Más de 4 años</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- SUCAMEC -->
                        <div class="space-y-2">
                            <label for="sucamec" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                Curso SUCAMEC vigente *
                            </label>
                            <select id="sucamec" name="sucamec"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Seleccione...</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                            <x-input-error :messages="$errors->get('sucamec')" class="mt-2" />
                        </div>

                        <!-- SUCAMEC -->
                        <div class="space-y-2">
                            <label for="sucamec" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                Carné SUCAMEC vigente *
                            </label>
                            <select id="carne_sucamec" name="carne_sucamec"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Seleccione...</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                            <x-input-error :messages="$errors->get('carne_sucamec')" class="mt-2" />
                        </div>

                        <!-- Licencia de arma -->
                        <div class="space-y-2">
                            <label for="licencia_arma" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-certificate mr-2 text-green-500"></i>
                                Licencia de arma*
                            </label>
                            <select id="licencia_arma" name="licencia_arma"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Seleccione...</option>
                                <option value="NO">NO</option>
                                <option value="L1">L1</option>
                                <option value="L2">L2</option>
                                <option value="L3">L3</option>
                                <option value="L4">L4</option>
                                <option value="L5">L5</option>
                                <option value="L6">L6</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Licencia de conducir -->
                        <div class="space-y-2">
                            <label for="licencia_conducir" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card-alt mr-2 text-green-500"></i>
                                Licencia de conducir A1 *
                            </label>
                            <select id="licencia_conducir" name="licencia_conducir"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Seleccione...</option>
                                <option value="NO">NO</option>
                                <option value="A_I">A-I</option>
                                <option value="A-IIa">A-IIa</option>
                                <option value="A-IIb">A-IIb</option>
                                <option value="A-IIIa">A-IIIa</option>
                                <option value="A-IIIB">A-IIIb</option>
                                <option value="A-IIc">A-IIC</option>
                                <option value="B-I">B-I</option>
                                <option value="B-IIa">B-IIa</option>
                                <option value="B-IIb">B-IIb</option>
                                <option value="B-IIC">B-IIC</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()"
                            class="px-8 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior</span>
                        </button>
                        <button type="button" onclick="nextStep()"
                            class="btn-primary px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center space-x-2">
                            <span>Siguiente</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Step 3: Documentos -->
            <div id="step-3" class="form-step hidden">
                <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                    <div class="flex items-center mb-8">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-file-upload text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Documentos</h2>
                            <p class="text-gray-600">Sube los documentos requeridos para tu postulación</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        <!-- CV Upload -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-file-pdf mr-2 text-purple-500"></i>
                                Curriculum Vitae/CV *
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cv" name="cv" accept=".pdf" data-max=5
                                    class="hidden" onchange="handleFileUpload(this, 'cv-preview')">
                                <label for="cv" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-2">Haz clic para subir tu CV</p>
                                    <p class="text-sm text-gray-500">PDF(máx. 5MB)</p>
                                </label>
                                <div id="cv-preview" class="mt-4 hidden">
                                    <div class="flex items-center justify-center space-x-2 text-green-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CUL Upload -->
                        <div class="space-y-4">
                            <label class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-certificate mr-2 text-purple-500"></i>
                                Certificado Único Laboral (CUL) *
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cul" name="cul" accept=".pdf" data-max=5
                                    class="hidden" onchange="handleFileUpload(this, 'cul-preview')">
                                <label for="cul" class="cursor-pointer">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-600 mb-2">Haz clic para subir tu CUL</p>
                                    <p class="text-sm text-gray-500">PDF(máx. 5MB)</p>
                                </label>
                                <div id="cul-preview" class="mt-4 hidden">
                                    <div class="flex items-center justify-center space-x-2 text-green-600">
                                        <i class="fas fa-check-circle"></i>
                                        <span class="file-name"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                        <div class="flex items-start space-x-3">
                            <label for="terms" class="text-sm text-gray-700">
                                <strong>Importante:</strong> Solo se aceptan archivos originales en formato PDF.
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()"
                            class="px-8 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior</span>
                        </button>
                        <button type="submit"
                            class="btn-primary px-8 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Enviar Postulación</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <x-alerts /> {{-- SweetAlert success / error --}}

        <!-- Loading Overlay -->
        <div id="loading-overlay"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                <span class="text-gray-700 font-medium">Enviando postulación...</span>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;


        document.addEventListener('DOMContentLoaded', function() {
            const depSelect = document.getElementById('departamento');
            const provSelect = document.getElementById('provincia');
            const distSelect = document.getElementById('distrito');

            const cache = {
                provincias: new Map(), // key: depCodigo => value: [{PROVI_CODIGO, PROVI_DESCRIPCION}, ...]
                distritos: new Map() // key: provCodigo => value: [{DIST_CODIGO, DIST_DESCRIPCION}, ...]
            };

            // Valores iniciales (edición / old())
            const initialDep = @json(old('departamento', isset($postulante) ? $postulante->departamento : null));
            const initialProv = @json(old('provincia', isset($postulante) ? $postulante->provincia : null));
            const initialDist = @json(old('distrito', isset($postulante) ? $postulante->distrito : null));

            // Helper: poblar select con items [{codeField, descField}, ...]
            function populate(selectEl, items, valueKey, textKey, selectedValue = null, emptyLabel =
                'Selecciona...') {
                selectEl.innerHTML = `<option value="">${emptyLabel}</option>`;
                if (!items || items.length === 0) {
                    return;
                }
                items.forEach(it => {
                    const opt = document.createElement('option');
                    opt.value = it[valueKey];
                    opt.textContent = it[textKey];
                    if (selectedValue && String(selectedValue) === String(it[valueKey])) opt.selected =
                        true;
                    selectEl.appendChild(opt);
                });
            }

            // Cargar provincias por departamento (usa cache si existe)
            async function loadProvincias(depCodigo, selectedProv = null) {
                provSelect.innerHTML = '<option value="">Cargando provincias...</option>';
                provSelect.disabled = true;
                distSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
                distSelect.disabled = true;

                if (!depCodigo) {
                    provSelect.innerHTML = '<option value="">Selecciona una provincia</option>';
                    provSelect.disabled = false;
                    return;
                }

                if (cache.provincias.has(depCodigo)) {
                    populate(provSelect, cache.provincias.get(depCodigo), 'PROVI_CODIGO', 'PROVI_DESCRIPCION',
                        selectedProv, 'Selecciona una provincia');
                    provSelect.disabled = false;
                    // si selectedProv existe, carga distritos asociados
                    if (selectedProv) await loadDistritos(selectedProv, initialDist);
                    return;
                }

                try {
                    const res = await fetch(`/api/provincias/${encodeURIComponent(depCodigo)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();
                    cache.provincias.set(depCodigo, data);
                    populate(provSelect, data, 'PROVI_CODIGO', 'PROVI_DESCRIPCION', selectedProv,
                        'Selecciona una provincia');
                    provSelect.disabled = false;
                    if (selectedProv) await loadDistritos(selectedProv, initialDist);
                } catch (err) {
                    console.error('Error cargando provincias:', err);
                    provSelect.innerHTML = '<option value="">Error cargando provincias</option>';
                    provSelect.disabled = false;
                }
            }

            // Cargar distritos por provincia (usa cache si existe)
            async function loadDistritos(provCodigo, selectedDist = null) {
                distSelect.innerHTML = '<option value="">Cargando distritos...</option>';
                distSelect.disabled = true;

                if (!provCodigo) {
                    distSelect.innerHTML = '<option value="">Selecciona un distrito</option>';
                    distSelect.disabled = false;
                    return;
                }

                if (cache.distritos.has(provCodigo)) {
                    populate(distSelect, cache.distritos.get(provCodigo), 'DIST_CODIGO', 'DIST_DESCRIPCION',
                        selectedDist, 'Selecciona un distrito');
                    distSelect.disabled = false;
                    return;
                }

                try {
                    const res = await fetch(`/api/distritos/${encodeURIComponent(provCodigo)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();
                    cache.distritos.set(provCodigo, data);
                    populate(distSelect, data, 'DIST_CODIGO', 'DIST_DESCRIPCION', selectedDist,
                        'Selecciona un distrito');
                    distSelect.disabled = false;
                } catch (err) {
                    console.error('Error cargando distritos:', err);
                    distSelect.innerHTML = '<option value="">Error cargando distritos</option>';
                    distSelect.disabled = false;
                }
            }

            // Event listeners
            depSelect?.addEventListener('change', function() {
                const dep = this.value;
                // al cambiar departamento, limpias provincia/distrito y cargas provincias del nuevo depto
                loadProvincias(dep, null);
            });

            provSelect?.addEventListener('change', function() {
                const prov = this.value;
                // al cambiar provincia, limpias distrito y cargas distritos
                loadDistritos(prov, null);
            });

            // Inicializar en carga: si hay valores preexistentes (edición / old()), los carga y selecciona
            (function init() {
                const depInicial = initialDep || depSelect?.value || null;
                const provInicial = initialProv || null;
                const distInicial = initialDist || null;

                if (depInicial) {
                    // setea el select departamento si no está marcado
                    if (depSelect && depSelect.value !== depInicial) {
                        try {
                            depSelect.value = depInicial;
                        } catch (e) {}
                    }
                    // Carga provincias y selecciona la provincia inicial (y luego distritos)
                    loadProvincias(depInicial, provInicial);
                }
            })();

        });


        document.addEventListener('DOMContentLoaded', function() {
            const reqSelect = document.getElementById('requerimiento_id');

            // Campos que solo aplican a personal operativo
            const opIds = [
                //'experiencia_rubro',
                'sucamec',
                'carne_sucamec',
                'licencia_arma',
                'licencia_conducir',
            ];

            function setOperativoUI(isOperativo) {
                opIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (!el) return;

                    const box = el.closest('.space-y-2') || el.parentElement;

                    if (isOperativo) {
                        // Mostrar y exigir
                        box.classList.remove('hidden');
                        el.disabled = false;
                        el.setAttribute('required', '');
                    } else {
                        // Ocultar y limpiar
                        box.classList.add('hidden');
                        el.disabled = true;
                        el.removeAttribute('required');
                        el.value = '';
                    }
                });
            }

            function syncByRequerimiento() {
                const opt = reqSelect?.selectedOptions[0];
                if (!opt) {
                    setOperativoUI(false);
                    return;
                }

                const tipoRaw = (opt.dataset.tipoPersonal || '').toString().toUpperCase();
                // Acepta tanto código como texto
                const isOperativo = ['01', '1', 'OPERATIVO'].includes(tipoRaw);
                setOperativoUI(isOperativo);
            }

            // Estado inicial: oculto hasta elegir vacante
            setOperativoUI(false);

            reqSelect?.addEventListener('change', syncByRequerimiento);

            // Si vienes de un "old()" o edición, sincroniza al cargar
            if (reqSelect && reqSelect.value) {
                syncByRequerimiento();
            }
        });


        document.getElementById('dni').addEventListener('blur', function() {
            const dni = this.value.trim();
            // Solo buscar si tiene 8 dígitos
            if (dni.length === 8 && /^\d{8}$/.test(dni)) {
                fetch(`/api/verificar-lista-negra/${dni}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.enListaNegra) {
                            // Muestra tu alerta aquí
                            Swal.fire({
                                icon: 'warning',
                                title: 'Atención',
                                text: data.mensaje,
                            });
                        }
                    });
            }
        });

        function updateProgressBar() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';
        }

        function nextStep() {
            console.log('Intentando avanzar del paso', currentStep, 'al', currentStep + 1);
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    console.log('Validación OK. Ocultando step-', currentStep, 'Mostrando step-', currentStep + 1);
                    document.getElementById(`step-${currentStep}`).classList.add('hidden');
                    currentStep++;
                    document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                    updateProgressBar();
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });

                }
            } else {
                console.log('Validación falló en step', currentStep);
            }
        }

        function prevStep() {
            if (currentStep > 1) {
                document.getElementById(`step-${currentStep}`).classList.add('hidden');
                currentStep--;
                document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                updateProgressBar();
                window.scrollTo(0, 0);
            }
        }

        function validateCurrentStep() {
            const currentStepElement = document.getElementById(`step-${currentStep}`);
            // Solo valida los required que NO son readonly
            const requiredFields = currentStepElement.querySelectorAll('[required]:not([readonly])');
            let isValid = true;

            requiredFields.forEach(field => {
                const errorMessage = field.parentElement.querySelector('.error-message');
                if (!field.value.trim()) {
                    console.log('Campo vacío:', field.name || field.id, field); // <--- agrega esto
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


        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const fileName = preview.querySelector('.file-name');

            if (file) {
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
            }
        }

        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            const maxMB = parseInt(input.dataset.max || "5", 10);
            const maxBytes = maxMB * 1024 * 1024;
            const preview = document.getElementById(previewId);
            const fileName = preview.querySelector(".file-name");

            // Reiniciar estado
            preview.classList.add("hidden");
            input.classList.remove("border-red-500");
            if (fileName) fileName.textContent = "";

            if (!file) return; // usuario canceló el diálogo

            if (file.size > maxBytes) {
                Swal.fire({
                    icon: "error",
                    title: "Archivo demasiado grande",
                    text: `El archivo supera el límite de ${maxMB} MB. Por favor elige otro.`,
                    width: 500, // ancho exacto — 500 px
                    heightAuto: true, // (por defecto) ajusta alto al contenido
                    padding: '2rem', // espacio interior (≈ 32 px)
                    confirmButtonColor: "#d33",
                }).then(() => {
                    input.value = ""; // forzar nueva selección
                    input.classList.add("border-red-500");
                });
                return;
            }

            // Tamaño válido → mostrar nombre
            if (fileName) fileName.textContent = file.name;
            preview.classList.remove("hidden");
        }
        // Initialize
        updateProgressBar();
    </script>

    <style>
        .form-input {
            transition: all 0.3s ease;
        }

        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .form-input,
        .form-select,
        .form-textarea {
            background-color: #ffffff;
            /* fondo blanco dentro del panel */
            color: #111827;
            /* texto gris oscuro */
            border: 1px solid #e5e7eb;
            /* borde suave */
        }

        .form-input::placeholder,
        .form-textarea::placeholder {
            color: #9ca3af;
            /* placeholder gris medio */
            opacity: 1;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 1px rgba(79, 70, 229, 0.3);
        }
    </style>

@endsection
