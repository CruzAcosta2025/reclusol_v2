<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registro de Postulante - RECLUSOL</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

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
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen gradient-bg py-8">
        <!-- Back to Home Button -->
        <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al inicio</span>
        </a>

        <!-- Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="text-center text-white">
                <h1 class="text-4xl font-bold mb-2">Formulario de Postulación</h1>
                <p class="text-xl text-blue-100">Completa todos los campos para enviar tu postulación</p>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white/20 rounded-full h-2 backdrop-blur-sm">
                <div id="progress-bar" class="bg-yellow-400 h-2 rounded-full transition-all duration-500" style="width: 33.33%"></div>
            </div>
            <div class="flex justify-between mt-2 text-white text-sm">
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

        <form method="POST" action="{{ route('postulantes.store') }}" enctype="multipart/form-data" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" id="postulanteForm">
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
                        <!-- Nombres -->
                        <div class="space-y-2">
                            <label for="nombres" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Nombres *
                            </label>
                            <input
                                type="text"
                                id="nombres"
                                name="nombres"
                                required
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
                            <input
                                type="text"
                                id="apellidos"
                                name="apellidos"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tus apellidos">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- DNI -->
                        <div class="space-y-2">
                            <label for="dni" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                DNI *
                            </label>
                            <input
                                type="text"
                                id="dni"
                                name="dni"
                                required
                                maxlength="8"
                                pattern="[0-9]{8}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="12345678">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Edad -->
                        <div class="space-y-2">
                            <label for="edad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                Edad *
                            </label>
                            <input
                                type="number"
                                id="edad"
                                name="edad"
                                required
                                min="18"
                                max="65"
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
                            <select
                                id="departamento"
                                name="departamento"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona un departamento</option>
                                @foreach($departamentos as $dep)
                                <option value="{{ $dep->DEPA_CODIGO }}">{{ $dep->DEPA_DESCRIPCION }}</option>
                                @endforeach
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Ciudad 
                        <div class="space-y-2">
                            <label for="ciudad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-city mr-2 text-blue-500"></i>
                                Departamento *
                            </label>
                            <select
                                id="ciudad"
                                name="ciudad"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu ciudad</option>
                                <option value="lima">Lima</option>
                                <option value="chimbote">Chimbote</option>
                                <option value="trujillo">Trujillo</option>
                                <option value="moquegua">Moquegua</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div> -->

                        <!-- Distrito 
                        <div class="space-y-2">
                            <label for="distrito" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Provincia *
                            </label>
                            <input
                                type="text"
                                id="distrito"
                                name="distrito"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa tu distrito">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div> -->

                        <!-- Provincia -->
                        <div class="space-y-2">
                            <label for="provincia" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Provincia *
                            </label>
                            <select
                                id="provincia"
                                name="provincia"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona una provincia</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular -->
                        <div class="space-y-2">
                            <label for="celular" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>
                                Celular *
                            </label>
                            <input
                                type="tel"
                                id="celular"
                                name="celular"
                                required
                                pattern="[0-9]{9}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular de Referencia -->
                        <div class="space-y-2">
                            <label for="celular_referencia" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone-alt mr-2 text-blue-500"></i>
                                Celular de Referencia
                            </label>
                            <input
                                type="tel"
                                id="celular_referencia"
                                name="celular_referencia"
                                pattern="[0-9]{9}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321">
                        </div>

                        <!-- Estado Civil -->
                        <div class="space-y-2">
                            <label for="estado_civil" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-heart mr-2 text-blue-500"></i>
                                Estado Civil *
                            </label>
                            <select
                                id="estado_civil"
                                name="estado_civil"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu estado civil</option>
                                <option value="soltero">Soltero(a)</option>
                                <option value="casado">Casado(a)</option>
                                <option value="divorciado">Divorciado(a)</option>
                                <option value="viudo">Viudo(a)</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Nacionalidad -->
                        <div class="space-y-2">
                            <label for="nacionalidad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-flag mr-2 text-blue-500"></i>
                                Nacionalidad *
                            </label>
                            <select
                                id="nacionalidad"
                                name="nacionalidad"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu nacionalidad</option>
                                <option value="peruana">Peruana</option>
                                <option value="extranjera">Extranjera</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>

                    <div class="flex justify-end mt-8">
                        <button type="button" onclick="nextStep()" class="btn-primary px-8 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center space-x-2">
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
                        <!-- Cargo al que postula -->
                        <div class="space-y-2">
                            <label for="cargo" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user-tie mr-2 text-green-500"></i>
                                Cargo al que postula *
                            </label>
                            <select
                                id="cargo"
                                name="cargo"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona el cargo</option>
                                <option value="agente_seguridad">Agente de Seguridad</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="analista">Analista</option>
                                <option value="secretaria">Secretaria</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Fecha que postula -->
                        <div class="space-y-2">
                            <label for="fecha_postula" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-green-500"></i>
                                Fecha que postula *
                            </label>
                            <input
                                type="date"
                                id="fecha_postula"
                                name="fecha_postula"
                                required
                                value="{{ date('Y-m-d') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Tiempo de experiencia -->
                        <div class="space-y-2">
                            <label for="experiencia" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Tiempo de experiencia en el cargo *
                            </label>
                            <select
                                id="experiencia_rubro"
                                name="experiencia_rubro"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu experiencia</option>
                                <option value="sin_experiencia">Sin experiencia</option>
                                <option value="menos_1_año">Menos de 1 año</option>
                                <option value="1_2_años">1-2 años</option>
                                <option value="3_5_años">3-5 años</option>
                                <option value="mas_5_años">Más de 5 años</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- SUCAMEC -->
                        <div class="space-y-2">
                            <label for="sucamec" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                SUCAMEC vigente o no vigente *
                            </label>
                            <select
                                id="sucamec"
                                name="sucamec"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona estado SUCAMEC</option>
                                <option value="vigente">Vigente</option>
                                <option value="no_vigente">No vigente</option>
                                <option value="no_tiene">No tiene</option>
                            </select>
                            <x-input-error :messages="$errors->get('sucamec')" class="mt-2" />
                        </div>
                        <!-- Grado de instrucción -->
                        <div class="space-y-2">
                            <label for="grado_instruccion" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                Grado de instrucción *
                            </label>
                            <select
                                id="grado_instruccion"
                                name="grado_instruccion"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tu grado de instrucción</option>
                                <option value="primaria">Primaria</option>
                                <option value="secundaria">Secundaria</option>
                                <option value="tecnico">Técnico</option>
                                <option value="universitario">Universitario</option>
                                <option value="postgrado">Postgrado</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Servicio militar -->
                        <div class="space-y-2">
                            <label for="servicio_militar" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-medal mr-2 text-green-500"></i>
                                Servicio militar *
                            </label>
                            <select
                                id="servicio_militar"
                                name="servicio_militar"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona estado del servicio militar</option>
                                <option value="cumplido">Cumplido</option>
                                <option value="no_cumplido">No cumplido</option>
                                <option value="exonerado">Exonerado</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Licencia de arma -->
                        <div class="space-y-2">
                            <label for="licencia_arma" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-certificate mr-2 text-green-500"></i>
                                Licencia de arma LA *
                            </label>
                            <select
                                id="licencia_arma"
                                name="licencia_arma"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona estado de licencia</option>
                                <option value="vigente">Vigente</option>
                                <option value="vencida">Vencida</option>
                                <option value="no_tiene">No tiene</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Licencia de conducir -->
                        <div class="space-y-2">
                            <label for="licencia_conducir" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card-alt mr-2 text-green-500"></i>
                                Licencia de conducir *
                            </label>
                            <select
                                id="licencia_conducir"
                                name="licencia_conducir"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300">
                                <option value="">Selecciona tipo de licencia</option>
                                <option value="a1">A-I</option>
                                <option value="a2a">A-IIa</option>
                                <option value="a2b">A-IIb</option>
                                <option value="b1">B-I</option>
                                <option value="b2a">B-IIa</option>
                                <option value="b2b">B-IIb</option>
                                <option value="no_tiene">No tiene</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()" class="px-8 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior</span>
                        </button>
                        <button type="button" onclick="nextStep()" class="btn-primary px-8 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center space-x-2">
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
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cv" name="cv" accept=".pdf" data-max=5 required class="hidden" onchange="handleFileUpload(this, 'cv-preview')">
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
                                Certificado Único Laboral (DUL) *
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                                <input type="file" id="cul" name="cul" accept=".pdf" data-max=5 required class="hidden" onchange="handleFileUpload(this, 'cul-preview')">
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
                            <input type="checkbox" id="terms" name="terms" required class="mt-1 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="terms" class="text-sm text-gray-700">
                                <strong>Importante:</strong> Solo se aceptan archivos originales en formato digital. El usuario asume por completo la veracidad de los datos proporcionados.
                                <br><br>
                                Al enviar esta postulación, acepto los <a href="#" class="text-blue-600 hover:underline">términos y condiciones</a> y autorizo el tratamiento de mis datos personales.
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-between mt-8">
                        <button type="button" onclick="prevStep()" class="px-8 py-3 bg-gray-500 text-white rounded-lg font-semibold hover:bg-gray-600 transition-colors flex items-center space-x-2">
                            <i class="fas fa-arrow-left"></i>
                            <span>Anterior</span>
                        </button>
                        <button type="submit" class="btn-primary px-8 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition-colors flex items-center space-x-2">
                            <i class="fas fa-paper-plane"></i>
                            <span>Enviar Postulación</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <x-alerts /> {{-- SweetAlert success / error --}}


        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div>
                <span class="text-gray-700 font-medium">Enviando postulación...</span>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;
        // Todas las provincias cargadas desde el backend
        const provincias = @json($provincias);
        const distritos = @json($distritos);

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

        // Filtrar provincias al cambiar departamento
        document.getElementById('departamento').addEventListener('change', function() {
            const depaId = this.value.padStart(2, '0');
            const provinciaSelect = document.getElementById('provincia');

            provinciaSelect.innerHTML = '<option value="">Selecciona una provincia</option>';

            if (depaId) {
                const provinciasFiltradas = provincias.filter(p => p.DEPA_CODIGO === depaId);

                provinciasFiltradas.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p.PROVI_CODIGO;
                    option.textContent = p.PROVI_DESCRIPCION;
                    provinciaSelect.appendChild(option);
                });

                if (provinciasFiltradas.length === 0) {
                    const option = document.createElement('option');
                    option.value = "";
                    option.textContent = "No hay provincias para este departamento";
                    provinciaSelect.appendChild(option);
                }
            }
        });

        

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
</body>

</html>