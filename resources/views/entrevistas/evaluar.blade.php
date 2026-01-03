@extends('layouts.app')

@section('content')
        <div class="space-y-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                    <li class="inline-flex items-center">
                        <a href="{{ route('entrevistas.index') }}" class="inline-flex items-center text-sm font-medium text-body hover:text-fg-brand">
                            <i class="fas fa-calendar-check mr-2"></i>
                            Entrevistas
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center space-x-1.5">
                            <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 5 7 7-7 7" />
                            </svg>
                            <span class="inline-flex items-center text-sm font-medium text-body-subtle">Evaluar</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <x-block>
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-lg font-bold text-M2">Evaluación de Entrevista</h1>
                        <p class="text-sm text-M3">Complete la evaluación del postulante y determine su aptitud para el
                            puesto</p>
                    </div>
                </div>
            </x-block>

            <form id="evaluacion-form" method="POST"
                action="{{ route('entrevistas.guardar-evaluacion', ['postulante' => $postulante->id]) }}">
                @csrf
                {{-- Datos Generales --}}
                <div class="space-y-4">
                    <x-block class="flex-col">
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100">
                                <i class="fas fa-user text-blue-600"></i>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-M2">Información del Postulante</h2>
                                <p class="text-sm text-M3">Datos generales del postulante</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fas fa-user-circle text-gray-600"></i>
                                    </span>
                                    Nombres y Apellidos
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">{{ $postulante->nombres }}
                                        {{ $postulante->apellidos }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fas fa-briefcase text-gray-600"></i>
                                    </span>
                                    Puesto al que postula
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->puesto_postula ?? 'N.A' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fas fa-map-marker-alt text-gray-600"></i>
                                    </span>
                                    Lugar de Nacimiento
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->distrito_nombre ?? $postulante->distrito }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fa-solid fa-address-card text-gray-600"></i>
                                    </span>
                                    DNI
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->dni ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fas fa-calendar text-gray-600"></i>
                                    </span>
                                    Edad
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->edad ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fa-solid fa-phone text-gray-600"></i>
                                    </span>
                                    Telefono
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->celular ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fa-solid fa-calendar text-gray-600"></i>
                                    </span>
                                    Fecha que postula
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ \Carbon\Carbon::parse($postulante->fecha_postula)->format('d/m/Y') }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fa-solid fa-briefcase text-gray-600"></i>
                                    </span>
                                    Experiencia en el cargo
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->experiencia_rubro ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-sm font-medium text-body flex items-center gap-2">
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full bg-gray-100">
                                        <i class="fa-solid fa-graduation-cap text-gray-600"></i>
                                    </span>
                                    Nivel de educacion
                                </label>
                                <div class="px-4 py-3 bg-white/80 rounded-xl border border-neutral">
                                    <p class="text-sm font-semibold text-M2">
                                        {{ $postulante->grado_instruccion ?? 'No especificado' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </x-block>
                </div>

                {{-- Documentos --}}

                {{-- Evaluación específica según tipo de personal --}}
                @if ($esOperativo)
                    @include('entrevistas.partials.evaluacion_operativo')
                @else
                    @include('entrevistas.partials.evaluacion_administrativo')
                @endif

                @php
    $aptoPuesto = old('apto_puesto', $entrevista->es_apto ?? null);
    $comentariosEval = old(
        'comentarios_evaluacion',
        $entrevista->comentario ?? ($entrevista->comentario_final ?? ''),
    );
                @endphp

                {{-- Evaluación de Aptitud --}}
                <div class="max-w-7xl mx-auto mt-2 mb-8">
                    <x-block class="flex-col">
                        <div class="flex items-center gap-2">
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-purple-100">
                                <i class="fas fa-clipboard-check text-purple-600"></i>
                            </span>
                            <div>
                                <h2 class="text-lg font-semibold text-M2">Evaluación de Aptitud para el Puesto</h2>
                                <p class="text-sm text-M3">Determine la aptitud del postulante</p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <div class="space-y-6">
                                <div class="space-y-4">
                                    <label class="text-sm font-medium text-body">¿Es apto para el puesto solicitado?</label>
                                    <div class="space-y-3">
                                        {{-- SÍ --}}
                                        <label
                                            class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200 cursor-pointer hover:bg-green-100 transition">
                                            <input type="radio" name="apto_puesto" value="si"
                                                class="text-green-600 focus:ring-green-500"
                                                {{ $aptoPuesto === 'si' ? 'checked' : '' }}>
                                            <div class="ml-3 flex items-center">
                                                <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                                <span class="text-sm font-medium text-M2">Sí, es apto para el puesto</span>
                                            </div>
                                        </label>

                                        {{-- NO --}}
                                        <label
                                            class="flex items-center p-4 bg-red-50 rounded-xl border border-red-200 cursor-pointer hover:bg-red-100 transition">
                                            <input type="radio" name="apto_puesto" value="no"
                                                class="text-red-600 focus:ring-red-500"
                                                {{ $aptoPuesto === 'no' ? 'checked' : '' }}>
                                            <div class="ml-3 flex items-center">
                                                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                                <span class="text-sm font-medium text-M2">No es apto para el puesto</span>
                                            </div>
                                        </label>

                                        {{-- OTRO PUESTO --}}
                                        <label
                                            class="flex items-center p-4 bg-blue-50 rounded-xl border border-blue-200 cursor-pointer hover:bg-blue-100 transition">
                                            <input type="radio" name="apto_puesto" value="otro_puesto"
                                                class="text-blue-600 focus:ring-blue-500"
                                                {{ $aptoPuesto === 'otro_puesto' ? 'checked' : '' }}>
                                            <div class="ml-3 flex items-center">
                                                <i class="fas fa-exchange-alt text-blue-600 mr-2"></i>
                                                <span class="text-sm font-medium text-M2">Apto para otro puesto</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <div id="otro-puesto-section"
                                    class="{{ $aptoPuesto === 'otro_puesto' ? '' : 'hidden' }} space-y-2">
                                    <label class="text-sm font-medium text-body">Especifique el otro puesto:</label>
                                    <input type="text" name="otro_puesto_especifico"
                                        class="w-full px-4 py-3 border border-neutral rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 transition-colors"
                                        placeholder="Nombre del puesto alternativo"
                                        value="{{ old('otro_puesto_especifico', $entrevista->otro_puesto ?? '') }}">
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-sm font-medium text-body">Comentarios de la Evaluación</label>
                                <textarea name="comentarios_evaluacion" rows="8"
                                    class="w-full px-4 py-3 border border-neutral rounded-xl focus:border-purple-500 focus:ring-0 bg-white/80 transition-colors resize-none"
                                    placeholder="Escriba sus observaciones, fortalezas, debilidades y recomendaciones sobre el postulante...">{{ $comentariosEval }}</textarea>
                            </div>
                        </div>
                    </x-block>
                </div>

                {{-- Preguntas Extras --}}

                {{-- Botones de Acción --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
                        <div class="flex flex-col sm:flex-row gap-4 justify-end">
                            <button type="button" id="btn-guardar-borrador" onclick="guardarBorrador()"
                                class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors flex items-center justify-center space-x-2">
                                <i class="fas fa-save"></i>
                                <span>Guardar Borrador</span>
                            </button>
                            <button type="button" onclick="previewEvaluacion()"
                                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors flex items-center justify-center space-x-2">
                                <i class="fas fa-eye"></i>
                                <span>Vista Previa</span>
                            </button>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-check-circle"></i>
                                <span>Finalizar Evaluación</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Modal de Vista Previa --}}
            <div id="preview-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div
                        class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Vista Previa de la Evaluación</h3>
                        <button onclick="closePreviewModal()" class="text-white hover:text-gray-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div id="preview-content" class="p-6">
                        {{-- Contenido de vista previa se genera aquí --}}
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            // ------ Cálculo automático de remuneración ------
            function calcularTotal() {
                const sueldoBasico = parseFloat(document.querySelector('input[name="sueldo_basico"]').value) || 0;
                const bonificaciones = parseFloat(document.querySelector('input[name="bonificaciones"]').value) || 0;
                const total = sueldoBasico + bonificaciones;
                document.getElementById('total-remuneracion').textContent = `S/ ${total.toFixed(2)}`;
            }

            //document.querySelector('input[name="sueldo_basico"]').addEventListener('input', calcularTotal);
            //document.querySelector('input[name="bonificaciones"]').addEventListener('input', calcularTotal);

            // ------ Mostrar/ocultar campo de otro puesto ------
            document.querySelectorAll('input[name="apto_puesto"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const otroSection = document.getElementById('otro-puesto-section');
                    if (this.value === 'otro_puesto') {
                        otroSection.classList.remove('hidden');
                    } else {
                        otroSection.classList.add('hidden');
                    }
                });
            });

            // Al cargar la página, mostrar "otro puesto" si ya estaba seleccionado en BD
            document.addEventListener('DOMContentLoaded', function() {
                const seleccionado = @json($aptoPuesto);
                const otroSection = document.getElementById('otro-puesto-section');

                if (seleccionado === 'otro_puesto') {
                    otroSection.classList.remove('hidden');
                }
            });


            // ------ Guardar borrador ------
            function guardarBorrador() {
                const formData = new FormData(document.getElementById('evaluacion-form'));
                formData.append('borrador', '1');

                fetch('{{ route('entrevistas.guardar-evaluacion', ['postulante' => $postulante->id]) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Borrador guardado exitosamente');
                        } else {
                            alert('Error al guardar el borrador');
                        }
                    })
                    .catch(error => {
                        alert('Error al guardar el borrador');
                    });
            }

            // ------ Vista previa ------
            function previewEvaluacion() {
                const formData = new FormData(document.getElementById('evaluacion-form'));
                let previewHTML = `
                    <div class="space-y-6">
                        <div class="border-b pb-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Postulante</h4>
                            <p class="text-gray-600">{{ $postulante->nombres }} {{ $postulante->apellidos }}</p>
                            <p class="text-sm text-gray-500">{{ $postulante->cargo_nombre ?? $postulante->cargo }} - {{ $postulante->departamento_nombre ?? $postulante->departamento }}</p>
                        </div>

                        <div class="border-b pb-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Esquema Remunerativo</h4>
                            <p class="text-gray-600">Sueldo Básico: S/ ${formData.get('sueldo_basico') || '0.00'}</p>
                            <p class="text-gray-600">Bonificaciones: S/ ${formData.get('bonificaciones') || '0.00'}</p>
                            <p class="text-gray-600">Beneficios: ${formData.get('beneficios') || 'No especificado'}</p>
                        </div>

                        <div class="border-b pb-4">
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Evaluación de Aptitud</h4>
                            <p class="text-gray-600">Apto para el puesto: ${formData.get('apto_puesto') || 'No especificado'}</p>
                            ${formData.get('otro_puesto_especifico') ? `<p class="text-gray-600">Otro puesto: ${formData.get('otro_puesto_especifico')}</p>` : ''}
                            <p class="text-gray-600">Comentarios: ${formData.get('comentarios_evaluacion') || 'Sin comentarios'}</p>
                        </div>

                        <div>
                            <h4 class="text-lg font-semibold text-gray-800 mb-2">Preguntas Adicionales</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <p>Experiencia previa: ${formData.get('experiencia_previa') || 'No respondido'}</p>
                                <p>Disponibilidad inmediata: ${formData.get('disponibilidad_inmediata') || 'No respondido'}</p>
                                <p>Horarios rotativos: ${formData.get('horarios_rotativos') || 'No respondido'}</p>
                                <p>Disponibilidad para viajar: ${formData.get('disponibilidad_viajes') || 'No respondido'}</p>
                                <p>Herramientas tecnológicas: ${formData.get('herramientas_tecnologicas') || 'No respondido'}</p>
                                <p>Referencias laborales: ${formData.get('referencias_laborales') || 'No respondido'}</p>
                            </div>
                        </div>
                    </div>
                `;

                document.getElementById('preview-content').innerHTML = previewHTML;
                document.getElementById('preview-modal').classList.remove('hidden');
            }

            function closePreviewModal() {
                document.getElementById('preview-modal').classList.add('hidden');
            }

            // ------ Validación del formulario ------
            document.getElementById('evaluacion-form').addEventListener('submit', function(e) {
                const aptoPuesto = document.querySelector('input[name="apto_puesto"]:checked');

                if (!aptoPuesto) {
                    e.preventDefault();
                    alert('Por favor, seleccione si el postulante es apto para el puesto.');
                    return;
                }

                if (aptoPuesto.value === 'otro_puesto') {
                    const otroPuesto = document.querySelector('input[name="otro_puesto_especifico"]').value;
                    if (!otroPuesto.trim()) {
                        e.preventDefault();
                        alert('Por favor, especifique el otro puesto para el cual es apto el postulante.');
                        return;
                    }
                }
            });

            // ------ Cerrar modal al hacer clic fuera ------
            document.getElementById('preview-modal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closePreviewModal();
                }
            });

            async function guardarBorrador() {
                const form = document.getElementById('evaluacion-form');
                if (!form) return;

                const url = form.action + '?borrador=1'; // usamos el mismo action pero marcando borrador
                const data = new FormData(form);

                try {
                    const res = await fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: data
                    });

                    if (!res.ok) {
                        let msg = 'Error al guardar el borrador.';
                        try {
                            const json = await res.json();
                            if (json.message) msg = json.message;
                        } catch (e) {}
                        Swal.fire({
                            icon: 'error',
                            title: 'Ups...',
                            text: msg,
                        });
                        return;
                    }

                    const json = await res.json();

                    if (json.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Borrador guardado',
                            text: 'La evaluación se guardó como borrador correctamente.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Atención',
                            text: 'No se pudo guardar el borrador, revisa los datos.',
                        });
                    }

                } catch (err) {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error inesperado',
                        text: 'Ocurrió un problema al guardar el borrador.',
                    });
                }
            }
        </script>
        </div>
@endsection
