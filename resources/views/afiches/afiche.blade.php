@php
    use Illuminate\Support\Str;
@endphp

@extends('layouts.app')

@section('content')
    <div class="min-h-screen gradient-bg py-8 pt-24">
        <!-- Back to Dashboard Button -->
        <a href="{{ route('dashboard') }}"
            class="absolute top-6 left-6 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold rounded-xl shadow-lg transition-colors flex items-center space-x-3 px-6 py-3 text-lg z-10 group">
            <i class="fas fa-arrow-left text-2xl group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-bold">Volver al Dashboard</span>
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
                        <div id="tab-plantillas" class="option-tab-content">
                            <!-- Plantillas de Afiche -->
                            <div class="grid grid-cols-2 gap-3">
                                {{-- ...tu bloque de plantillas (igual que antes)... --}}
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center"
                                    onclick="selectTemplate('modern',this)">
                                    <div
                                        class="w-full h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/modern.png') }}" alt="Plantilla Moderno"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Moderno</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center"
                                    onclick="selectTemplate('classic',this)">
                                    <div
                                        class="w-full h-20 bg-gradient-to-br from-gray-600 to-gray-800 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/classic.png') }}" alt="Plantilla Clasica"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Clásico</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center"
                                    onclick="selectTemplate('colorful',this)">
                                    <div
                                        class="w-full h-20 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/colorful.png') }}" alt="Plantilla Colorido"
                                            class="w-full h-full object-cover">
                                    </div>
                                    <p class="text-sm font-medium text-gray-700">Colorido</p>
                                </div>
                                <div class="template-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 transition-all duration-300 text-center"
                                    onclick="selectTemplate('minimal',this)">
                                    <div
                                        class="w-full h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg mb-2 flex items-center justify-center">
                                        <img src="{{ asset('assets/plantillas/minimal.png') }}" alt="Plantilla Minimalista"
                                            class="w-full h-full object-cover">
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
                                    <img src="{{ asset('assets/images/guardia.png') }}" alt="Guardia"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Guardia</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/supervisor.png', this)">
                                    <img src="{{ asset('assets/images/supervisor.png') }}" alt="Supervisor"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Supervisor</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/arquitecto.png', this)">
                                    <img src="{{ asset('assets/images/arquitecto.png') }}" alt="Arquitecto"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Arquitecto</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/contabilidad.png', this)">
                                    <img src="{{ asset('assets/images/contabilidad.png') }}" alt="Contabilidad"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Contabilidad</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/programador.png', this)">
                                    <img src="{{ asset('assets/images/programador.png') }}" alt="Programador"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Programador</span>
                                </div>
                                <div class="iconG-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconG('assets/images/guardia1.png', this)">
                                    <img src="{{ asset('assets/images/guardia1.png') }}" alt="Guardia 1"
                                        class="w-full h-16 object-contain mx-auto">
                                    <span class="block text-sm mt-2">Guardia 1</span>
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconCheck" class="option-tab-content hidden">
                            <!-- Ícono de Check para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check1.png') }}" alt="Check 1"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check2.png') }}" alt="Check 2"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check3.png') }}" alt="Check 3"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconCheck-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconCheck('assets/icons/icon_check4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_check4.png') }}" alt="Check 4"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconPhone" class="option-tab-content hidden">
                            <!-- Ícono de Phone para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone1.png') }}" alt="Phone 1"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone2.png') }}" alt="Phone 2"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone3.png') }}" alt="Phone 3"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone4.png') }}" alt="Phone 4"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone5.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone5.png') }}" alt="Phone 5"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconPhone-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconPhone('assets/icons/icon_phone6.png', this)">
                                    <img src="{{ asset('assets/icons/icon_phone6.png') }}" alt="Phone 6"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-iconEmail" class="option-tab-content hidden">
                            <!-- Ícono de Email para Requisitos -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email1.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email1.png') }}" alt="Email 1"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email2.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email2.png') }}" alt="Email 2"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email3.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email3.png') }}" alt="Email 3"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email4.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email4.png') }}" alt="Email 4"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email5.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email5.png') }}" alt="Email 5"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                                <div class="iconEmail-option p-3 border border-gray-200 rounded-lg cursor-pointer hover:border-purple-400 hover:bg-purple-50 text-center"
                                    onclick="selectIconEmail('assets/icons/icon_email6.png', this)">
                                    <img src="{{ asset('assets/icons/icon_email6.png') }}" alt="Email 6"
                                        class="w-full h-12 object-contain mx-auto">
                                </div>
                            </div>
                        </div>
                        <div id="tab-font" class="option-tab-content hidden">
                            <!-- Fuente de letra -->
                            <select id="font-select"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 outline-none">
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
                                            class="w-full text-left px-4 py-2 hover:bg-green-100 flex items-center">
                                            <i class="fas fa-file-image mr-2"></i> JPG
                                        </button>
                                        <button onclick="downloadPoster('pdf')"
                                            class="w-full text-left px-4 py-2 hover:bg-red-100 flex items-center">
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
                                class="w-full p-3 rounded-lg border border-gray-300 text-sm bg-gray-50 resize-none" rows="8" readonly></textarea>
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
        let selectedTemplate = 'modern';
        let selectedFont = fontSelect.value;
        let selectedIconG = 'assets/images/guardia.png'; // Valor por defecto
        let selectedIconCheck = 'assets/icons/icon_check1.png'; // Valor por defecto
        let selectedIconPhone = 'assets/icons/icon_phone1.png'; // Valor por defecto
        let selectedIconEmail = 'assets/icons/icon_email1.png'; // Valor por defecto

        // Mostrar la primera tab por defecto
        document.addEventListener('DOMContentLoaded', () => {
            showTab('tab-plantillas', document.querySelector('.option-tab'));
            const first = document.querySelector('.requirement-item');
            if (first) first.click();
            const tpl = document.querySelector(".template-option[onclick*=\"'modern'\"]");
            if (tpl) tpl.classList.add('selected');
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
@endsection
