@extends('layouts.app')

@section('content')
<div class="font-sans antialiased">
    <div class="min-h-screen gradient-bg py-5 sm:py-8 relative">
        <!-- Volver solo en móvil -->
        <div class="block sm:hidden mb-4">
            <a href="{{ route('welcome') }}"
                class="flex items-center w-max m1-2 bg-blue-600 px-2 py-2 rounded-lg text-white hover:text-yellow-300 transition-colors space-x-4 group">
                <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-medium">Volver al inicio</span>
            </a>
        </div>
        <!-- Volver solo escritorio -->
        <a href="{{ route('welcome') }}"
            class="hidden sm:flex absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al inicio</span>
        </a>
        <!-- Header -->
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            <div class="text-center text-white">
                <h1 class="text-2xl sm:text-4xl font-bold mb-2">Formulario de Postulación</h1>
                <p class="text-xl text-blue-100">Completa todos los campos para enviar tu postulación</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto px-2 sm:px-6 lg:px-8 mb-6">
            <div class="bg-white/20 rounded-full h-2 backdrop-blur-sm mb-3">
                <div id="progress-bar" class="bg-yellow-400 h-2 rounded-full transition-all duration-500"
                    style="width: 33.33%"></div>
            </div>
            <div
                class="flex flex-col sm:flex-row justify-between mt-2 text-xs sm:text-sm text-white text-center sm:text-left gap-1 sm:gap-0">
                <span>Información Personal</span>
                <span>Información Profesional</span>
                <span>Documentos</span>
            </div>
        </div>

        <!-- @if ($errors->any())
    <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                  <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
                  </ul>
                 </div>
    @endif -->

        <form method="POST" action="{{ route('postulantes.storeExterno') }}" enctype="multipart/form-data"
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
                            <label for="fecha_postula" class="block text-xs font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha que postula *
                            </label>
                            <input type="date" id="fecha_postula" name="fecha_postula" required readonly
                                value="{{ date('Y-m-d') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg focus:outline-none cursor-not-allowed">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- DNI -->
                        <div class="space-y-2">
                            <label for="dni" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                DNI *
                            </label>
                            <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{8}"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="12345678">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Nombres -->
                        <div class="space-y-2">
                            <label for="nombres" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Nombres *
                            </label>
                            <input type="text" id="nombres" name="nombres" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tus nombres">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Apellidos -->
                        <div class="space-y-2">
                            <label for="apellidos" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Apellidos *
                            </label>
                            <input type="text" id="apellidos" name="apellidos" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tus apellidos">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Fecha que postula -->
                        <div class="space-y-2">
                            <label for="fecha_nacimiento" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha de Nacimiento *
                            </label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Edad -->
                        <div class="space-y-2">
                            <label for="edad" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                Edad *
                            </label>
                            <input type="number" id="edad" name="edad" min="18" max="45" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="25">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Departamento -->
                        <div class="space-y-2">
                            <label for="departamento" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-city mr-2 text-blue-500"></i>
                                Departamento *
                            </label>
                            <select id="departamento" name="departamento" required
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
                            <label for="provincia" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Provincia *
                            </label>
                            <select id="provincia" name="provincia" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona una provincia</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Provincia -->
                        <div class="space-y-2">
                            <label for="distrito" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Distrito *
                            </label>
                            <select id="distrito" name="distrito" required
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
                            <select id="nacionalidad" name="nacionalidad" required
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
                            <select id="grado_instruccion" name="grado_instruccion" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                                <option value="universitaria">Universitaria</option>
                                <option value="carrera_tecnica">Carrera Técnica</option>
                                <option value="ffaa_ffpp">Egresado de las FFAA / FFPP</option>
                                <option value="5_secundaria">5º Grado de Secundaria</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular -->
                        <div class="space-y-2">
                            <label for="celular" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>
                                Celular *
                            </label>
                            <input type="tel" id="celular" name="celular" pattern="[0-9]{9}" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
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

                        <div class="space-y-2">
                            <label for="tipo_cargo" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-briefcase mr-2 text-green-500"></i>
                                Tipo de Cargo *
                            </label>
                            <select id="tipo_cargo" name="tipo_cargo" required
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
                            <label for="cargo" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-briefcase mr-2 text-green-500"></i>
                                Cargo solicitado *
                            </label>
                            <select id="cargo" name="cargo" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Tiempo de experiencia -->
                        <div class="space-y-2">
                            <label for="experiencia_rubro"
                                class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Tiempo de experiencia en el cargo *
                            </label>
                            <select id="experiencia_rubro" name="experiencia_rubro" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu experiencia</option>
                                <option value="sin_experiencia">Sin experiencia</option>
                                <option value="menos_1_año">Menos de 1 año</option>
                                <option value="1_2_años">Entre 1 y 2 años</option>
                                <option value="3_4_años">Entre 3 y 4 años</option>
                                <option value="mas_4_años">Más de 4 años</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- SUCAMEC -->
                        <div class="space-y-2">
                            <label for="sucamec" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                Curso SUCAMEC vigente *
                            </label>
                            <select id="sucamec" name="sucamec" required
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
                            <select id="carne_sucamec" name="carne_sucamec" required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Seleccione...</option>
                                <option value="SI">SI</option>
                                <option value="NO">NO</option>
                            </select>
                            <x-input-error :messages="$errors->get('sucamec')" class="mt-2" />
                        </div>

                        <!-- Licencia de arma -->
                        <div class="space-y-2">
                            <label for="licencia_arma" class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-certificate mr-2 text-green-500"></i>
                                Licencia de arma*
                            </label>
                            <select id="licencia_arma" name="licencia_arma" required
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
                            <label for="licencia_conducir"
                                class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card-alt mr-2 text-green-500"></i>
                                Licencia de conducir A1 *
                            </label>
                            <select id="licencia_conducir" name="licencia_conducir" required
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
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-file-pdf mr-2 text-purple-500"></i>
                                Curriculum Vitae/CV *
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cv" name="cv" accept=".pdf" data-max=5
                                    required class="hidden" onchange="handleFileUpload(this, 'cv-preview')">
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
                            <label class="block text-xs sm:text-sm font-semibold text-gray-700">
                                <i class="fas fa-certificate mr-2 text-purple-500"></i>
                                Certificado Único Laboral (CUL) *
                            </label>
                            <div
                                class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cul" name="cul" accept=".pdf" data-max=5
                                    required class="hidden" onchange="handleFileUpload(this, 'cul-preview')">
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
                            <input type="checkbox" id="terms" name="terms" required
                                class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="terms" class="text-sm text-gray-700">
                                <strong>Importante:</strong> Solo se aceptan archivos originales en formato PDF.<br>
                                Acepto los términos y condiciones.
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end mt-8 gap-3 sm:gap-4">
                        <button type="button" onclick="prevStep()"
                            class="flex items-center justify-center px-6 sm:px-8 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors space-x-2 w-full sm:w-auto">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior</span>
                        </button>
                        <button type="submit"
                            class="flex items-center justify-center px-6 sm:px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors space-x-2 w-full sm:w-auto btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span class="text-center">
                                Enviar<br class="hidden sm:block" />Postulación
                            </span>
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
            const initialDep = @json(old('departamento', isset($postulante) ? $postulante -> departamento : null));
            const initialProv = @json(old('provincia', isset($postulante) ? $postulante -> provincia : null));
            const initialDist = @json(old('distrito', isset($postulante) ? $postulante -> distrito : null));

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
                    const res = await fetch(`/api-publico/provincias/${encodeURIComponent(depCodigo)}`, {
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
                    const res = await fetch(`/api-publico/distritos/${encodeURIComponent(provCodigo)}`, {
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
            const tipoSelect = document.getElementById('tipo_cargo');
            const cargoSelect = document.getElementById('cargo');
            const cache = new Map();

            const initialTipo = @json(old('tipo_cargo', isset($postulante) ? $postulante -> tipo_cargo : null));
            const initialCargo = @json(old('cargo', isset($postulante) ? $postulante -> cargo : null));

            // === NUEVO: helpers para mostrar/ocultar los 4 campos ===
            const opIds = ['sucamec', 'carne_sucamec', 'licencia_arma', 'licencia_conducir'];

            function setOperativoUI(isOperativo) {
                opIds.forEach(id => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    const box = el.closest('.space-y-2') || el.parentElement;

                    if (isOperativo) {
                        box.style.display = '';
                        el.disabled = false;
                        el.setAttribute('required', '');
                    } else {
                        box.style.display = 'none';
                        el.disabled = true;
                        el.removeAttribute('required');
                        el.value = '';
                    }
                });
            }

            async function syncUIByCargo(codiCarg) {
                if (!codiCarg) {
                    setOperativoUI(false);
                    return;
                }
                try {
                    const res = await fetch(`/api-publico/cargo-tipo/${encodeURIComponent(codiCarg)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('HTTP ' + res.status);
                    const data = await res.json();
                    const isOperativo = String(data.cargo_tipo || '').padStart(2, '0') === '01';
                    setOperativoUI(isOperativo);
                } catch (e) {
                    console.error('Error obteniendo cargo_tipo:', e);
                    setOperativoUI(false);
                }
            }

            // === tu lógica existente para cargar cargos (no la toques) ===
            async function loadCargosForTipo(tipoCodigo, selectedCargo = null) {
                cargoSelect.innerHTML = '<option value="">Cargando...</option>';
                cargoSelect.disabled = true;

                if (!tipoCodigo) {
                    cargoSelect.innerHTML = '<option value="">Selecciona un cargo</option>';
                    cargoSelect.disabled = false;
                    setOperativoUI(false);
                    return;
                }

                if (cache.has(tipoCodigo)) {
                    populateOptions(cache.get(tipoCodigo), selectedCargo);
                    return;
                }

                try {
                    const res = await fetch(`/api-publico/cargos-por-tipo/${encodeURIComponent(tipoCodigo)}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    if (!res.ok) throw new Error('Error en la petición: ' + res.status);
                    const data = await res.json();
                    cache.set(tipoCodigo, data);
                    populateOptions(data, selectedCargo);
                } catch (err) {
                    console.error('No se pudieron cargar los cargos:', err);
                    cargoSelect.innerHTML = '<option value="">Error cargando cargos</option>';
                    cargoSelect.disabled = false;
                    setOperativoUI(false);
                }
            }

            function populateOptions(items, selectedCargo = null) {
                cargoSelect.innerHTML = '<option value="">Selecciona un cargo</option>';

                if (!items || items.length === 0) {
                    cargoSelect.innerHTML = '<option value="">No hay cargos para este tipo</option>';
                    cargoSelect.disabled = false;
                    setOperativoUI(false);
                    return;
                }

                items.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.CODI_CARG;
                    opt.textContent = item.DESC_CARGO;
                    cargoSelect.appendChild(opt);
                });

                if (selectedCargo) cargoSelect.value = String(selectedCargo);

                // << NUEVO: sincroniza UI con el cargo elegido (si ya hay uno)
                syncUIByCargo(cargoSelect.value);

                cargoSelect.disabled = false;
            }

            // Eventos
            tipoSelect?.addEventListener('change', function() {
                loadCargosForTipo(this.value, null);
            });

            cargoSelect?.addEventListener('change', function() {
                syncUIByCargo(this.value);
            });

            // Init
            setOperativoUI(false);
            (function initOnLoad() {
                const tipoInicial = initialTipo || tipoSelect?.value || null;
                const cargoInicial = initialCargo || null;
                if (tipoInicial) {
                    if (tipoSelect && tipoSelect.value !== tipoInicial) {
                        try {
                            tipoSelect.value = tipoInicial;
                        } catch (e) {}
                    }
                    loadCargosForTipo(tipoInicial, cargoInicial);
                }
            })();
        });



        function updateProgressBar() {
            const progress = (currentStep / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = progress + '%';
        }

        function nextStep() {
            if (validateCurrentStep()) {
                if (currentStep < totalSteps) {
                    document.getElementById(`step-${currentStep}`).classList.add('hidden');
                    currentStep++;
                    document.getElementById(`step-${currentStep}`).classList.remove('hidden');
                    updateProgressBar();
                    window.scrollTo(0, 0);
                }
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
            const requiredFields = currentStepElement.querySelectorAll('[required]');
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
        .gradient-bg {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #1d4ed8 100%);
        }

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
    </style>
    @endsection