@php
    use Illuminate\Support\Str;
@endphp

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
                        <h1 class="text-3xl font-bold text-gray-800">Generador de Afiches</h1>
                        <p class="text-gray-600 mt-1">Crea afiches automáticamente basados en los requerimientos activos</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button onclick="generateAllPosters()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-magic"></i>
                            <span>Generar Todos</span>
                        </button>
                        <button onclick="showHistory()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-history"></i>
                            <span>Historial</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Panel de Control -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Selección de Requerimiento -->
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @foreach ($requerimientos as $req)
                        @php
                        $color = match (strtolower($req->prioridad)) {
                        'urgente' => 'bg-red-100 text-red-800',
                        'alta' => 'bg-red-50 text-red-600',
                        'media' => 'bg-yellow-100 text-yellow-800',
                        'baja' => 'bg-green-100 text-green-800',
                        default => 'bg-gray-100 text-gray-800',
                        };
                        @endphp

                        <div class="requirement-item p-4 border border-gray-200 rounded-lg cursor-pointer
                            hover:border-blue-400 hover:bg-blue-50 transition-all duration-300"
                            onclick="selectRequirement({{ $req->id }}, this)">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold text-gray-800">{{ Str::upper($req->cargo_solicitado) }}</h4>
                                <span class="{{ $color }} text-xs px-2 py-1 rounded-full capitalize">
                                    {{ $req->prioridad }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mb-2">
                                Cliente: {{ $req->cliente }} – {{ Str::title($req->sucursal) }}
                            </p>
                            <div class="flex justify-between text-xs text-gray-500">
                                <span>Cantidad: {{ $req->cantidad_requerida }} {{ Str::plural('persona', $req->cantidad_requerida) }}</span>
                                <span>Límite: {{ \Carbon\Carbon::parse($req->fecha_limite)->format('Y/m/d') }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @php
                    $jsRequerimientos = $requerimientos
                    ->mapWithKeys(function ($r) {
                    $badgeColor = match (strtolower($r->prioridad)) {
                    'urgente' => 'bg-red-100 text-red-800',
                    'alta' => 'bg-red-50 text-red-600',
                    'media' => 'bg-yellow-100 text-yellow-800',
                    'baja' => 'bg-green-100 text-green-800',
                    default => 'bg-gray-100 text-gray-800',
                    };

                    return [
                    $r->id => [
                    'title' => strtoupper($r->cargo_solicitado),
                    'location' => \Illuminate\Support\Str::title($r->sucursal).' - '.$r->cliente,
                    'quantity' => $r->cantidad_requerida,
                    'deadline' => optional($r->fecha_limite)->format('Y/m/d'),
                    'requirements'=> array_values(array_filter([
                    'Edad: '.$r->edad_minima.' - '.$r->edad_maxima.' años',
                    $r->nivel_estudios ? 'Estudios: '.str_replace('_',' ',$r->nivel_estudios) : null,
                    $r->experiencia_minima ? 'Experiencia: '.str_replace('_',' ',$r->experiencia_minima) : null,
                    $r->requiere_sucamec ? 'SUCAMEC vigente' : null,
                    $r->requiere_licencia_conducir ? 'Licencia de conducir' : null,
                    $r->requisitos_adicionales,
                    ])),
                    'badgeColor' => $badgeColor,
                    ],
                    ];
                    })
                    ->toJson(JSON_UNESCAPED_UNICODE);
                    @endphp

                    <!-- Plantillas de Afiche -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-palette text-purple-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Plantillas</h3>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('modern',this)">
                                <div class="w-full h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-2 flex items-center justify-center">
                                    <img src="{{ asset('assets/plantillas/modern.png') }}" alt="Plantilla Moderno" class="w-full h-full object-cover">

                                    <!-- <i class="fas fa-briefcase text-white text-xl"></i> -->
                                </div>
                                <p class="text-sm font-medium text-gray-700">Moderno</p>
                            </div>

                            <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('classic',this)">
                                <div class="w-full h-20 bg-gradient-to-br from-gray-600 to-gray-800 rounded-lg mb-2 flex items-center justify-center">
                                    <img src="{{ asset('assets/plantillas/classic.png') }}" alt="Plantilla Clasica" class="w-full h-full object-cover">

                                    <!-- <i class="fas fa-building text-white text-xl"></i> -->
                                </div>
                                <p class="text-sm font-medium text-gray-700">Clásico</p>
                            </div>

                            <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('colorful',this)">
                                <div class="w-full h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-2 flex items-center justify-center">
                                    <img src="{{ asset('assets/plantillas/colorful.png') }}" alt="Plantilla Colorido" class="w-full h-full object-cover">

                                    <!-- <i class="fas fa-star text-white text-xl"></i> -->
                                </div>
                                <p class="text-sm font-medium text-gray-700">Colorido</p>
                            </div>

                            <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('minimal',this)">
                                <div class="w-full h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg mb-2 flex items-center justify-center">
                                    <img src="{{ asset('assets/plantillas/minimal.png') }}" alt="Plantilla Minimalista" class="w-full h-full object-cover">

                                    <!-- <i class="fas fa-leaf text-white text-xl"></i> -->
                                </div>
                                <p class="text-sm font-medium text-gray-700">Minimalista</p>
                            </div>
                        </div>
                    </div>

                    <!-- Opciones de Personalización -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                                <i class="fas fa-cog text-orange-600"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-800">Personalización</h3>
                        </div>

                        <div class="space-y-4">
                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-image mr-2 text-orange-500"></i>
                                    Incluir Logo
                                </label>
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="include_logo" checked class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                    <label for="include_logo" class="text-sm text-gray-600">Logo de RECLUSOL</label>
                                </div>
                            </div>

                            <!-- Información de Contacto -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-2 text-orange-500"></i>
                                    Información de Contacto
                                </label>
                                <div class="space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="include_phone" checked class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                        <label for="include_phone" class="text-sm text-gray-600">Teléfono</label>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="include_email" checked class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                        <label for="include_email" class="text-sm text-gray-600">Email</label>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <input type="checkbox" id="include_address" class="w-4 h-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                        <label for="include_address" class="text-sm text-gray-600">Dirección</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Tamaño del Afiche -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-expand-arrows-alt mr-2 text-orange-500"></i>
                                    Tamaño
                                </label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent outline-none">
                                    <option value="a4">A4 (210 x 297 mm)</option>
                                    <option value="a3">A3 (297 x 420 mm)</option>
                                    <option value="letter">Carta (216 x 279 mm)</option>
                                    <option value="custom">Personalizado</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vista Previa del Afiche -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-eye text-green-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Vista Previa</h3>
                            </div>
                            <div class="flex items-center space-x-3">
                                <!-- <button onclick="refreshPreview()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                                    <i class="fas fa-sync-alt"></i>
                                    <span>Actualizar</span>
                                </button> -->
                                <!-- Botón con dropdown -->
                                <div class="relative inline-block text-left">
                                    <button onclick="toggleDownloadMenu()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                                        <i class="fas fa-download"></i>
                                        <span>Descargar</span>
                                        <i class="fas fa-caret-down ml-1"></i>
                                    </button>

                                    <!-- Menú desplegable -->
                                    <div id="download-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 hidden">
                                        <button onclick="downloadPoster('jpg')" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-green-100 hover:text-green-700">
                                            <i class="fas fa-file-image mr-2 text-green-500"></i> Descargar JPG
                                        </button>
                                        <button onclick="downloadPoster('pdf')" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-red-100 hover:text-red-700">
                                            <i class="fas fa-file-pdf mr-2 text-red-500"></i> Descargar PDF
                                        </button>
                                    </div>
                                </div>
                                <!-- <button onclick="printPoster()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium flex items-center space-x-2 transition-all duration-300">
                                    <i class="fas fa-print"></i>
                                    <span>Imprimir</span>
                                </button> -->
                                <!-- Solo botón del ojo -->
                                <div class="relative w-fit mx-auto">
                                    <button onclick="verAfiche()"
                                        class="bg-white/90 hover:bg-white text-gray-800 p-3 rounded-full shadow-lg transition duration-200"
                                        title="Ver afiche">
                                        <i class="fas fa-eye text-xl"></i>
                                    </button>
                                </div>

                                <!-- Imagen oculta -->
                                <img id="poster-image" class="hidden" alt="Vista previa del afiche">
                            </div>
                        </div>

                        <!-- Afiche Preview -->
                        <div id="poster-preview" class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg p-8 text-white min-h-[600px] flex flex-col justify-between shadow-2xl">

                            <!-- Header del Afiche -->
                            <div class="text-center mb-8">
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4">
                                     <img src="{{ asset('assets/logo_solmar.png') }}" alt="Plantilla Minimalista" class="w-full h-full object-cover">
                                </div>
                                <h1 class="text-4xl font-bold mb-2">OPORTUNIDAD LABORAL</h1>    
                            </div>

                            <!-- Contenido Principal -->
                            <div class="flex-1 text-center">
                                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 mb-6">
                                     <h2 class="text-4xl font-bold mb-2">EMPRESA DE SEGURIDAD</h1>
                                     <h2 class="text-4xl opacity-90">SOLMAR SECURITY</h1>
                                     <p class="text-lg  mb-4" id="job-location">—</p>

                                    <div class="bg-white/20 rounded-lg p-6 mb-6">
                                        <!-- Estos valores serán puestos por JS -->
                                        <h3 class="text-2xl font-bold mb-2">SE REQUIERE <span id="job-title">—</span></h3>
                                        
                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div class="bg-white/20 rounded-lg p-3">
                                                <i class="fas fa-users mb-2 text-xl"></i>
                                                <p><strong id="job-quantity">—</strong> Vacantes</p>
                                            </div>
                                            <div class="bg-white/20 rounded-lg p-3">
                                                <i class="fas fa-calendar mb-2 text-xl"></i>
                                                <p>Hasta el <strong id="job-deadline">{{ $deadline ?? '—' }}</strong></p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Requisitos dinámicos -->
                                    <div class="text-left bg-white/20 rounded-lg p-4 mb-4">
                                        <h4 class="font-bold mb-3 text-center">REQUISITOS:</h4>
                                        <ul class="space-y-2 text-sm" id="job-requirements"></ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer del Afiche -->
                            <div class="text-center">
                                <div class="bg-white/20 rounded-lg p-4">
                                    <h4 class="font-bold mb-3">CONTÁCTANOS:</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-phone mr-2"></i>
                                            <span>+51 946 343 555</span>
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-envelope mr-2"></i>
                                            <span>informes@gruposolmar.com.pe</span>
                                        </div>
                                        <div class="flex items-center justify-center">
                                            <i class="fas fa-globe mr-2"></i>
                                            <span>www.reclusol.pe</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Historial -->
        <div id="history-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-2xl p-8 max-w-4xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-gray-800">Historial de Afiches</h3>
                    <button onclick="closeHistory()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Afiche en historial -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg h-40 mb-3 flex items-center justify-center text-white">
                            <i class="fas fa-briefcase text-3xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-1">Agente de Seguridad</h4>
                        <p class="text-sm text-gray-600 mb-2">Generado: 20/12/2024</p>
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                <i class="fas fa-download mr-1"></i> Descargar
                            </button>
                            <button class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </button>
                        </div>
                    </div>

                    <!-- Más afiches... -->
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-lg h-40 mb-3 flex items-center justify-center text-white">
                            <i class="fas fa-user-tie text-3xl"></i>
                        </div>
                        <h4 class="font-semibold text-gray-800 mb-1">Supervisor</h4>
                        <p class="text-sm text-gray-600 mb-2">Generado: 18/12/2024</p>
                        <div class="flex space-x-2">
                            <button class="flex-1 bg-blue-600 text-white px-3 py-1 rounded text-sm hover:bg-blue-700">
                                <i class="fas fa-download mr-1"></i> Descargar
                            </button>
                            <button class="flex-1 bg-green-600 text-white px-3 py-1 rounded text-sm hover:bg-green-700">
                                <i class="fas fa-eye mr-1"></i> Ver
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Generando afiche...</span>
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
    </style>

    <script>
        /* ---------------- datos Laravel → JS ---------------- */
        const requerimientosData = {!! $jsRequerimientos !!};

        /* ---- cacheamos los nodos que vamos a tocar todo el tiempo ---- */
        const jobTitle = document.getElementById('job-title');
        const jobLocation = document.getElementById('job-location');
        const jobQuantity = document.getElementById('job-quantity');
        const jobDeadline = document.getElementById('job-deadline');
        const jobReqList = document.getElementById('job-requirements');
        const posterPreview = document.getElementById('poster-preview');
        const posterImage = document.getElementById('poster-image');

        let selectedRequirement = null;
        let selectedTemplate = 'modern';

        /* ---------- selección de requerimiento ---------- */
        function selectRequirement(id, el) {
            // resaltado visual
            document.querySelectorAll('.requirement-item')
                .forEach(card => card.classList.remove('selected'));
            el.classList.add('selected');

            selectedRequirement = id;
            updatePreview(id);
        }

        function updatePreview(id) {
            const req = requerimientosData[id];
            if (!req) return;

            // Actualiza texto
            jobTitle.textContent = req.title;
            jobLocation.textContent = req.location;
            jobQuantity.textContent = req.quantity;
            jobDeadline.textContent = req.deadline;

            // Lista de requisitos
            jobReqList.innerHTML = req.requirements
                .map(r => `<li class="flex items-center"><i class="fas fa-check mr-2"></i> ${r}</li>`)
                .join('');

            // Imagen
            const url = `/poster/${id}/${selectedTemplate}?preview=1&logo=1`;
            const img = document.getElementById('poster-image');

            img.onerror = () => {
                img.src = '/assets/plantillas/placeholder.png'; // Asegúrate de tener esta imagen
                console.warn(`❌ Falló la carga del afiche: ${url}`);
            };

            img.onload = () => {
                console.log(`✅ Afiche cargado correctamente: ${url}`);
            };

            img.src = url;
        }



        function selectTemplate(template, el) {
            template = template.trim();
            // Eliminar selección anterior
            document.querySelectorAll('.template-option')
                .forEach(opt => opt.classList.remove('selected'));
            el.classList.add('selected');

            selectedTemplate = template;
            applyTemplate(template); // si aún quieres los colores de fondo

            // 🔁 ACTUALIZA LA IMAGEN DEL PREVIEW
            if (selectedRequirement) {
                const url = `/poster/${selectedRequirement}/${selectedTemplate}?preview=1&logo=1`;
                document.getElementById('poster-image').src = url;
            }
        }

        function applyTemplate(template) {
            posterPreview.className = posterPreview.className
                .replace(/bg-gradient-to-br from-\w+-\d+ to-\w+-\d+/g, '');

            const classes = {
                modern: 'bg-gradient-to-br from-blue-500 to-purple-600',
                classic: 'bg-gradient-to-br from-gray-600 to-gray-800',
                colorful: 'bg-gradient-to-br from-orange-500 to-red-600',
                minimal: 'bg-gradient-to-br from-green-500 to-teal-600',
            };
            posterPreview.classList.add(...classes[template].split(' '));
        }

        /* ---------- descargar ---------- */
        function downloadPoster() {
            if (!selectedRequirement) return alert('Selecciona un requerimiento primero');
            const url = `/poster/${selectedRequirement}/${selectedTemplate}?logo=1`;
            window.open(url, '_blank');
        }

        function toggleDownloadMenu() {
            const menu = document.getElementById('download-menu');
            menu.classList.toggle('hidden');
        }

        // Ocultar menú si haces clic fuera
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('download-menu');
            const button = event.target.closest('button');
            if (!event.target.closest('#download-menu') && !button?.onclick?.toString().includes('toggleDownloadMenu')) {
                menu.classList.add('hidden');
            }
        });

        function verAfiche() {
            const imgSrc = document.getElementById('poster-image').src;
            if (!imgSrc) return alert('No hay afiche para mostrar.');
            window.open(imgSrc, '_blank');
        }


        /* ---------- imprimir ---------- */
        function printPoster() {
            if (!selectedRequirement) return alert('Selecciona un requerimiento primero');
            window.print();
        }

        /* ---------- inicialización ---------- */
        document.addEventListener('DOMContentLoaded', () => {
            const firstCard = document.querySelector('.requirement-item');
            if (firstCard) firstCard.click(); // dispara selectRequirement
        });
    </script>
</x-app-layout>