{{-- resources/views/entrevistas/partials/evaluacion_administrativo.blade.php --}}

@php
$formacionSel = old('formacion', $entrevista->formacion ?? []);
if (!is_array($formacionSel)) {
$formacionSel = [];
}

$compData = $entrevista->competencias ?? [];
if (!is_array($compData)) {
$compData = [];
}

$competenciasSel = old('competencias', $compData['habilidades'] ?? []);
if (!is_array($competenciasSel)) {
$competenciasSel = [];
}

$fortalezasTexto = old('fortalezas', $entrevista->fortalezas ?? '');
$oportunidadesTexto = old('oportunidades', $entrevista->oportunidades ?? '');
$otrosCursosTexto = old('otros_cursos', $entrevista->otros_cursos ?? '');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-sky-500 to-sky-600 text-white px-6 py-4">
            <h2 class="flex items-center text-lg font-semibold">
                <i class="fas fa-user-tie mr-2"></i>
                Evaluación Específica - Personal Administrativo
            </h2>
        </div>

        <div class="p-6 space-y-8">
            {{-- FORMACIÓN / CURSOS --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Formación / Cursos</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="formacion[]" value="SGC" class="rounded mr-2"
                            {{ in_array('SGC', $formacionSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">SGC</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="formacion[]" value="SGA" class="rounded mr-2"
                            {{ in_array('SGA', $formacionSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">SGA</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="formacion[]" value="SGSST" class="rounded mr-2"
                            {{ in_array('SGSST', $formacionSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">SGSST</span>
                    </label>

                    <div class="flex items-center p-2 bg-gray-50 rounded-lg border md:col-span-2 lg:col-span-3">
                        <input type="checkbox" name="formacion[]" value="Otros cursos" class="rounded mr-2"
                            {{ in_array('Otros cursos', $formacionSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800 mr-2">Otros cursos / especializaciones:</span>
                        <input type="text" name="otros_cursos"
                            class="flex-1 text-sm border-0 bg-transparent focus:ring-0"
                            placeholder="Especifique"
                            value="{{ $otrosCursosTexto }}">
                    </div>
                </div>
            </div>

            {{-- HABILIDADES / COMPETENCIAS --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-3">Competencias y habilidades observadas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Trabajo en equipo" class="rounded mr-2"
                            {{ in_array('Trabajo en equipo', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Trabajo en equipo</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Trabajo bajo presion" class="rounded mr-2"
                            {{ in_array('Trabajo bajo presion', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Trabajo bajo presión</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Comunicacion" class="rounded mr-2"
                            {{ in_array('Comunicacion', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Comunicación</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Organizacion" class="rounded mr-2"
                            {{ in_array('Organizacion', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Organización</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Proactividad" class="rounded mr-2"
                            {{ in_array('Proactividad', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Proactividad</span>
                    </label>
                    <label class="flex items-center p-2 bg-gray-50 rounded-lg border hover:bg-gray-100 cursor-pointer">
                        <input type="checkbox" name="competencias[]" value="Responsabilidad" class="rounded mr-2"
                            {{ in_array('Responsabilidad', $competenciasSel, true) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-800">Responsabilidad</span>
                    </label>
                </div>
            </div>

            {{-- FORTALEZAS / OPORTUNIDADES --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Fortalezas del postulante</h3>
                    <textarea name="fortalezas" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales fortalezas observadas...">{{ $fortalezasTexto }}</textarea>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Oportunidades de mejora / Debilidades</h3>
                    <textarea name="oportunidades" rows="4"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-sky-500 focus:ring-0 bg-white/80 resize-none"
                        placeholder="Describa las principales debilidades u oportunidades de mejora...">{{ $oportunidadesTexto }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>