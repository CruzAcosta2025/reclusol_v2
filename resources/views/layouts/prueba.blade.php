<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RECLUSOL') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_app.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- TailwindCSS CDN (funciona siempre, incluso si Vite no) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Alpine.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>

    <!-- En la sección <head> de tu layout, o antes del cierre de </body> -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased overflow-x-hidden">
    <div x-data="{
        sidebarOpen: window.innerWidth >= 768,
        isMobile: window.innerWidth < 768
    }"
        @resize.window="isMobile = window.innerWidth < 768; if(isMobile && !sidebarOpen) sidebarOpen = false;"
        @close-parent-sidebar.window="sidebarOpen = false" class="flex h-screen w-screen overflow-hidden">

        @include('components.sidebar', [
            'logo' => 'RECLUSOL',
            'subtitle' => 'Sistema de Gestión',
            'items' => [
                ['label' => 'Dashboard', 'href' => route('dashboard'), 'icon' => 'fa-tachometer-alt'],
                ['label' => 'Solicitudes', 'href' => route('requerimientos.filtrar'), 'icon' => 'fa-file-alt'],
                ['label' => 'Postulantes', 'href' => route('postulantes.filtrar'), 'icon' => 'fa-users'],
                ['label' => 'Afiches', 'href' => route('afiches.index'), 'icon' => 'fa-images'],
                ['label' => 'Entrevistas', 'href' => route('entrevistas.index'), 'icon' => 'fa-calendar-check'],
                ['label' => 'Usuarios', 'href' => route('usuarios.index'), 'icon' => 'fa-users-cog'],
                ['label' => 'Configuración', 'href' => '#', 'icon' => 'fa-cog'],
            ],
            'logout' => [
                'label' => 'Logout',
                'href' => route('logout'),
                'icon' => 'fa-sign-out-alt',
            ],
        ])

        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-md p-4 flex items-center flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded hover:bg-gray-200 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-7 bg-gray-50 overflow-y-auto overflow-x-hidden" style="min-width: 0;">
                @yield('content')
            </main>
        </div>

    </div>
</body>

</html>
