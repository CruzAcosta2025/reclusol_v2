@extends('layouts.public')

@section('content')
    <div class="min-h-screen gradient-bg py-6 sm:py-10 relative overflow-hidden">

        <!-- Decorative background -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute top-10 right-0 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>
        </div>

        <div class="relative z-10">
            <!-- Header -->
            <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8 mb-8">
                <div class="text-center">
                    <h1 class="text-3xl sm:text-5xl font-black mb-3 text-white tracking-tight">Formulario de Postulación</h1>
                    <p class="text-lg sm:text-xl text-slate-200 font-medium">Completa todos los campos para enviar tu postulación</p>
                </div>
            </div>

            {{-- 
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
                
                --}}

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

            <form method="POST" action="{{ route('postulantes.storeExterno') }}" enctype="multipart/form-data"
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="postulanteForm">
                @csrf
                <!-- Step 1: Información Personal -->
                <div id="step-1" class="form-step">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                        <div class="flex items-center mb-10 pb-6 border-b-2 border-blue-100">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-5 shadow-lg">
                                <i class="fas fa-user text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Información Personal</h2>
                                <p class="text-gray-600 font-medium">Ingresa tus datos personales básicos</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Fecha que postula -->
                            <div class="space-y-3">
                                <label for="fecha_postula" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    Fecha que postula <span class="text-red-600">*</span>
                                </label>
                                <input type="date" id="fecha_postula" name="fecha_postula" readonly
                                    value="{{ date('Y-m-d') }}"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-300 bg-white text-gray-900 rounded-lg focus:outline-none cursor-not-allowed font-bold">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>
                            <!-- DNI -->
                            <div class="space-y-3">
                                <label for="dni" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                    DNI <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{8}"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900"
                                    placeholder="12345678">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Nombres -->
                            <div class="space-y-3">
                                <label for="nombres" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>
                                    Nombres <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="nombres" name="nombres"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900"
                                    placeholder="Ingresa tus nombres">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Apellidos -->
                            <div class="space-y-3">
                                <label for="apellidos" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>
                                    Apellidos <span class="text-red-600">*</span>
                                </label>
                                <input type="text" id="apellidos" name="apellidos"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900"
                                    placeholder="Ingresa tus apellidos">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Fecha de Nacimiento -->
                            <div class="space-y-3">
                                <label for="fecha_nacimiento" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                    Fecha de Nacimiento <span class="text-red-600">*</span>
                                </label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Edad -->
                            <div class="space-y-3">
                                <label for="edad" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                    Edad <span class="text-red-600">*</span>
                                </label>
                                <input type="number" id="edad" name="edad" min="18" max="45"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900"
                                    placeholder="25">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Departamento -->
                            <div class="space-y-3">
                                <label for="departamento" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-city mr-2 text-blue-500"></i>
                                    Departamento <span class="text-red-600">*</span>
                                </label>
                                <select id="departamento" name="departamento"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Selecciona un departamento</option>
                                    @foreach ($departamentos as $codigo => $descripcion)
                                        <option value="{{ $codigo }}">{{ $descripcion }}</option>
                                    @endforeach
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Provincia -->
                            <div class="space-y-3">
                                <label for="provincia" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    Provincia <span class="text-red-600">*</span>
                                </label>
                                <select id="provincia" name="provincia"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Selecciona una provincia</option>
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Distrito -->
                            <div class="space-y-3">
                                <label for="distrito" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                    Distrito <span class="text-red-600">*</span>
                                </label>
                                <select id="distrito" name="distrito"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Selecciona un distrito</option>
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Nacionalidad -->
                            <div class="space-y-3">
                                <label for="nacionalidad" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-flag mr-2 text-blue-500"></i>
                                    Nacionalidad <span class="text-red-600">*</span>
                                </label>
                                <select id="nacionalidad" name="nacionalidad"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Selecciona tu nacionalidad</option>
                                    <option value="PERUANA">PERUANA</option>
                                    <option value="EXTRANJERA">EXTRANJERA</option>
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <div class="space-y-3">
                                <label for="grado_instruccion" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-graduation-cap mr-2 text-blue-500"></i>
                                    Grado de instrucción <span class="text-red-600">*</span>
                                </label>
                                <select id="grado_instruccion" name="grado_instruccion"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Selecciona el grado de instrucción</option>
                                    <option value="Universitaria">Universitaria</option>
                                    <option value="Carrera Técnica">Carrera Técnica</option>
                                    <option value="Egresado de las FFAA / FFPP">Egresado de las FFAA / FFPP</option>
                                    <option value="5º Grado de Secundaria">5º Grado de Secundaria</option>
                                </select>
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <!-- Celular -->
                            <div class="space-y-3">
                                <label for="celular" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-phone mr-2 text-blue-500"></i>
                                    Celular <span class="text-red-600">*</span>
                                </label>
                                <input type="tel" id="celular" name="celular" pattern="[0-9]{9}"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all duration-300 font-bold text-gray-900"
                                    placeholder="987654321">
                                <span class="error-message text-red-500 text-sm hidden"></span>
                            </div>

                            <div class="flex justify-end mt-10">
                                <button type="button" onclick="nextStep()"
                                    class="btn-primary px-10 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
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
                        <div class="flex items-center mb-10 pb-6 border-b-2 border-green-100">
                            <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center mr-5 shadow-lg">
                                <i class="fas fa-briefcase text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Información Profesional</h2>
                                <p class="text-gray-600 font-medium">Completa tu información laboral y educativa</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Vacante / Requerimiento --}}
                            <div class="space-y-3 md:col-span-2">
                                <label for="requerimiento_id" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-bullseye mr-2 text-green-500"></i>
                                    Puesto al que postula <span class="text-red-600">*</span>
                                </label>
                                <select id="requerimiento_id" name="requerimiento_id"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
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
                            <div class="space-y-3">
                                <label for="experiencia_rubro" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-clock mr-2 text-green-500"></i>
                                    Tiempo de experiencia en el cargo <span class="text-red-600">*</span>
                                </label>
                                <select id="experiencia_rubro" name="experiencia_rubro"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
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
                            <div class="space-y-3">
                                <label for="sucamec" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    Curso SUCAMEC vigente <span class="text-red-600">*</span>
                                </label>
                                <select id="sucamec" name="sucamec"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Seleccione...</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                                <x-input-error :messages="$errors->get('sucamec')" class="mt-2" />
                            </div>

                            <!-- Carné SUCAMEC -->
                            <div class="space-y-3">
                                <label for="carne_sucamec" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    Carné SUCAMEC vigente <span class="text-red-600">*</span>
                                </label>
                                <select id="carne_sucamec" name="carne_sucamec"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
                                    <option value="">Seleccione...</option>
                                    <option value="SI">SI</option>
                                    <option value="NO">NO</option>
                                </select>
                                <x-input-error :messages="$errors->get('carne_sucamec')" class="mt-2" />
                            </div>

                            <!-- Licencia de arma -->
                            <div class="space-y-3">
                                <label for="licencia_arma" class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-certificate mr-2 text-green-500"></i>
                                    Licencia de arma <span class="text-red-600">*</span>
                                </label>
                                <select id="licencia_arma" name="licencia_arma"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
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
                            <div class="space-y-3">
                                <label for="licencia_conducir"
                                    class="block text-sm font-bold text-gray-800">
                                    <i class="fas fa-id-card-alt mr-2 text-green-500"></i>
                                    Licencia de conducir A1 <span class="text-red-600">*</span>
                                </label>
                                <select id="licencia_conducir" name="licencia_conducir"
                                    class="form-input w-full px-4 py-3 border-2 border-gray-400 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 outline-none transition-all duration-300 font-bold text-gray-900">
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

                        <div class="flex justify-between mt-10 gap-4">
                            <button type="button" onclick="prevStep()"
                                class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Anterior</span>
                            </button>
                            <button type="button" onclick="nextStep()"
                                class="btn-primary px-8 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                                <span>Siguiente</span>
                                <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Documentos -->
                <div id="step-3" class="form-step hidden">
                    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8">
                        <div class="flex items-center mb-10 pb-6 border-b-2 border-purple-100">
                            <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center mr-5 shadow-lg">
                                <i class="fas fa-file-upload text-white text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl font-black text-gray-900">Documentos</h2>
                                <p class="text-gray-600 font-medium">Sube los documentos requeridos para tu postulación</p>
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

                        <div class="flex justify-between mt-10 gap-4">
                            <button type="button" onclick="prevStep()"
                                class="px-8 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
                                <i class="fas fa-arrow-left"></i>
                                <span>Anterior</span>
                            </button>
                            <button type="submit"
                                class="btn-primary px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg font-bold shadow-lg hover:shadow-xl transition-all duration-300 flex items-center space-x-2">
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

            // ========== VERIFICAR ERROR DE DUPLICADO AL CARGAR ==========
            document.addEventListener('DOMContentLoaded', function() {
                @if (session('duplicate_entry'))
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Postulación Duplicada',
                        html: `
                            <div class="text-left">
                                <p class="mb-3 font-bold text-lg">
                                    Ya existe una postulación registrada con este DNI.
                                </p>
                                <p class="text-sm text-gray-700 mb-2">
                                    Solo se permite una postulación por persona en el sistema.
                                </p>
                            </div>
                        `,
                        confirmButtonColor: '#dc2626',
                        confirmButtonText: 'Entendido',
                        width: 450,
                        padding: '2.5rem',
                        backdrop: true,
                    }).then(() => {
                        // Solo recargar la página
                        location.reload();
                    });
                @endif
            });

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

    </div>

    <style>
        .gradient-bg {
            background: radial-gradient(1200px circle at 20% 0%, rgba(99, 102, 241, .22), transparent 55%), radial-gradient(900px circle at 80% 20%, rgba(16, 185, 129, .16), transparent 55%), linear-gradient(135deg, #020617 0%, #0b1220 55%, #020617 100%);
        }

        .form-input {
            background-color: #ffffff !important;
            color: #000000 !important;
            border: 2px solid #d1d5db !important;
            font-weight: 700 !important;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: #6b7280 !important;
            opacity: 1 !important;
        }

        .form-input:focus {
            background-color: #ffffff !important;
            color: #000000 !important;
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        .form-input option {
            color: #000000 !important;
            background-color: #ffffff !important;
            font-weight: 600 !important;
        }

        .form-input:disabled {
            background-color: #f3f4f6 !important;
            color: #9ca3af !important;
            cursor: not-allowed;
        }

        .btn-primary {
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
        }
    </style>
@endsection
