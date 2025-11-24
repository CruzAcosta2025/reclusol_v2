{{-- resources/views/entrevistas/partials/evaluacion_administrativo.blade.php --}}

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-4">
            <h2 class="flex items-center text-lg font-semibold">
                <i class="fas fa-user-tie mr-2"></i>
                Evaluación Específica - Personal Administrativo (FAS-05-019)
            </h2>
        </div>

        <div class="p-6 space-y-8">
            {{-- EDUCACIÓN --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Nivel de Educación</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_edu_titulado" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Titulado / Colegiado</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_edu_egresado" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Egresado</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_edu_est_universitario" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Estudiante universitario</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_edu_est_tecnico" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Estudiante de carrera técnica</span>
                    </label>
                    <div class="flex items-center p-2 bg-gray-50 rounded-lg border md:col-span-2">
                        <input type="checkbox" name="adm_edu_otro_flag" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800 mr-2">Otro:</span>
                        <input type="text" name="adm_edu_otro"
                            class="flex-1 text-sm border-0 bg-transparent focus:ring-0"
                            placeholder="Especifique">
                    </div>
                </div>
            </div>

            {{-- FORMACIÓN / CURSOS --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Formación / Cursos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_curso_sgc" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGC</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_curso_sga" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGA</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="adm_curso_sgsst" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGSST</span>
                    </label>
                    <div class="flex items-center p-2 bg-gray-50 rounded-lg border md:col-span-2 lg:col-span-3">
                        <input type="checkbox" name="adm_curso_otros_flag" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800 mr-2">Otros cursos / especializaciones:</span>
                        <input type="text" name="adm_curso_otros"
                            class="flex-1 text-sm border-0 bg-transparent focus:ring-0"
                            placeholder="Especifique">
                    </div>
                </div>
            </div>

            {{-- EXPERIENCIA --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Experiencia en el puesto / área</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="adm_exp_rango" value="<6m" class="mr-2">
                        <span class="text-sm text-gray-800">Menos de 6 meses</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="adm_exp_rango" value="1a" class="mr-2">
                        <span class="text-sm text-gray-800">Alrededor de 1 año</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="adm_exp_rango" value="2-3a" class="mr-2">
                        <span class="text-sm text-gray-800">Entre 2 y 3 años</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="adm_exp_rango" value="4+a" class="mr-2">
                        <span class="text-sm text-gray-800">Más de 4 años</span>
                    </label>
                </div>
            </div>

            {{-- HABILIDADES / COMPETENCIAS --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Competencias y habilidades observadas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_trabajo_equipo" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Trabajo en equipo</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_bajo_presion" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Trabajo bajo presión</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_comunicacion" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Comunicación</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_organizacion" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Organización</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_proactividad" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Proactividad</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="hab_responsabilidad" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Responsabilidad</span>
                    </label>
                </div>
            </div>

            {{-- FORTALEZAS / DEBILIDADES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Fortalezas del postulante</h3>
                    <textarea name="fortalezas" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales fortalezas observadas..."></textarea>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Oportunidades de mejora / Debilidades</h3>
                    <textarea name="debilidades" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales debilidades u oportunidades de mejora..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>