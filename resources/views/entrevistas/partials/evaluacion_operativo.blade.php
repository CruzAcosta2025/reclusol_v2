{{-- resources/views/entrevistas/partials/evaluacion_operativo.blade.php --}}

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-teal-600 text-white px-6 py-4">
            <h2 class="flex items-center text-lg font-semibold">
                <i class="fas fa-user-shield mr-2"></i>
                Evaluación Específica - Personal Operativo (FAS-05-014)
            </h2>
        </div>

        <div class="p-6 space-y-8">
            {{-- EDUCACIÓN --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Nivel de Educación</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="edu_universitaria" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Universitaria</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="edu_carrera_tecnica" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Carrera Técnica</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="edu_ffaa_ffpp" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Egresado de las FFAA / FFPP</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="edu_secundaria" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">5° grado de secundaria</span>
                    </label>
                </div>
            </div>

            {{-- FORMACIÓN / CURSOS --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Formación / Cursos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="curso_sucamec" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">Curso SUCAMEC</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="curso_sgc" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGC</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="curso_sga" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGA</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="curso_sgsst" value="1" class="rounded mr-2">
                        <span class="text-sm text-gray-800">SGSST</span>
                    </label>
                </div>
            </div>

            {{-- EXPERIENCIA --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Experiencia en el cargo</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="exp_cargo_operativo" value="<1" class="mr-2">
                        <span class="text-sm text-gray-800">Menos de 1 año</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="exp_cargo_operativo" value="1-2" class="mr-2">
                        <span class="text-sm text-gray-800">Entre 1 y 2 años</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="exp_cargo_operativo" value="3-4" class="mr-2">
                        <span class="text-sm text-gray-800">Entre 3 y 4 años</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="radio" name="exp_cargo_operativo" value="4+" class="mr-2">
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
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales fortalezas observadas..."></textarea>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Oportunidades de mejora / Debilidades</h3>
                    <textarea name="debilidades" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-teal-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales debilidades u oportunidades de mejora..."></textarea>
                </div>
            </div>
        </div>
    </div>
</div>