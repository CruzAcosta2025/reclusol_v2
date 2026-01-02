@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')
@section('module', 'afiches')

@section('content')
    <div class ="space-y-6">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="card glass-strong p-6 shadow-soft">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="min-w-0">
                        <h2 class="text-xl sm:text-2xl font-extrabold text-white tracking-wide">
                            Generación de Afiches
                        </h2>

                        <p class="text-sm text-white/70 mt-1">
                            Afiches para la publicación de requerimientos laborales.
                        </p>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('dashboard') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-gauge-high mr-2"></i>Dashboard
                        </a>
                        
                        <a href="{{ route('requerimientos.filtrar') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-list mr-2"></i>Ver Requerimientos
                        </a>
                        
                        <a href="{{ route('afiches.assets.upload') }}"
                            class="px-4 py-2 rounded-xl font-semibold text-sm bg-white/10 hover:bg-white/15 transition">
                            <i class="fas fa-plus mr-2"></i>Añadir recursos
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Panel de Control -->
                <div class="lg:col-span-1 space-y-6">

                    {{-- PANEL DE REQUERIMIENTOS --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-briefcase text-blue-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Requerimientos Activos</h3>
                        </div>
                        <div class="space-y-3 max-h-80 overflow-y-auto">
                            @foreach ($requerimientos as $req)
                                @php
                                    $color = match (strtolower($req->urgencia)) {
                                        'alta' => 'bg-red-600 text-white',
                                        'media' => 'bg-yellow-600 text-white',
                                        'baja' => 'bg-green-600 text-white',
                                        default => 'bg-gray-600 text-white',
                                    };

                                @endphp
                                <div class="requirement-item p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-300"
                                    onclick="selectRequirement({{ $req->id }}, this)">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="font-semibold text-xs text-gray-800">{{ $req->cargo_nombre }}</h4>
                                        <span
                                            class="{{ $color }} text-sm font-semibold tracking-widest px-3 py-1 rounded-full uppercase shadow-sm border border-opacity-40 border-current">
                                            {{ strtoupper($req->urgencia) }}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-600 mb-2">
                                        Sucursal: {{ $req->sucursal_nombre }}
                                    </p>
                                    <div class="flex justify-between text-xs text-gray-600">
                                        <span>
                                            Cantidad Requerida: {{ $req->cantidad_requerida }}
                                            {{ str('persona')->plural($req->cantidad_requerida) }}
                                        </span>
                                        <span>
                                            Límite: {{ \Carbon\Carbon::parse($req->fecha_limite)->format('Y/m/d') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- JS DATA --}}
                    @php
                        $jsRequerimientos = $requerimientos
                            ->mapWithKeys(function ($req) {
                                $badgeColor = match (strtolower($req->urgencia)) {
                                    'alta' => 'bg-red-700 text-red-700',
                                    'media' => 'bg-yellow-100 text-yellow-800',
                                    'baja' => 'bg-green-100 text-green-800',
                                    default => 'bg-gray-100 text-gray-800',
                                };
                                return [
                                    $req->id => [
                                        'title' => strtoupper($req->cargo_nombre), // 👈 Cambiado
                                        'location' => trim(
                                            $req->sucursal_nombre . ' - ' . ($req->cliente_nombre ?? ''),
                                            ' -',
                                        ),
                                        'quantity' => $req->cantidad_requerida,
                                        'deadline' => optional($req->fecha_limite)->format('Y/m/d'),
                                        'requirements' => array_values(
                                            array_filter([
                                                'Edad: ' . $req->edad_minima . ' - ' . $req->edad_maxima . ' años',

                                                $req->nivel_estudios
                                                    ? 'Estudios: ' . str_replace('_', ' ', $req->nivel_estudios)
                                                    : null,
                                                $req->experiencia_minima
                                                    ? 'Experiencia: ' . str_replace('_', ' ', $req->experiencia_minima)
                                                    : null,
                                                $req->requiere_sucamec ? 'SUCAMEC vigente' : null,
                                                $req->requiere_licencia_conducir ? 'Licencia de conducir' : null,
                                                $req->requisitos_adicionales,
                                            ]),
                                        ),
                                        'badgeColor' => $badgeColor,
                                    ],
                                ];
                            })
                            ->toJson(JSON_UNESCAPED_UNICODE);
                    @endphp

                    {{-- CONTENEDOR DE OPCIONES (TABS) --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="mb-4 flex border-b border-gray-200 overflow-x-auto no-scrollbar">
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none focus:border-purple-600"
                                onclick="showTab('tab-plantillas', this)">Plantillas</button>
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconG', this)">Ícono Principal</button>
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconCheck', this)">Ícono Check</button>
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconPhone', this)">Ícono Celular</button>
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconEmail', this)">Ícono Email</button>
                            <button
                                class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-font', this)">Fuente</button>
                        </div>

                        <div id="tab-plantillas" class="option-tab-content hidden">
                            <div class="grid grid-cols-2 gap-3">
                                @forelse($plantillas ?? [] as $tpl)
                                    @php
                                        // Clave que se enviará al controlador: nombre del archivo sin extensión
                                        $tplKey = Str::beforeLast($tpl->filename, '.'); // ej: modern, classic, etc.
                                    @endphp

                                    <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center"
                                        onclick="selectTemplate('{{ $tplKey }}', this)">
                                        <div
                                            class="w-full h-20 rounded-lg mb-2 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset($tpl->path) }}" alt="{{ $tpl->name }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">{{ $tpl->name }}</p>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 col-span-full">
                                        No hay plantillas cargadas.
                                        <a href="{{ route('afiches.assets.upload') }}" class="text-blue-500 underline">
                                            Ir a “Cargar recursos”
                                        </a>
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div id="tab-iconG" class="option-tab-content hidden">
                            <div class="grid grid-cols-3 gap-3">
                                @forelse($iconosG ?? [] as $icon)
                                    <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                        onclick="selectIconG('{{ $icon->path }}', this)">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-16 object-contain mx-auto">
                                        <span class="block text-sm mt-2 truncate">{{ $icon->name }}</span>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 col-span-full">
                                        No hay íconos principales cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div id="tab-iconCheck" class="option-tab-content hidden">
                            <div class="grid grid-cols-3 gap-3">
                                @forelse($iconosCheck ?? [] as $icon)
                                    <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                        onclick="selectIconCheck('{{ $icon->path }}', this)">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mx-auto">
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 col-span-full">
                                        No hay íconos de check cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div id="tab-iconPhone" class="option-tab-content hidden">
                            <div class="grid grid-cols-3 gap-3">
                                @forelse($iconosPhone ?? [] as $icon)
                                    <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                        onclick="selectIconPhone('{{ $icon->path }}', this)">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mx-auto">
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 col-span-full">
                                        No hay íconos de teléfono cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div id="tab-iconEmail" class="option-tab-content hidden">
                            <div class="grid grid-cols-3 gap-3">
                                @forelse($iconosEmail ?? [] as $icon)
                                    <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                        onclick="selectIconEmail('{{ $icon->path }}', this)">
                                        <img src="{{ asset($icon->path) }}" alt="{{ $icon->name }}"
                                            class="w-full h-12 object-contain mx-auto">
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 col-span-full">
                                        No hay íconos de email cargados.
                                    </p>
                                @endforelse
                            </div>
                        </div>

                        <div id="tab-font" class="option-tab-content hidden">
                            <select id="font-select"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none">
                                <option value="">(Fuente por defecto)</option>

                                @foreach ($fonts ?? [] as $font)
                                    <option value="{{ $font->path }}">{{ $font->filename }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Vista Previa del Afiche -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <!-- Encabezado derecha -->
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h2 class="text-xl font-bold text-gray-800">Vista previa del afiche</h2>
                                <p class="text-sm text-gray-500">
                                    Selecciona un requerimiento y una plantilla para generar el afiche.
                                </p>
                            </div>

                            <div class="flex items-center space-x-3">
                                <button id="btn-ver-afiche"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2"
                                    onclick="verAfiche()">
                                    <i class="fas fa-eye"></i>
                                    <span>Ver afiche</span>
                                </button>

                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDownloadMenu()"
                                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                                        <i class="fas fa-download"></i>
                                        <span>Descargar</span>
                                        <i class="fas fa-caret-down ml-1"></i>
                                    </button>

                                    <div id="download-menu"
                                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 hidden">
                                        <button onclick="downloadPoster('jpg')"
                                            class="w-full text-left text-gray-700 px-4 py-2 hover:bg-green-100 flex items-center">
                                            <i class="fas fa-file-image mr-2"></i> JPG
                                        </button>
                                        <button onclick="downloadPoster('pdf')"
                                            class="w-full text-left text-gray-700 px-4 py-2 hover:bg-red-100 flex items-center">
                                            <i class="fas fa-file-pdf mr-2"></i> PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vista previa -->
                        <div class="flex justify-center items-center p-6 bg-gray-50 rounded-lg">
                            <img id="poster-image" src="" alt="Vista previa del afiche"
                                class="hidden rounded-lg shadow-xl border border-gray-200 w-full max-w-[1080px] object-contain">
                        </div>

                        <!-- Mensaje predeterminado -->
                        <div class="mt-6">
                            <label class="font-bold mb-2 block">
                                Mensaje predeterminado para publicar:
                            </label>
                            <textarea id="mensaje-predeterminado"
                                class="w-full p-3 rounded-lg border border-gray-300 text-sm text-gray-700 bg-gray-50 resize-none" rows="8"
                                readonly></textarea>
                            <button
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg mt-2 flex items-center justify-center space-x-2"
                                onclick="copiarMensaje()">
                                <i class="fas fa-copy"></i>
                                <span>Copiar Información</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }

        .requirement-item.selected {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        .template-option.selected {
            border-color: #8b5cf6;
            background-color: #f3e8ff;
        }

        .icon-option.selected {
            border-color: #8b5cf6;
            background-color: #f3e8ff;
        }

        .iconG-option.selected,
        .iconCheck-option.selected,
        .iconPhone-option.selected,
        .iconEmail-option.selected {
            border-color: #8b5cf6 !important;
            background-color: #f3e8ff !important;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const requerimientosData = {!! $jsRequerimientos !!};
        const posterImage = document.getElementById('poster-image');
        const fontSelect = document.getElementById('font-select');

        let selectedRequirement = null;
        let selectedTemplate = null; // antes 'modern'
        let selectedFont = fontSelect ? fontSelect.value : null;
        let selectedIconG = null;
        let selectedIconCheck = null;
        let selectedIconPhone = null;
        let selectedIconEmail = null;


        // Mostrar la primera tab por defecto
        document.addEventListener('DOMContentLoaded', () => {
            // No abrimos ninguna pestaña por defecto; el usuario decide cuál ver.
            const firstReq = document.querySelector('.requirement-item');
            if (firstReq) firstReq.click();

            const firstTpl = document.querySelector('.template-option');
            if (firstTpl) firstTpl.click(); // deja una plantilla preseleccionada (aunque la pestaña esté oculta)

            if (fontSelect) {
                fontSelect.addEventListener('change', () => selectFont(fontSelect.value));
            }
        });



        function showTab(tabId, btn) {
            document.querySelectorAll('.option-tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            document.querySelectorAll('.option-tab').forEach(b => b.classList.remove('border-purple-600',
                'text-purple-600'));
            btn.classList.add('border-purple-600', 'text-purple-600');
        }


        function verAfiche() {
            if (!selectedRequirement) {
                alert('Selecciona un requerimiento primero');
                return;
            }

            // Si el afiche YA está visible, lo ocultamos
            if (!posterImage.classList.contains('hidden')) {
                posterImage.classList.add('hidden');
                return; // salimos, no volvemos a generar la imagen
            }

            // Si está oculto, lo mostramos (generando/actualizando la vista previa)
            updatePreview(selectedRequirement);
        }


        function selectRequirement(id, el) {
            document.querySelectorAll('.requirement-item').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            selectedRequirement = id;
            posterImage.classList.add('hidden');
            armarMensajePredeterminado()
            //updatePreview(id);
        }

        async function copiarMensaje() {
            const textarea = document.getElementById('mensaje-predeterminado');
            const texto = textarea.value.trim();

            if (!texto) {
                Swal.fire({
                    icon: 'info',
                    title: 'Nada para copiar',
                    text: 'Primero genera el mensaje del requerimiento.',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            try {
                // Intento moderno con Clipboard API
                if (navigator.clipboard && window.isSecureContext) {
                    await navigator.clipboard.writeText(texto);
                } else {
                    // Fallback para navegadores antiguos
                    textarea.select();
                    textarea.setSelectionRange(0, 99999);
                    document.execCommand('copy');
                    textarea.blur();
                }

                // SweetAlert tipo toast, 3 segundos
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Mensaje copiado al portapapeles',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

            } catch (e) {
                console.error(e);
                Swal.fire({
                    icon: 'error',
                    title: 'Error al copiar',
                    text: 'No se pudo copiar el mensaje. Inténtalo de nuevo.',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        }


        function armarMensajePredeterminado() {
            if (!selectedRequirement) {
                document.getElementById('mensaje-predeterminado').value = '';
                return;
            }
            const req = requerimientosData[selectedRequirement];
            if (!req) return;

            let mensaje = `🔎 ¡Convocatoria de ${req.title} en ${req.location}!

🙌 ¿Buscas una oportunidad laboral? En SOLMAR SECURITY estamos en la búsqueda de personal comprometido y responsable💪

✅ Requisitos:
`;

            if (req.requirements && req.requirements.length > 0) {
                req.requirements.forEach(item => mensaje += `- ${item}\n`);
            }

            mensaje += `
📩 ¡Postula ahora! Ingresa a link, envíanos tu CV a 📧 informes@gruposolmar.com.pe o contáctanos al 📞 946 343 555.

👉 ¡No dejes pasar esta oportunidad de ser parte de nuestro equipo!

#OportunidadLaboral #TrabajaConNosotros #SolmarSECURITY #Empleo #${req.location.replace(/\s/g, '')} #${req.title.replace(/\s/g, '')}
`;

            document.getElementById('mensaje-predeterminado').value = mensaje;
        }
        // También actualízalo cuando cambies de plantilla o lo que sea relevante

        function updatePreview(id) {
            if (!selectedTemplate) {
                Swal.fire({
                    icon: 'info',
                    title: 'Selecciona una plantilla',
                    timer: 2000,
                    showConfirmButton: false
                });
                return;
            }

            const req = requerimientosData[id];
            if (!req) return;

            let url = `/poster/${id}/${selectedTemplate}?preview=1&logo=1`;
            if (selectedIconG) url += `&iconG=${encodeURIComponent(selectedIconG)}`;
            if (selectedIconCheck) url += `&iconCheck=${encodeURIComponent(selectedIconCheck)}`;
            if (selectedIconPhone) url += `&iconPhone=${encodeURIComponent(selectedIconPhone)}`;
            if (selectedIconEmail) url += `&iconEmail=${encodeURIComponent(selectedIconEmail)}`;
            if (selectedFont) url += `&font=${encodeURIComponent(selectedFont)}`;

            posterImage.classList.remove('hidden');
            posterImage.onerror = () => posterImage.src = '/assets/plantillas/placeholder.png';
            posterImage.src = url;
        }



        function selectTemplate(template, el) {
            document.querySelectorAll('.template-option').forEach(o => o.classList.remove('selected'));
            el.classList.add('selected');
            selectedTemplate = template.trim();
            if (selectedRequirement) updatePreview(selectedRequirement);
        }


        function selectIconG(path, el) {
            document.querySelectorAll('.iconG-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            selectedIconG = path;
            if (selectedRequirement) updatePreview(selectedRequirement);
        }

        function selectIconCheck(path, el) {
            document.querySelectorAll('.iconCheck-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            selectedIconCheck = path;
            if (selectedRequirement) updatePreview(selectedRequirement);
        }

        function selectIconPhone(path, el) {
            document.querySelectorAll('.iconPhone-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            selectedIconPhone = path;
            if (selectedRequirement) updatePreview(selectedRequirement);
        }

        function selectIconEmail(path, el) {
            document.querySelectorAll('.iconEmail-option').forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');
            selectedIconEmail = path;
            if (selectedRequirement) updatePreview(selectedRequirement);
        }

        function selectFont(fontUrl) {
            selectedFont = fontUrl;
            if (selectedRequirement) updatePreview(selectedRequirement);
        }


        function downloadPoster(format = 'png') {
            if (!selectedRequirement) return alert('Selecciona un requerimiento primero');
            let url = `/poster/${selectedRequirement}/${selectedTemplate}?logo=1&format=${encodeURIComponent(format)}`;
            if (selectedIconG) url += `&iconG=${encodeURIComponent(selectedIconG)}`;
            if (selectedIconCheck) url += `&iconCheck=${encodeURIComponent(selectedIconCheck)}`;
            if (selectedIconPhone) url += `&iconPhone=${encodeURIComponent(selectedIconPhone)}`;
            if (selectedIconEmail) url += `&iconEmail=${encodeURIComponent(selectedIconEmail)}`;
            if (selectedFont) url += `&font=${encodeURIComponent(selectedFont)}`;
            window.open(url, '_blank');
        }

        function toggleDownloadMenu() {
            document.getElementById('download-menu').classList.toggle('hidden');
        }
    </script>

    {{-- Estilos adicionales --}}
    <style>
        .panel-light {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.96), rgba(249, 250, 251, 0.95));
            border: 1px solid rgba(148, 163, 184, 0.25);
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.25);
            backdrop-filter: blur(10px);
        }

        .form-input::placeholder {
            color: #9ca3af;
            opacity: 1;
        }

        .form-input:focus {
            outline: none;
        }
    </style>

@endsection
