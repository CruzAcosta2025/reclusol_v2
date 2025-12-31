<div>
    <div class="space-y-5 w-full">
        <!-- Edad (rango) -->
        <div class="space-y-2">
            <label class="block text-xs font-medium text-M3">
                <i class="fas fa-calendar mr-2 text-M3"></i>
                Edad (rango) *
            </label>
            <div class="grid md:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label for="edad_minima" class="block text-xs text-neutral-dark">Mínima</label>
                    <input type="number" id="edad_minima" name="edad_minima" min="1" max="100"
                        class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                        placeholder="Ej: 18" required>
                </div>
                <div class="space-y-1">
                    <label for="edad_maxima" class="block text-xs text-neutral-dark">Máxima</label>
                    <input type="number" id="edad_maxima" name="edad_maxima" min="1" max="100"
                        class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                        placeholder="Ej: 45" required>
                </div>
            </div>
            <span id="edad-rango-error" class="error-message text-error text-xs hidden"></span>
        </div>

        <!-- CAMPOS SOLO PARA OPERATIVO -->
        <div id="campos-operativo" class="space-y-4" style="display: none;">
            <div class="rounded-lg border border-neutral bg-neutral-lightest px-4 py-3">
                <p class="text-xs font-semibold text-M2">Requisitos Operativo</p>
                <p class="text-xs text-neutral-dark">Completa estos campos solo si aplica.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4">
                <!-- Curso SUCAMEC vigente -->
                <div class="space-y-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-id-card mr-2 text-M3"></i>
                    Curso SUCAMEC vigente *
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="curso_sucamec_operativo" value="si"
                            class="h-4 w-4 text-M1 focus:ring-M1" required>
                        <span class="text-sm text-neutral-darker">Sí</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="curso_sucamec_operativo" value="no"
                            class="h-4 w-4 text-M1 focus:ring-M1">
                        <span class="text-sm text-neutral-darker">No</span>
                    </label>
                </div>
            </div>

            <!-- Carné SUCAMEC vigente -->
            <div class="space-y-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-id-badge mr-2 text-M3"></i>
                    Carné SUCAMEC vigente *
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="carne_sucamec_operativo" value="si"
                            class="h-4 w-4 text-M1 focus:ring-M1" required>
                        <span class="text-sm text-neutral-darker">Sí</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="carne_sucamec_operativo" value="no"
                            class="h-4 w-4 text-M1 focus:ring-M1">
                        <span class="text-sm text-neutral-darker">No</span>
                    </label>
                </div>
            </div>

            <!-- Licencia para portar armas (L4-L5) -->
            <div class="space-y-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-shield-alt mr-2 text-M3"></i>
                    Licencia para portar armas (L4-L5) *
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="licencia_armas" value="si"
                            class="h-4 w-4 text-M1 focus:ring-M1" required>
                        <span class="text-sm text-neutral-darker">Sí</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="licencia_armas" value="no" class="h-4 w-4 text-M1 focus:ring-M1">
                        <span class="text-sm text-neutral-darker">No</span>
                    </label>
                </div>
            </div>

            <!-- Licencia de conducir -->
            <div class="space-y-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-shield-alt mr-2 text-M3"></i>
                    Licencia de conducir *
                </label>
                <div class="grid grid-cols-2 gap-2">
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="requiere_licencia_conducir" value="si"
                            class="h-4 w-4 text-M1 focus:ring-M1" required>
                        <span class="text-sm text-neutral-darker">Sí</span>
                    </label>
                    <label class="flex items-center gap-2 rounded-lg border border-neutral bg-white px-3 py-2 cursor-pointer">
                        <input type="radio" name="requiere_licencia_conducir" value="no"
                            class="h-4 w-4 text-M1 focus:ring-M1">
                        <span class="text-sm text-neutral-darker">No</span>
                    </label>
                </div>
            </div>

            <!-- Servicio acuartelado -->
            <div class="space-y-2 md:col-span-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-campground mr-2 text-M3"></i>
                    Servicio acuartelado *
                </label>
                <select id="servicio_acuartelado" name="servicio_acuartelado"
                    class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
                    <option value="">Seleccione el servicio</option>
                    <option value="no">No</option>
                    <option value="con_habitabilidad">Con habitabilidad</option>
                    <option value="con_alimentacion">Con alimentación</option>
                    <option value="con_movilidad">Con movilidad de traslado</option>
                </select>
            </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <!-- Experiencia mínima -->
            <div class="space-y-2">
                <label for="experiencia_minima" class="block text-xs font-medium text-M3">
                    <i class="fas fa-clock mr-2 text-M3"></i>
                    Experiencia mínima *
                </label>
                <select id="experiencia_minima" name="experiencia_minima" required
                    class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
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
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-graduation-cap mr-2 text-M3"></i>
                    Grado académico mínimo requerido *
                </label>
                <select id="grado_academico" name="grado_academico"
                    class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                    required>
                    <option value="">Seleccione el grado</option>
                    <option value="secundaria">5to Grado de Secundaria</option>
                    <option value="ffaa_ffpp">Egresado de la FFAA/FFPP</option>
                    <option value="tecnica">Carrera Técnica</option>
                    <option value="universitaria">Carrera Universitaria</option>
                </select>
            </div>
        </div>
    </div>
</div>