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
                    <!-- <div class="flex items-center space-x-4">
                        <a href="{{ route('afiches.historial') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold flex items-center space-x-2 transition-all duration-300 hover:-translate-y-1">
                            <i class="fas fa-history"></i>
                            <span>Historial</span>
                        </a>
                    </div> -->
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
                            $color = match (strtolower($req->prioridad)) {
                            'urgente' => 'bg-red-700 text-white',
                            'alta' => 'bg-red-600 text-white',
                            'media' => 'bg-yellow-600 text-white',
                            'baja' => 'bg-green-600 text-white',
                            default => 'bg-gray-600 text-white',
                            };

                            @endphp
                            <div class="requirement-item p-4 border border-gray-200 rounded-lg cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-300"
                                onclick="selectRequirement({{ $req->id }}, this)">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-semibold text-gray-800">{{ $req->cargo_nombre }}</h4>
                                    <span class="{{ $color }} text-sm font-semibold tracking-widest px-3 py-1 rounded-full uppercase shadow-sm border border-opacity-40 border-current">
                                        {{ strtoupper($req->prioridad) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-2">
                                    Sucursal: {{ $req->sucursal_nombre }} - Cliente: {{ $req->cliente }}
                                </p>
                                <p class="text-xs text-gray-600 mb-2">
                                    Area: {{ $req->area_solicitante}}
                                </p>
                                <p class="text-xs text-gray-600 mb-2">
                                    Provincia: {{ $req->provincia_nombre }} - Distrito: {{ $req->distrito_nombre }}
                                </p>

                                <div class="flex justify-between text-xs text-gray-600">
                                    <span>
                                        Cantidad Requerida: {{ $req->cantidad_requerida }} {{ str('persona')->plural($req->cantidad_requerida) }}
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
                    ->mapWithKeys(function ($r) {
                    $badgeColor = match (strtolower($r->prioridad)) {
                    'urgente' => 'bg-red-100 text-red-800',
                    'alta' => 'bg-red-700 text-red-700',
                    'media' => 'bg-yellow-100 text-yellow-800',
                    'baja' => 'bg-green-100 text-green-800',
                    default => 'bg-gray-100 text-gray-800',
                    };
                    return [
                    $r->id => [
                    'title' => strtoupper($r->cargo_nombre), // 👈 Cambiado
                    'location' => $r->sucursal_nombre . ' - ' . $r->cliente, // 👈 Cambiado
                    'quantity' => $r->cantidad_requerida,
                    'deadline' => optional($r->fecha_limite)->format('Y/m/d'),
                    'requirements' => array_values(array_filter([
                    'Edad: ' . $r->edad_minima . ' - ' . $r->edad_maxima . ' años',

                    $r->nivel_estudios ? 'Estudios: ' . str_replace('_', ' ', $r->nivel_estudios) : null,
                    $r->experiencia_minima ? 'Experiencia: ' . str_replace('_', ' ', $r->experiencia_minima) : null,
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

                    {{-- CONTENEDOR DE OPCIONES (TABS) --}}
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="mb-4 flex border-b border-gray-200 overflow-x-auto no-scrollbar">
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none focus:border-purple-600"
                                onclick="showTab('tab-plantillas', this)">Plantillas</button>
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconG', this)">Ícono Principal</button>
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconCheck', this)">Ícono Check</button>
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconPhone', this)">Ícono Celular</button>
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-iconEmail', this)">Ícono Email</button>
                            <button class="option-tab px-4 py-2 font-semibold text-gray-700 border-b-2 border-transparent focus:outline-none"
                                onclick="showTab('tab-font', this)">Fuente</button>
                        </div>
                        <div id="tab-plantillas" class="option-tab-content">
                            <!-- Plantillas de Afiche -->
                            <div class="grid grid-cols-2 gap-3">
                                {{-- ...tu bloque de plantillas (igual que antes)... --}}
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('modern',this)">
                                    <div class="w-full h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/modern.png') }}" alt="Plantilla Moderno" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Moderno</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('classic',this)">
                                    <div class="w-full h-20 bg-gradient-to-br from-gray-600 to-gray-800 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/classic.png') }}" alt="Plantilla Clasica" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Clásico</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('colorful',this)">
                                    <div class="w-full h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/colorful.png') }}" alt="Plantilla Colorido" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Colorido</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center" onclick="selectTemplate('minimal',this)">
                                    <div class="w-full h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/minimal.png') }}" alt="Plantilla Minimalista" class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Minimalista</p>
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconG" class="option-tab-content hidden">
                            <!-- Ícono Principal -->
                            <div class="grid grid-cols-3 gap-3">
                                {{-- ...tu bloque de iconG igual que antes... --}}
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/guardia.png', this)">
                                    <img src="{{ asset('assets/images/guardia.png') }}" alt="Guardia" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Guardia</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/supervisor.png', this)">
                                    <img src="{{ asset('assets/images/supervisor.png') }}" alt="Supervisor" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Supervisor</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/arquitecto.png', this)">
                                    <img src="{{ asset('assets/images/arquitecto.png') }}" alt="Arquitecto" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Arquitecto</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/contabilidad.png', this)">
                                    <img src="{{ asset('assets/images/contabilidad.png') }}" alt="Contabilidad" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Contabilidad</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/programador.png', this)">
                                    <img src="{{ asset('assets/images/programador.png') }}" alt="Programador" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Programador</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/guardia1.png', this)">
                                    <img src="{{ asset('assets/images/guardia1.png') }}" alt="Guardia 1" class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Guardia 1</span>
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconCheck" class="option-tab-content hidden">
                            <!-- Ícono de Check para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check1.png') }}" alt="Check 1" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check2.png') }}" alt="Check 2" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check3.png') }}" alt="Check 3" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check4.png') }}" alt="Check 4" class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconPhone" class="option-tab-content hidden">
                            <!-- Ícono de Phone para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone1.png') }}" alt="Phone 1" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone2.png') }}" alt="Phone 2" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone3.png') }}" alt="Phone 3" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone4.png') }}" alt="Phone 4" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone5.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone5.png') }}" alt="Phone 5" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone6.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone6.png') }}" alt="Phone 6" class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconEmail" class="option-tab-content hidden">
                            <!-- Ícono de Email para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email1.png') }}" alt="Email 1" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email2.png') }}" alt="Email 2" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email3.png') }}" alt="Email 3" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email4.png') }}" alt="Email 4" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email5.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email5.png') }}" alt="Email 5" class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email6.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email6.png') }}" alt="Email 6" class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-font" class="option-tab-content hidden">
                            <!-- Fuente de letra -->
                            <select id="font-select" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none">
                                <option value="fonts/OpenSans-Regular.ttf">Open Sans Regular</option>
                                <option value="fonts/OpenSans-Bold.ttf">Open Sans Bold</option>
                                <option value="fonts/Roboto-Bold.ttf">Roboto Bold</option>
                                <option value="fonts/Roboto-Light.ttf">Roboto Light</option>
                                <option value="fonts/Roboto-Regular.ttf">Roboto Regular</option>
                                <option value="fonts/VERDANA.ttf">Verdana</option>
                                <option value="fonts/VERDANAB.ttf">Verdana Bold</option>
                                <option value="fonts/Vampire Wars Italic.ttf">Vampire Wars Italic</option>
                                <option value="fonts/ARIAL.ttf">Arial</option>
                                <option value="fonts/ARIALBD.ttf">Arial Bold</option>
                                <option value="fonts/Hey Comic.ttf">Comic</option>
                                <option value="fonts/GODOFWAR.ttf">God of War</option>
                            </select>
                        </div>
                    </div>
                </div>
                <!-- Vista Previa del Afiche -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg p-6 card-hover">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-eye text-green-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800">Vista Previa</h3>
                            </div>
                            <div class="relative inline-block text-left">
                                <button onclick="toggleDownloadMenu()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                                    <i class="fas fa-download"></i>
                                    <span>Descargar</span>
                                    <i class="fas fa-caret-down ml-1"></i>
                                </button>
                                <div id="download-menu" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50 hidden">
                                    <button onclick="downloadPoster('jpg')" class="w-full text-left px-4 py-2 hover:bg-green-100 flex items-center">
                                        <i class="fas fa-file-image text-green-500 mr-2"></i> JPG
                                    </button>
                                    <button onclick="downloadPoster('pdf')" class="w-full text-left px-4 py-2 hover:bg-red-100 flex items-center">
                                        <i class="fas fa-file-pdf text-red-500 mr-2"></i> PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-center items-center p-6 bg-gray-50 rounded-lg">
                            <img id="poster-image" src="" alt="Vista previa del afiche" class="hidden rounded-lg shadow-xl border border-gray-200 w-full max-w-[1080px] object-contain">
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

    <script>
        function showTab(tabId, btn) {
            document.querySelectorAll('.option-tab-content').forEach(el => el.classList.add('hidden'));
            document.getElementById(tabId).classList.remove('hidden');
            document.querySelectorAll('.option-tab').forEach(b => b.classList.remove('border-purple-600', 'text-purple-600'));
            btn.classList.add('border-purple-600', 'text-purple-600');
        }
        // Mostrar la primera tab por defecto
        document.addEventListener('DOMContentLoaded', () => {
            showTab('tab-plantillas', document.querySelector('.option-tab'));
            const first = document.querySelector('.requirement-item');
            if (first) first.click();
            const tpl = document.querySelector(".template-option[onclick*=\"'modern'\"]");
            if (tpl) tpl.classList.add('selected');
        });
        const requerimientosData = {!! $jsRequerimientos !!};
        const posterImage = document.getElementById('poster-image');
        const fontSelect = document.getElementById('font-select');

        let selectedRequirement = null;
        let selectedTemplate = 'modern';
        let selectedFont = fontSelect.value;
        let selectedIconG = 'assets/images/guardia.png'; // Valor por defecto
        let selectedIconCheck = 'assets/icons/icon_check1.png'; // Valor por defecto
        let selectedIconPhone = 'assets/icons/icon_phone1.png'; // Valor por defecto
        let selectedIconEmail = 'assets/icons/icon_email1.png'; // Valor por defecto

        function updatePreview(id) {
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
            //posterImage.onload = () => console.log('✅ Preview listo:', url);
            posterImage.src = url;
        }

        function selectRequirement(id, el) {
            document.querySelectorAll('.requirement-item').forEach(c => c.classList.remove('selected'));
            el.classList.add('selected');
            selectedRequirement = id;
            updatePreview(id);
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
        fontSelect.addEventListener('change', () => selectFont(fontSelect.value));

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
</x-app-layout>