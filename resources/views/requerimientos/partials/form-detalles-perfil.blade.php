<div>
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
