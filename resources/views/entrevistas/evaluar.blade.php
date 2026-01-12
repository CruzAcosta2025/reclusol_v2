@extends('layouts.app')
@section('module', 'entrevistas')

@section('content')
<div class="space-y-6">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Encabezado --}}
        <div class="card glass-strong p-6 shadow-soft">
            <div class="flex items-start justify-between gap-4 flex-wrap">
                <div class="min-w-0">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">Evaluación de Entrevista</h1>
                    <p class="text-sm text-white/70 mt-1">Complete la evaluación del postulante y determine su aptitud para el
                        puesto</p>
                </div>

                <div class="flex items-center gap-2 flex-wrap">
                    <a href="{{ route('postulantes.formInterno') }}"
                        class="px-4 py-2 rounded-xl font-extrabold text-sm text-white"
                        style="background:linear-gradient(135deg,#3b82f6,#4f46e5);">
                        <i class="fas fa-user-plus mr-2"></i>Crear Postulante
                    </a>
                    <a href="{{ route('dashboard') }}"
                        class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition text-white">
                        <i class="fas fa-gauge-high mr-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <form id="evaluacion-form" method="POST"
        action="{{ route('entrevistas.guardar-evaluacion', ['postulante' => $postulante->id]) }}">
        @csrf
        {{-- Datos Generales --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4">
                    <h2 class="flex items-center text-lg font-semibold">
                        <i class="fas fa-user mr-2"></i>
                        Información del Postulante
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-user-circle text-blue-500 mr-2"></i>
                                Nombres y Apellidos
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">{{ $postulante->nombres }}
                                    {{ $postulante->apellidos }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-briefcase text-green-500 mr-2"></i>
                                Puesto al que postula
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->puesto_postula ?? 'N.A' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                Lugar de Nacimiento
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->distrito_nombre ?? $postulante->distrito }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fa-solid fa-address-card text-blue-500 mr-2"></i>
                                DNI
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->dni ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fas fa-calendar text-purple-500 mr-2"></i>
                                Edad
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->edad ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fa-solid fa-phone text-purple-500 mr-2"></i>
                                Telefono
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->celular ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fa-solid fa-calendar text-purple-500 mr-2"></i>
                                Fecha que postula
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($postulante->fecha_postula)->format('d/m/Y') }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fa-solid fa-phone text-purple-500 mr-2"></i>
                                Experiencia en el cargo
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->experiencia_rubro ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 flex items-center">
                                <i class="fa-solid fa-phone text-purple-500 mr-2"></i>
                                Nivel de educacion
                            </label>
                            <div class="p-3 bg-gray-50 rounded-xl border">
                                <p class="text-sm font-semibold text-gray-900">
                                    {{ $postulante->grado_instruccion ?? 'No especificado' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
        $entrevista->comentario
        ?? $entrevista->comentario_final
        ?? ''
        );
        $fechaEntrevista = $entrevista?->fecha_entrevista
            ? \Carbon\Carbon::parse($entrevista->fecha_entrevista)
            : null;
        $whatsappFecha = $fechaEntrevista ? $fechaEntrevista->format('d/m/Y') : null;
        $whatsappHoraInicio = $fechaEntrevista ? $fechaEntrevista->format('H:i') : null;
        $whatsappHoraFin = $fechaEntrevista ? $fechaEntrevista->copy()->addMinutes(20)->format('H:i') : null;
        @endphp

        {{-- Evaluación de Aptitud --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4">
                    <h2 class="flex items-center text-lg font-semibold">
                        <i class="fas fa-clipboard-check mr-2"></i>
                        Evaluación de Aptitud para el Puesto
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-4">
                                <label class="text-sm font-medium text-gray-700">¿Es apto para el puesto
                                    solicitado?</label>
                                <div class="space-y-3">
                                    {{-- SÍ --}}
                                    <label
                                        class="flex items-center p-4 bg-green-50 rounded-xl border border-green-200 cursor-pointer hover:bg-green-100 transition">
                                        <input type="radio" name="apto_puesto" value="si"
                                            class="text-green-600 focus:ring-green-500"
                                            {{ $aptoPuesto === 'si' ? 'checked' : '' }}>
                                        <div class="ml-3 flex items-center">
                                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                                            <span class="text-sm font-medium text-gray-900">Sí, es apto para el
                                                puesto</span>
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
                                            <span class="text-sm font-medium text-gray-900">No es apto para el
                                                puesto</span>
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
                                            <span class="text-sm font-medium text-gray-900">Apto para otro
                                                puesto</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <div id="otro-puesto-section"
                                class="{{ $aptoPuesto === 'otro_puesto' ? '' : 'hidden' }} space-y-2">
                                <label class="text-sm font-medium text-gray-700">Especifique el otro puesto:</label>
                                <input type="text" name="otro_puesto_especifico"
                                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 text-gray-900 font-semibold transition-colors"
                                    placeholder="Nombre del puesto alternativo"
                                    value="{{ old('otro_puesto_especifico', $entrevista->otro_puesto ?? '') }}">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-sm font-medium text-gray-700">Comentarios de la Evaluación</label>
                            <textarea name="comentarios_evaluacion" rows="8"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-0 bg-white/80 text-gray-900 font-semibold transition-colors resize-none"
                                placeholder="Escriba sus observaciones, fortalezas, debilidades y recomendaciones sobre el postulante...">{{ $comentariosEval }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Mensajeria WhatsApp --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-4">
                    <h2 class="flex items-center text-lg font-semibold">
                        <i class="fab fa-whatsapp mr-2"></i>
                        Enviar mensaje por WhatsApp
                    </h2>
                </div>
                <div class="p-6 flex flex-col lg:flex-row lg:items-center gap-4">
                    <div class="flex-1 space-y-1">
                        <p class="text-sm text-gray-700">Número registrado:
                            <span class="font-semibold">{{ $postulante->celular ?? 'Sin número' }}</span>
                        </p>
                        <p class="text-xs text-gray-500">Se usará el texto predeterminado según el resultado de aptitud.</p>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <button type="button"
                            class="px-4 py-2 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl shadow-md flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                            onclick="abrirWhatsappDesdeSeleccion('apto')" @if(empty($postulante->celular)) disabled @endif>
                            <i class="fab fa-whatsapp"></i>
                            <span>Mensaje apto</span>
                        </button>
                        <button type="button"
                            class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow-md flex items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed"
                            onclick="abrirWhatsappDesdeSeleccion('no_apto')" @if(empty($postulante->celular)) disabled @endif>
                            <i class="fab fa-whatsapp"></i>
                            <span>Mensaje no apto</span>
                        </button>
                    </div>
                </div>
                @if(empty($postulante->celular))
                <div class="px-6 pb-6">
                    <p class="text-sm text-red-600">Registre un celular para habilitar el envío por WhatsApp.</p>
                </div>
                @endif
            </div>
        </div>


        {{-- Botones de Acción --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex flex-col sm:flex-row gap-4 justify-end">
                    <button type="button" id="btn-guardar-borrador" onclick="guardarBorrador()"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors flex items-center justify-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Guardar Borrador</span>
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

        fetch('{{ route('entrevistas.guardar-evaluacion',['postulante'=>$postulante->id])}}', {
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
            Swal.fire({
                icon: 'warning',
                title: 'Campo requerido',
                text: 'Por favor, seleccione si el postulante es apto para el puesto.',
                confirmButtonColor: '#3b82f6',
            });
            return;
        }

        if (aptoPuesto.value === 'otro_puesto') {
            const otroPuesto = document.querySelector('input[name="otro_puesto_especifico"]').value;
            if (!otroPuesto.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor, especifique el otro puesto para el cual es apto el postulante.',
                    confirmButtonColor: '#3b82f6',
                });
                return;
            }
        }

        // Mostrar SweetAlert cuando se finaliza la evaluación
        e.preventDefault();
        Swal.fire({
            icon: 'success',
            title: '¡Evaluación Completada!',
            html: '<div class="text-left"><p class="mb-2"><strong>Postulante:</strong> {{ $postulante->nombres }} {{ $postulante->apellidos }}</p><p class="mb-2"><strong>Puesto:</strong> {{ $postulante->puesto_postula ?? "N/A" }}</p><p><strong>Estado:</strong> Evaluado</p></div>',
            confirmButtonColor: '#10b981',
            confirmButtonText: 'Confirmar y Guardar',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('evaluacion-form').submit();
            }
        });
    });

    // ------ Cerrar modal al hacer clic fuera ------
    document.getElementById('preview-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePreviewModal();
        }
    });

    const whatsappConfig = {
        ruta: @json(route('entrevistas.whatsapp', ['postulante' => $postulante->id])),
        numero: @json($postulante->celular),
        nombre: @json(trim($postulante->nombres . ' ' . $postulante->apellidos)),
        puesto: @json($postulante->puesto_postula ?? 'el puesto indicado'),
        fecha: @json($whatsappFecha),
        horaInicio: @json($whatsappHoraInicio),
        horaFin: @json($whatsappHoraFin),
    };

    function obtenerTipoDesdeSeleccion() {
        const seleccionado = document.querySelector('input[name="apto_puesto"]:checked');
        if (!seleccionado) return null;
        if (seleccionado.value === 'no') return 'no_apto';
        return 'apto';
    }

    function abrirWhatsappDesdeSeleccion(forzado = null) {
        const tipo = forzado || obtenerTipoDesdeSeleccion();

        if (!tipo) {
            Swal.fire({
                icon: 'info',
                title: 'Selecciona el resultado',
                text: 'Define si el postulante es apto o no apto para elegir el mensaje.',
                confirmButtonColor: '#3b82f6',
            });
            return;
        }

        if (!whatsappConfig.numero) {
            Swal.fire({
                icon: 'warning',
                title: 'Sin celular',
                text: 'No hay un nÇ§mero de contacto registrado para este postulante.',
                confirmButtonColor: '#f59e0b',
            });
            return;
        }

        mostrarModalWhatsapp(tipo);
    }

    function construirMensajePreview(tipo, data) {
        const nombre = data.nombre || '';
        const puesto = data.puesto || 'el puesto indicado';

        if (tipo === 'apto') {
            const fechaTxt = data.fecha ? ` para el dÇða ${data.fecha}` : '';
            const horaTxt = data.horaInicio && data.horaFin ? ` entre las ${data.horaInicio} y las ${data.horaFin}` : '';
            let texto = `Hola ${nombre}, gracias por postular al puesto de ${puesto}. Luego de realizar la revisiÇün documentaria correspondiente, solicitamos de tu tiempo para realizar una entrevista virtual mediante Google Meets cuya duraciÇün serÇ­ de 20 minutos${fechaTxt}${horaTxt}. IndÇ­canos si estÇ­s disponible en ese horario o si deseas reprogramar tu entrevista.`;

            if (data.enlace) {
                texto += ` Enlace de la reuniÇün: ${data.enlace}.`;
            }
            return texto;
        }

        return `Hola ${nombre}, gracias por postular al puesto de ${puesto}. Lamentamos informarte que en esta oportunidad los puestos han sido cubiertos.`;
    }

    function mostrarModalWhatsapp(tipo) {
        const esApto = tipo === 'apto';
        const titulo = esApto ? 'Mensaje para candidato apto' : 'Mensaje para no apto';

        const horarioInputs = esApto ? `
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Hora inicio (A)</label>
                    <input type="time" id="wa-hora-inicio" value="${whatsappConfig.horaInicio || ''}"
                        class="w-full px-3 py-2 border rounded-lg focus:border-emerald-500 focus:ring-0 text-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Hora fin (B)</label>
                    <input type="time" id="wa-hora-fin" value="${whatsappConfig.horaFin || ''}"
                        class="w-full px-3 py-2 border rounded-lg focus:border-emerald-500 focus:ring-0 text-sm">
                </div>
            </div>
            <div class="mb-3">
                <label class="block text-xs text-gray-600 mb-1">Enlace de Google Meet (opcional)</label>
                <input type="text" id="wa-enlace" placeholder="https://meet.google.com/..." class="w-full px-3 py-2 border rounded-lg focus:border-emerald-500 focus:ring-0 text-sm">
            </div>
        ` : '';

        const previewInicial = construirMensajePreview(tipo, {
            nombre: whatsappConfig.nombre,
            puesto: whatsappConfig.puesto,
            fecha: whatsappConfig.fecha,
            horaInicio: whatsappConfig.horaInicio,
            horaFin: whatsappConfig.horaFin,
        });

        Swal.fire({
            title: titulo,
            html: `
                ${whatsappConfig.fecha ? `<p class="text-xs text-gray-600 mb-2">Fecha de entrevista: ${whatsappConfig.fecha}</p>` : ''}
                ${horarioInputs}
                <label class="block text-xs text-gray-600 mb-1 text-left">Vista previa</label>
                <textarea id="wa-preview" class="w-full border rounded-lg p-3 text-sm" rows="4" readonly>${previewInicial}</textarea>
            `,
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Enviar por WhatsApp',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                const payload = { tipo };

                if (esApto) {
                    payload.hora_inicio = document.getElementById('wa-hora-inicio')?.value || '';
                    payload.hora_fin = document.getElementById('wa-hora-fin')?.value || '';
                    payload.enlace_meet = document.getElementById('wa-enlace')?.value || '';
                }

                return payload;
            },
            didOpen: () => {
                if (esApto) {
                    const inicioInput = document.getElementById('wa-hora-inicio');
                    const finInput = document.getElementById('wa-hora-fin');
                    const enlaceInput = document.getElementById('wa-enlace');
                    const previewEl = document.getElementById('wa-preview');

                    [inicioInput, finInput, enlaceInput].forEach(el => {
                        el?.addEventListener('input', () => {
                            const texto = construirMensajePreview(tipo, {
                                nombre: whatsappConfig.nombre,
                                puesto: whatsappConfig.puesto,
                                fecha: whatsappConfig.fecha,
                                horaInicio: inicioInput?.value || whatsappConfig.horaInicio,
                                horaFin: finInput?.value || whatsappConfig.horaFin,
                                enlace: enlaceInput?.value || ''
                            });

                            if (previewEl) previewEl.value = texto;
                        });
                    });
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                enviarWhatsapp(result.value);
            }
        });
    }

    async function enviarWhatsapp(payload) {
        if (!payload || !payload.tipo) return;

        Swal.fire({
            title: 'Enviando...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading(),
        });

        try {
            const res = await fetch(whatsappConfig.ruta, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify(payload)
            });

            const data = await res.json();

            if (!res.ok || !data.success) {
                throw new Error(data.message || 'No se pudo enviar el mensaje.');
            }

            Swal.fire({
                icon: 'success',
                title: 'Mensaje enviado',
                text: data.message || 'El mensaje fue enviado por WhatsApp.',
                confirmButtonColor: '#10b981',
            });
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'No se pudo enviar el mensaje de WhatsApp.',
                confirmButtonColor: '#ef4444',
            });
        }
    }

    async function guardarBorrador() {
        const form = document.getElementById('evaluacion-form');
        if (!form) return;

        // Mostrar loading
        Swal.fire({
            icon: 'info',
            title: 'Guardando...',
            text: 'Por favor espera mientras guardamos tu borrador.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const url = form.action + '?borrador=1';
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
                    title: '¡Error!',
                    text: msg,
                    confirmButtonColor: '#ef4444',
                });
                return;
            }

            const json = await res.json();

            if (json.success) {
                Swal.fire({
                    icon: 'success',
                    title: '¡Borrador Guardado!',
                    html: '<div class="text-left"><p class="mb-2"><strong>Postulante:</strong> {{ $postulante->nombres }} {{ $postulante->apellidos }}</p><p class="mb-2"><strong>Puesto:</strong> {{ $postulante->puesto_postula ?? "N/A" }}</p><p><strong>Estado:</strong> Borrador</p><p class="text-yellow-600 text-sm mt-3">Recuerda finalizar la evaluación cuando esté completa.</p></div>',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Entendido',
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'No se pudo guardar el borrador, revisa los datos.',
                    confirmButtonColor: '#f59e0b',
                });
            }

        } catch (err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error inesperado',
                text: 'Ocurrió un problema al guardar el borrador.',
                confirmButtonColor: '#ef4444',
            });
        }
    }
</script>

{{-- Estilos adicionales --}}
<style>
    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    input[type="radio"]:checked {
        background-color: currentColor;
        border-color: currentColor;
    }

    .card-hover {
        transform: translateY(0);
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-2px);
    }
</style>
</div>
@endsection
