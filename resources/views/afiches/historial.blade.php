<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Afiches - RECLUSOL</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #1d4ed8 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }

        .status-activo {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .status-pausado {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .status-finalizado {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .status-borrador {
            background: linear-gradient(135deg, #6b7280, #4b5563);
        }

        .afiche-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .timeline-line {
            background: linear-gradient(to bottom, #3b82f6, #1d4ed8);
        }

        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-crown text-white text-lg"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-blue-600">RECLUSOL</h1>
                        <p class="text-xs text-gray-500">Plataforma de Reclutamiento</p>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-2"></i>
                        Inicio
                    </a>
                    <a href="#" class="text-blue-600 font-medium">
                        <i class="fas fa-images mr-2"></i>
                        Historial de Afiches
                    </a>
                    <button class="btn-primary text-white px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-plus mr-2"></i>
                        Nuevo Afiche
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-white">
                <h1 class="text-4xl lg:text-5xl font-bold mb-4">
                    Historial de Afiches
                </h1>
                <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                    Gestiona y revisa el historial completo de todos los afiches publicados
                </p>
            </div>
        </div>
    </section>

    <!-- Stats Dashboard -->
    <section class="py-8 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-5 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Afiches</p>
                            <p class="text-3xl font-bold">{{ $totalAfiches ?? 156 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-images text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Activos</p>
                            <p class="text-3xl font-bold">{{ $afichesActivos ?? 89 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-play-circle text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium">En Pausa</p>
                           {{--  <p class="text-3xl font-bold">{{ $afiches Pausados ?? 23 }}</p> --}}
                        </div>
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-pause-circle text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Finalizados</p>
                            <p class="text-3xl font-bold">{{ $afichesFinalizados ?? 44 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-gray-500 to-gray-600 rounded-xl p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-100 text-sm font-medium">Este Mes</p>
                            <p class="text-3xl font-bold">{{ $afichesEsteMes ?? 18 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-xl"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters Section -->
    <section class="py-6 bg-gradient-to-r from-blue-600 to-blue-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glassmorphism rounded-2xl p-6 shadow-lg">
                <div class="grid md:grid-cols-6 gap-4 items-center">
                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Buscar</label>
                        <div class="relative">
                            <input type="text" placeholder="Título o empresa..."
                                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Estado</label>
                        <select class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90">
                            <option value="">Todos</option>
                            <option value="activo">Activo</option>
                            <option value="pausado">Pausado</option>
                            <option value="finalizado">Finalizado</option>
                            <option value="borrador">Borrador</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Categoría</label>
                        <select class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90">
                            <option value="">Todas</option>
                            <option value="tecnologia">Tecnología</option>
                            <option value="marketing">Marketing</option>
                            <option value="ventas">Ventas</option>
                            <option value="recursos-humanos">RRHH</option>
                            <option value="administracion">Administración</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Fecha Desde</label>
                        <input type="date" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-white mb-2">Fecha Hasta</label>
                        <input type="date" class="w-full py-2 px-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/90">
                    </div>

                    <div class="flex items-end space-x-2">
                        <button class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                            <i class="fas fa-filter mr-2"></i>
                            Filtrar
                        </button>
                        <button class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline View Toggle -->
    <section class="py-4 bg-gray-100 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-800">Vista del Historial</h2>
                    <div class="flex bg-white rounded-lg p-1 shadow-sm">
                        <button id="gridView" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md transition-colors">
                            <i class="fas fa-th-large mr-2"></i>Cuadrícula
                        </button>
                        <button id="timelineView" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 rounded-md transition-colors">
                            <i class="fas fa-stream mr-2"></i>Línea de Tiempo
                        </button>
                        <button id="listView" class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 rounded-md transition-colors">
                            <i class="fas fa-list mr-2"></i>Lista
                        </button>
                    </div>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600">Ordenar por:</span>
                    <select class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="fecha_desc">Fecha (Más reciente)</option>
                        <option value="fecha_asc">Fecha (Más antiguo)</option>
                        <option value="titulo">Título A-Z</option>
                        <option value="estado">Estado</option>
                        <option value="visualizaciones">Visualizaciones</option>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <!-- Grid View (Default) -->
    <section id="gridContent" class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($afiches ?? collect([
                (object)['id' => 1, 'titulo' => 'Desarrollador Full Stack Senior', 'empresa' => 'TechCorp', 'categoria' => 'tecnologia', 'fecha_creacion' => '2024-01-15', 'fecha_expiracion' => '2024-02-15', 'estado' => 'activo', 'visualizaciones' => 1247, 'postulaciones' => 45, 'imagen' => 'tech-job.jpg'],
                (object)['id' => 2, 'titulo' => 'Especialista en Marketing Digital', 'empresa' => 'MarketPro', 'categoria' => 'marketing', 'fecha_creacion' => '2024-01-14', 'fecha_expiracion' => '2024-02-14', 'estado' => 'activo', 'visualizaciones' => 892, 'postulaciones' => 32, 'imagen' => 'marketing-job.jpg'],
                (object)['id' => 3, 'titulo' => 'Gerente de Ventas Regional', 'empresa' => 'SalesCorp', 'categoria' => 'ventas', 'fecha_creacion' => '2024-01-12', 'fecha_expiracion' => '2024-02-12', 'estado' => 'pausado', 'visualizaciones' => 634, 'postulaciones' => 28, 'imagen' => 'sales-job.jpg'],
                (object)['id' => 4, 'titulo' => 'Analista de Recursos Humanos', 'empresa' => 'HR Solutions', 'categoria' => 'recursos-humanos', 'fecha_creacion' => '2024-01-10', 'fecha_expiracion' => '2024-02-10', 'estado' => 'finalizado', 'visualizaciones' => 445, 'postulaciones' => 67, 'imagen' => 'hr-job.jpg'],
                (object)['id' => 5, 'titulo' => 'Coordinador Administrativo', 'empresa' => 'AdminCorp', 'categoria' => 'administracion', 'fecha_creacion' => '2024-01-08', 'fecha_expiracion' => '2024-02-08', 'estado' => 'activo', 'visualizaciones' => 823, 'postulaciones' => 51, 'imagen' => 'admin-job.jpg'],
                (object)['id' => 6, 'titulo' => 'Diseñador UX/UI', 'empresa' => 'CreativeStudio', 'categoria' => 'tecnologia', 'fecha_creacion' => '2024-01-05', 'fecha_expiracion' => '2024-02-05', 'estado' => 'borrador', 'visualizaciones' => 156, 'postulaciones' => 12, 'imagen' => 'design-job.jpg']
                ]) as $afiche)
                <div class="afiche-card card-hover rounded-2xl p-6 shadow-lg">
                    <!-- Afiche Image/Preview -->
                    <div class="relative mb-4">
                        <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 rounded-xl flex items-center justify-center overflow-hidden">
                            <div class="text-center">
                                <i class="fas fa-briefcase text-4xl text-blue-500 mb-2"></i>
                                <p class="text-sm text-gray-600 font-medium">{{ ucfirst($afiche->categoria) }}</p>
                            </div>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white
                                    @if($afiche->estado === 'activo') status-activo
                                    @elseif($afiche->estado === 'pausado') status-pausado
                                    @elseif($afiche->estado === 'finalizado') status-finalizado
                                    @else status-borrador
                                    @endif">
                                @if($afiche->estado === 'activo') <i class="fas fa-play mr-1"></i> @endif
                                @if($afiche->estado === 'pausado') <i class="fas fa-pause mr-1"></i> @endif
                                @if($afiche->estado === 'finalizado') <i class="fas fa-check mr-1"></i> @endif
                                @if($afiche->estado === 'borrador') <i class="fas fa-edit mr-1"></i> @endif
                                {{ ucfirst($afiche->estado) }}
                            </span>
                        </div>
                    </div>

                    <!-- Afiche Info -->
                    <div class="space-y-3">
                        <div>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $afiche->titulo }}</h3>
                            <p class="text-blue-600 font-medium">{{ $afiche->empresa }}</p>
                        </div>

                        <div class="flex items-center justify-between text-sm text-gray-600">
                            <span class="flex items-center">
                                <i class="fas fa-calendar mr-1"></i>
                                {{ \Carbon\Carbon::parse($afiche->fecha_creacion)->format('d/m/Y') }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-clock mr-1"></i>
                                {{ \Carbon\Carbon::parse($afiche->fecha_expiracion)->diffForHumans() }}
                            </span>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-2 gap-4 pt-3 border-t border-gray-200">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ number_format($afiche->visualizaciones) }}</div>
                                <div class="text-xs text-gray-500">Visualizaciones</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $afiche->postulaciones }}</div>
                                <div class="text-xs text-gray-500">Postulaciones</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-2 pt-3">
                            <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-eye mr-1"></i>Ver
                            </button>
                            <button class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 px-3 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-edit mr-1"></i>Editar
                            </button>
                            <button class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-3 rounded-lg text-sm transition-colors">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-images text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">No hay afiches en el historial</h3>
                    <p class="text-gray-500">Aún no se han creado afiches.</p>
                </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Mostrando <span class="font-medium">1</span> a <span class="font-medium">6</span> de <span class="font-medium">{{ $totalAfiches ?? 156 }}</span> resultados
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-chevron-left mr-1"></i>Anterior
                    </button>
                    <button class="px-3 py-2 text-sm bg-blue-600 text-white rounded-lg">1</button>
                    <button class="px-3 py-2 text-sm text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">2</button>
                    <button class="px-3 py-2 text-sm text-gray-700 hover:text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">3</button>
                    <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        Siguiente<i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Timeline View (Hidden by default) -->
    <section id="timelineContent" class="py-8 bg-gray-50 hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative">
                <!-- Timeline Line -->
                <div class="absolute left-6 top-0 bottom-0 w-0.5 timeline-line"></div>

                <!-- Timeline Items -->
                <div class="space-y-8">
                    @foreach($afiches ?? collect([
                    (object)['id' => 1, 'titulo' => 'Desarrollador Full Stack Senior', 'empresa' => 'TechCorp', 'fecha_creacion' => '2024-01-15', 'estado' => 'activo', 'accion' => 'Publicado'],
                    (object)['id' => 2, 'titulo' => 'Especialista en Marketing Digital', 'empresa' => 'MarketPro', 'fecha_creacion' => '2024-01-14', 'estado' => 'activo', 'accion' => 'Actualizado'],
                    (object)['id' => 3, 'titulo' => 'Gerente de Ventas Regional', 'empresa' => 'SalesCorp', 'fecha_creacion' => '2024-01-12', 'estado' => 'pausado', 'accion' => 'Pausado'],
                    (object)['id' => 4, 'titulo' => 'Analista de Recursos Humanos', 'empresa' => 'HR Solutions', 'fecha_creacion' => '2024-01-10', 'estado' => 'finalizado', 'accion' => 'Finalizado']
                    ]) as $item)
                    <div class="relative flex items-start space-x-4">
                        <!-- Timeline dot -->
                        <div class="relative z-10 w-3 h-3 bg-blue-600 rounded-full border-2 border-white shadow-md mt-2"></div>

                        <!-- Content -->
                        <div class="flex-1 bg-white rounded-lg shadow-sm border p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-2">
                                        <h3 class="font-semibold text-gray-800">{{ $item->titulo }}</h3>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium text-white
                                                @if($item->estado === 'activo') status-activo
                                                @elseif($item->estado === 'pausado') status-pausado
                                                @elseif($item->estado === 'finalizado') status-finalizado
                                                @else status-borrador
                                                @endif">
                                            {{ ucfirst($item->estado) }}
                                        </span>
                                    </div>
                                    <p class="text-gray-600 mb-1">{{ $item->empresa }}</p>
                                    <p class="text-sm text-gray-500">{{ $item->accion }} - {{ \Carbon\Carbon::parse($item->fecha_creacion)->format('d/m/Y H:i') }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 p-1 rounded transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 p-1 rounded transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- List View (Hidden by default) -->
    <section id="listContent" class="py-8 bg-gray-50 hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr class="text-left">
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Afiche</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Estadísticas</th>
                            <th class="px-6 py-4 text-sm font-semibold text-gray-600 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($afiches ?? collect([]) as $afiche)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-briefcase text-white"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-800">{{ $afiche->titulo }}</h3>
                                        <p class="text-sm text-gray-500">{{ ucfirst($afiche->categoria) }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $afiche->empresa }}</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium text-white
                                        @if($afiche->estado === 'activo') status-activo
                                        @elseif($afiche->estado === 'pausado') status-pausado
                                        @elseif($afiche->estado === 'finalizado') status-finalizado
                                        @else status-borrador
                                        @endif">
                                    {{ ucfirst($afiche->estado) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ \Carbon\Carbon::parse($afiche->fecha_creacion)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <div class="text-gray-800">{{ number_format($afiche->visualizaciones) }} vistas</div>
                                    <div class="text-gray-500">{{ $afiche->postulaciones }} postulaciones</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="text-green-600 hover:text-green-800 p-2 hover:bg-green-50 rounded-lg transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="text-purple-600 hover:text-purple-800 p-2 hover:bg-purple-50 rounded-lg transition-colors">
                                        <i class="fas fa-chart-bar"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-center space-x-3">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-crown text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold">RECLUSOL</h3>
                    <p class="text-gray-400 text-xs">Plataforma de Reclutamiento</p>
                </div>
            </div>
            <div class="text-center text-gray-400 mt-4">
                <p>&copy; {{ date('Y') }} RECLUSOL. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // View toggle functionality
        const gridView = document.getElementById('gridView');
        const timelineView = document.getElementById('timelineView');
        const listView = document.getElementById('listView');
        const gridContent = document.getElementById('gridContent');
        const timelineContent = document.getElementById('timelineContent');
        const listContent = document.getElementById('listContent');

        function setActiveView(activeBtn, activeContent) {
            // Reset all buttons
            [gridView, timelineView, listView].forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('text-gray-600');
            });

            // Reset all content
            [gridContent, timelineContent, listContent].forEach(content => {
                content.classList.add('hidden');
            });

            // Set active
            activeBtn.classList.add('bg-blue-600', 'text-white');
            activeBtn.classList.remove('text-gray-600');
            activeContent.classList.remove('hidden');
        }

        gridView.addEventListener('click', () => setActiveView(gridView, gridContent));
        timelineView.addEventListener('click', () => setActiveView(timelineView, timelineContent));
        listView.addEventListener('click', () => setActiveView(listView, listContent));

        // Filter functionality
        document.querySelector('.btn-primary').addEventListener('click', function() {
            // Implement filter logic here
            console.log('Aplicando filtros...');
        });

        // Sort functionality
        document.querySelector('select').addEventListener('change', function() {
            console.log('Ordenando por:', this.value);
            // Implement sort logic here
        });
    </script>
</body>

</html>