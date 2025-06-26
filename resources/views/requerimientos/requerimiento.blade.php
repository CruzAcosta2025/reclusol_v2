<x-app-layout>
    <div class="min-h-screen gradient-bg py-8">
        <!-- Back to Dashboard Button -->
        <a href="{{ route('dashboard') }}" class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al Dashboard</span>
        </a>

        <!-- Header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Nuevo Requerimiento</h1>
                        <p class="text-gray-600 mt-1">Complete la información para crear una nueva solicitud de personal</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Fecha:</p>
                        <p class="text-lg font-semibold text-gray-800">{{ now()->format('d/m/Y') }}</p>
                    </div>
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

        <form method="POST" action="{{ route('requerimientos.store') }}" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @csrf
            
            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <!-- Información General -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Información General</h2>
                            <p class="text-gray-600">Datos básicos del requerimiento de personal</p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Área Solicitante -->
                        <div class="space-y-2">
                            <label for="area_solicitante" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-building mr-2 text-blue-500"></i>
                                Área Solicitante *
                            </label>
                            <select 
                                id="area_solicitante" 
                                name="area_solicitante" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona el área</option>
                                <option value="recursos_humanos">Recursos Humanos</option>
                                <option value="operaciones">Operaciones</option>
                                <option value="administracion">Administración</option>
                                <option value="seguridad">Seguridad</option>
                                <option value="sistemas">Sistemas</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Sucursal -->
                        <div class="space-y-2">
                            <label for="sucursal" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                                Sucursal *
                            </label>
                            <select 
                                id="sucursal" 
                                name="sucursal" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona la sucursal</option>
                                <option value="lima">Lima</option>
                                <option value="chimbote">Chimbote</option>
                                <option value="trujillo">Trujillo</option>
                                <option value="moquegua">Moquegua</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cliente -->
                        <div class="space-y-2">
                            <label for="cliente" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-user-tie mr-2 text-blue-500"></i>
                                Cliente *
                            </label>
                            <input 
                                type="text" 
                                id="cliente" 
                                name="cliente" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Nombre del cliente"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cargo solicitado -->
                        <div class="space-y-2">
                            <label for="cargo_solicitado" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                                Cargo solicitado *
                            </label>
                            <select 
                                id="cargo_solicitado" 
                                name="cargo_solicitado" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona el cargo</option>
                                <option value="agente_seguridad">Agente de Seguridad</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="analista">Analista</option>
                                <option value="secretaria">Secretaria</option>
                                <option value="coordinador">Coordinador</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Cantidad requerida -->
                        <div class="space-y-2">
                            <label for="cantidad_requerida" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                Cantidad requerida *
                            </label>
                            <input 
                                type="number" 
                                id="cantidad_requerida" 
                                name="cantidad_requerida" 
                                required 
                                min="1"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                                placeholder="Número de personas"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Fecha Límite de Reclutamiento -->
                        <div class="space-y-2">
                            <label for="fecha_limite" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                Fecha Límite de Reclutamiento *
                            </label>
                            <input 
                                type="date" 
                                id="fecha_limite" 
                                name="fecha_limite" 
                                required 
                                min="{{ date('Y-m-d') }}"
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Requisitos del Puesto -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-clipboard-list text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Requisitos del Puesto</h2>
                            <p class="text-gray-600">Especificaciones y requisitos para el cargo</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Edad -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="edad_minima" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    Edad mínima
                                </label>
                                <input 
                                    type="number" 
                                    id="edad_minima" 
                                    name="edad_minima" 
                                    min="18" 
                                    max="65"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                                    placeholder="18"
                                >
                            </div>
                            <div class="space-y-2">
                                <label for="edad_maxima" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-calendar mr-2 text-green-500"></i>
                                    Edad máxima
                                </label>
                                <input 
                                    type="number" 
                                    id="edad_maxima" 
                                    name="edad_maxima" 
                                    min="18" 
                                    max="65"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                                    placeholder="65"
                                >
                            </div>
                        </div>

                        <!-- Checkboxes de licencias -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="flex items-center space-x-3">
                                    <input type="hidden" name="requiere_licencia_conducir" value="0">

                                <input 
                                    type="checkbox" 
                                    id="requiere_licencia_conducir" 
                                    name="requiere_licencia_conducir" 
                                    value="1"
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                >
                                <label for="requiere_licencia_conducir" class="text-sm font-medium text-gray-700">
                                    <i class="fas fa-id-card-alt mr-2 text-green-500"></i>
                                    Requiere licencia de conducir
                                </label>
                            </div>
                            <div class="flex items-center space-x-3">
                                    <input type="hidden" name="requiere_sucamec" value="0">

                                <input 
                                    type="checkbox" 
                                    id="requiere_sucamec" 
                                    name="requiere_sucamec" 
                                    value ="1"
                                    class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                >
                                <label for="requiere_sucamec" class="text-sm font-medium text-gray-700">
                                    <i class="fas fa-shield-alt mr-2 text-green-500"></i>
                                    Requiere SUCAMEC
                                </label>
                            </div>
                        </div>

                        <!-- Nivel de estudios y Experiencia -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label for="nivel_estudios" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-graduation-cap mr-2 text-green-500"></i>
                                    Nivel de estudios *
                                </label>
                                <select 
                                    id="nivel_estudios" 
                                    name="nivel_estudios" 
                                    required 
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300"
                                >
                                    <option value="">Selecciona nivel</option>
                                    <option value="primaria">Primaria</option>
                                    <option value="secundaria">Secundaria</option>
                                    <option value="tecnico">Técnico</option>
                                    <option value="universitario">Universitario</option>
                                    <option value="postgrado">Postgrado</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="experiencia_minima" class="block text-sm font-semibold text-gray-700">
                                    <i class="fas fa-clock mr-2 text-green-500"></i>
                                    Experiencia mínima *
                                </label>
                                <select 
                                    id="experiencia_minima" 
                                    name="experiencia_minima" 
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
                            </div>
                        </div>

                        <!-- Requisitos adicionales -->
                        <div class="space-y-2">
                            <label for="requisitos_adicionales" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-list mr-2 text-green-500"></i>
                                Requisitos adicionales *
                            </label>
                            <textarea 
                                id="requisitos_adicionales" 
                                name="requisitos_adicionales" 
                                rows="4"
                                required
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all duration-300 resize-none"
                                placeholder="Describe los requisitos adicionales para el puesto..."
                            ></textarea>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-2 gap-8 mb-8">
                <!-- Validaciones y Remuneración -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Validaciones y Remuneración</h2>
                            <p class="text-gray-600">Aprobaciones y escala salarial asociada</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Validado por Recursos Humanos -->
                        <div class="flex items-center space-x-3">
                            <input type="hidden" name="validado_rrhh" value="0">

                            <input 
                                type="checkbox" 
                                id="validado_rrhh" 
                                name="validado_rrhh" 
                                value="1"
                                class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500"
                            >
                            <label for="validado_rrhh" class="text-sm font-medium text-gray-700">
                                <i class="fas fa-check-circle mr-2 text-purple-500"></i>
                                Validado por Recursos Humanos
                            </label>
                        </div>

                        <!-- Escala remunerativa -->
                        <div class="space-y-2">
                            <label for="escala_remunerativa" class="block text-sm font-semibold text-gray-700">
                                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                                Escala remunerativa asociada *
                            </label>
                            <select 
                                id="escala_remunerativa" 
                                name="escala_remunerativa" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona escala</option>
                                <option value="escala_a">ESCALA A</option>
                                <option value="escala_b">ESCALA B</option>
                                <option value="escala_c">ESCALA C</option>
                                <option value="escala_d">ESCALA D</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>

                        <!-- Nota informativa -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-info-circle text-blue-400"></i>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>Escala seleccionada: ESCALA C</strong><br>
                                        Esta escala incluye beneficios de ley y bonificaciones según política de la empresa.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nivel de Prioridad -->
                <div class="bg-white rounded-2xl shadow-lg p-8 card-hover">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Nivel de Prioridad</h2>
                            <p class="text-gray-600">Urgencia del requerimiento</p>
                        </div>
                    </div>

                     <div class="space-y-2">
                            <select 
                                id="prioridad" 
                                name="prioridad" 
                                required 
                                class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300"
                            >
                                <option value="">Selecciona prioridad</option>
                                <option value="baja">Baja</option>
                                <option value="media">Media</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                            <span class="error-message text-red-500 text-sm hidden"></span>
                        </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-center">
                <button type="submit" class="btn-primary bg-blue-600 hover:bg-blue-700 text-white px-16 py-4 rounded-lg font-semibold text-lg flex items-center space-x-3 shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-save text-xl"></i>
                    <span>Guardar Requerimiento</span>
                </button>
            </div>
        </form>
        <x-alerts />   {{-- SweetAlert success / error --}}

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Guardando requerimiento...</span>
            </div>
        </div>
    </div>

    <style>
        .form-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.15);
        }
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

        // Update scale info based on selection
        document.getElementById('escala_remunerativa').addEventListener('change', function() {
            const infoBox = document.querySelector('.bg-blue-50 p strong');
            if (infoBox && this.value) {
                infoBox.textContent = `Escala seleccionada: ${this.options[this.selectedIndex].text}`;
            }
        });
    </script>
</x-app-layout>