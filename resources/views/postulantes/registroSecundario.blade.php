<x-app-layout>
    <div class="min-h-screen gradient-bg py-8">
        <!-- Back to Dashboard Button -->
        <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al Dashboard</span>
        </a>

        <!-- Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-plus text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Registro de Postulante</h1>
                        <p class="text-gray-600 mt-1">Información básica del postulante</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Colócalo justo antes de tu <form> o encima del botón Enviar --}}
        <!-- @if ($errors->any())
        <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
          <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
       @endif -->

        <form method="POST" action="{{ route('internos.postulantes.store') }}" enctype="multipart/form-data" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @csrf
            
            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <!-- Datos Personales -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Datos Personales</h2>
                            <p class="text-gray-600">Información básica del postulante</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Nombres -->
                        <div class="space-y-2">
                            <label for="nombres" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Nombres
                            </label>
                            <input 
                                type="text" 
                                id="nombres" 
                                name="nombres" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa los nombres"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Apellidos -->
                        <div class="space-y-2">
                            <label for="apellidos" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user mr-2 text-blue-500"></i>
                                Apellidos
                            </label>
                            <input 
                                type="text" 
                                id="apellidos" 
                                name="apellidos" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa los apellidos"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- DNI -->
                        <div class="space-y-2">
                            <label for="dni" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-id-card mr-2 text-blue-500"></i>
                                DNI
                            </label>
                            <input 
                                type="text" 
                                id="dni" 
                                name="dni" 
                                required 
                                maxlength="8"
                                pattern="[0-9]{8}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="12345678"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Edad -->
                        <div class="space-y-2">
                            <label for="edad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar mr-2 text-blue-500"></i>
                                Edad
                            </label>
                            <input 
                                type="number" 
                                id="edad" 
                                name="edad" 
                                required 
                                min="18" 
                                max="65"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="25"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Ciudad -->
                        <div class="space-y-2">
                            <label for="ciudad" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-city mr-2 text-blue-500"></i>
                                Ciudad
                            </label>
                            <select 
                                id="ciudad" 
                                name="ciudad" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona tu ciudad</option>
                                <option value="lima">Lima</option>
                                <option value="chimbote">Chimbote</option>
                                <option value="trujillo">Trujillo</option>
                                <option value="moquegua">Moquegua</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Distrito -->
                        <div class="space-y-2">
                            <label for="distrito" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Distrito
                            </label>
                            <input 
                                type="text" 
                                id="distrito" 
                                name="distrito" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Ingresa el distrito"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular -->
                        <div class="space-y-2">
                            <label for="celular" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone mr-2 text-blue-500"></i>
                                Celular
                            </label>
                            <input 
                                type="tel" 
                                id="celular" 
                                name="celular" 
                                required 
                                pattern="[0-9]{9}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Celular de Referencia -->
                        <div class="space-y-2">
                            <label for="celular_referencia" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-phone-alt mr-2 text-blue-500"></i>
                                Celular de referencia
                            </label>
                            <input 
                                type="tel" 
                                id="celular_referencia" 
                                name="celular_referencia" 
                                pattern="[0-9]{9}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="987654321"
                            >
                        </div>

                        <!-- Estado Civil -->
                        <div class="space-y-2">
                            <label for="estado_civil" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-heart mr-2 text-blue-500"></i>
                                Estado civil
                            </label>
                            <select 
                                id="estado_civil" 
                                name="estado_civil" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona estado civil</option>
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
                                Nacionalidad
                            </label>
                            <select 
                                id="nacionalidad" 
                                name="nacionalidad" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona nacionalidad</option>
                                <option value="peruana">Peruana</option>
                                <option value="extranjera">Extranjera</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Información Profesional -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-briefcase text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Información Profesional</h2>
                            <p class="text-gray-600">Datos laborales y educativos</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Cargo al que postula -->
                        <div class="space-y-2">
                            <label for="cargo" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user-tie mr-2 text-green-500"></i>
                                Cargo al que postula
                            </label>
                            <select 
                                id="cargo" 
                                name="cargo" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
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
                                Fecha que postula
                            </label>
                            <input 
                                type="date" 
                                id="fecha_postula" 
                                name="fecha_postula" 
                                required 
                                value="{{ date('Y-m-d') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Tiempo de experiencia -->
                        <div class="space-y-2">
                            <label for="experiencia_rubro" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-clock mr-2 text-green-500"></i>
                                Tiempo de experiencia en el cargo
                            </label>
                            <select 
                                id="experiencia_rubro" 
                                name="experiencia_rubro" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona experiencia</option>
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
                                SUCAMEC vigente o no vigente
                            </label>
                            <select 
                                id="sucamec" 
                                name="sucamec" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona estado SUCAMEC</option>
                                <option value="vigente">Vigente</option>
                                <option value="no_vigente">No vigente</option>
                                <option value="no_tiene">No tiene</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Grado de instrucción -->
                        <div class="space-y-2">
                            <label for="grado_instruccion" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                Grado de instrucción
                            </label>
                            <select 
                                id="grado_instruccion" 
                                name="grado_instruccion" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona grado de instrucción</option>
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
                                Servicio militar
                            </label>
                            <select 
                                id="servicio_militar" 
                                name="servicio_militar" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona estado servicio militar</option>
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
                                Licencia de arma LA
                            </label>
                            <select 
                                id="licencia_arma" 
                                name="licencia_arma" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona estado licencia</option>
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
                                Licencia de conducir
                            </label>
                            <select 
                                id="licencia_conducir" 
                                name="licencia_conducir" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona tipo licencia</option>
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
                </div>
            </div>

            <!-- Documentos -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 card-hover">
                <div class="flex items-center mb-6">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-file-upload text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Documentos</h2>
                        <p class="text-gray-600">Sube los documentos requeridos</p>
                    </div>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    <!-- CV Upload -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-file-pdf mr-2 text-purple-500"></i>
                            CV del Postulante *
                        </label>
                        <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            <input type="file" id="cv" name="cv" accept=".pdf" required class="hidden" onchange="handleFileUpload(this, 'cv-preview')">
                            <label for="cv" class="cursor-pointer">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-cloud-upload-alt text-purple-500 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 mb-2 font-medium">Subir CV (PDF)</p>
                                <p class="text-sm text-gray-500">Haz clic para seleccionar archivo</p>
                                <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX (máx. 5MB)</p>
                            </label>
                            <div id="cv-preview" class="mt-4 hidden">
                                <div class="flex items-center justify-center space-x-2 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="file-name font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CUL Upload -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700">
                            <i class="fas fa-certificate mr-2 text-purple-500"></i>
                            CUL del Postulante *
                        </label>
                        <div class="upload-area border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            <input type="file" id="cul" name="cul" accept=".pdf" required class="hidden" onchange="handleFileUpload(this, 'cul-preview')">
                            <label for="cul" class="cursor-pointer">
                                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="fas fa-cloud-upload-alt text-purple-500 text-2xl"></i>
                                </div>
                                <p class="text-gray-600 mb-2 font-medium">Subir CUL (PDF)</p>
                                <p class="text-sm text-gray-500">Haz clic para seleccionar archivo</p>
                                <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG (máx. 5MB)</p>
                            </label>
                            <div id="cul-preview" class="mt-4 hidden">
                                <div class="flex items-center justify-center space-x-2 text-green-600">
                                    <i class="fas fa-check-circle"></i>
                                    <span class="file-name font-medium"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700 text-white px-12 py-4 rounded-lg font-semibold text-lg flex items-center space-x-3 shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-save"></i>
                    <span>Guardar</span>
                </button>
            </div>
        </form>
        <x-alerts />   {{-- SweetAlert success / error --}}

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Guardando información...</span>
            </div>
        </div>
    </div>

    <script>
        function handleFileUpload(input, previewId) {
            const file = input.files[0];
            const preview = document.getElementById(previewId);
            const fileName = preview.querySelector('.file-name');

            if (file) {
                fileName.textContent = file.name;
                preview.classList.remove('hidden');
            }
        }

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

        // Form submission
        document.getElementById('applicant-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (validateForm()) {
                document.getElementById('loading-overlay').classList.remove('hidden');
                
                // Simulate form submission
                setTimeout(() => {
                    document.getElementById('loading-overlay').classList.add('hidden');
                    alert('¡Información guardada exitosamente!');
                    // Here you would normally submit the form
                    // this.submit();
                }, 2000);
            }
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

         function handleFileUpload(input, previewId) {
         const file      = input.files[0];
         const maxMB     = parseInt(input.dataset.max || "5", 10);
         const maxBytes  = maxMB * 1024 * 1024;
         const preview   = document.getElementById(previewId);
         const fileName  = preview.querySelector(".file-name");

         // Reiniciar estado
         preview.classList.add("hidden");
         input.classList.remove("border-red-500");
         if (fileName) fileName.textContent = "";

         if (!file) return;          // usuario canceló el diálogo

         if (file.size > maxBytes) {
         Swal.fire({
            icon: "error",
            title: "Archivo demasiado grande",
            text: `El archivo supera el límite de ${maxMB} MB. Por favor elige otro.`,
            width: 500,          // ancho exacto — 500 px
            heightAuto: true,    // (por defecto) ajusta alto al contenido
            padding: '2rem',     // espacio interior (≈ 32 px)
            confirmButtonColor: "#d33",
        }).then(() => {
            input.value = "";                   // forzar nueva selección
            input.classList.add("border-red-500");
        });
        return;
        }

        // Tamaño válido → mostrar nombre
        if (fileName) fileName.textContent = file.name;
        preview.classList.remove("hidden");
        }

    </script>
</x-app-layout>