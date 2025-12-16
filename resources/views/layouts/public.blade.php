<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RECLUSOL') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_app.png') }}">

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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<style>
    /* ====== Estilo profesional (oscuro) ====== */
    :root {
        --bg0: #050711;
        --bg1: #0b1220;
        --card: rgba(255, 255, 255, .06);
        --card2: rgba(255, 255, 255, .04);
        --border: rgba(255, 255, 255, .10);
        --border2: rgba(255, 255, 255, .16);
        --shadow: 0 24px 60px rgba(0, 0, 0, .55);
        --shadow2: 0 10px 30px rgba(0, 0, 0, .45);
    }

    body {
        background: radial-gradient(1200px circle at 10% 10%, rgba(99, 102, 241, .18), transparent 45%),
            radial-gradient(900px circle at 90% 20%, rgba(16, 185, 129, .14), transparent 40%),
            radial-gradient(700px circle at 35% 90%, rgba(236, 72, 153, .10), transparent 45%),
            linear-gradient(180deg, var(--bg0) 0%, var(--bg1) 100%);
    }

    .gradient-bg {
        background: radial-gradient(1200px circle at 10% 10%, rgba(99, 102, 241, .22), transparent 45%),
            radial-gradient(900px circle at 90% 20%, rgba(16, 185, 129, .16), transparent 40%),
            radial-gradient(700px circle at 35% 90%, rgba(236, 72, 153, .12), transparent 45%),
            linear-gradient(180deg, #020617 0%, #0b1220 100%);
    }

    .glass {
        background: var(--card);
        border: 1px solid var(--border);
        box-shadow: var(--shadow2);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }

    .glass-soft {
        background: var(--card2);
        border: 1px solid var(--border);
    }

    .card-hover {
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .card-hover:hover {
        transform: translateY(-6px);
        box-shadow: var(--shadow);
        border-color: var(--border2);
    }

    .btn-primary {
        background: linear-gradient(135deg, #ffffff 0%, #e5e7eb 100%);
        color: #0b1220;
        transition: transform .2s ease, box-shadow .2s ease, opacity .2s ease;
        box-shadow: 0 18px 40px rgba(0, 0, 0, .35);
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        opacity: .96;
    }

    .btn-ghost {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .14);
        transition: transform .2s ease, background .2s ease, border-color .2s ease;
    }

    .btn-ghost:hover {
        transform: translateY(-1px);
        background: rgba(255, 255, 255, .09);
        border-color: rgba(255, 255, 255, .20);
    }

    .badge {
        background: rgba(255, 255, 255, .06);
        border: 1px solid rgba(255, 255, 255, .12);
    }

    .avatar-ring {
        background: linear-gradient(135deg, rgba(99, 102, 241, .9), rgba(16, 185, 129, .85));
        box-shadow: 0 18px 40px rgba(0, 0, 0, .35);
    }

    /* ===== Fix definitivo para inputs en tema oscuro ===== */
    .form-input {
        background-color: #ffffff !important;
        border: 2px solid #d1d5db !important;
        color: #000000 !important;
        font-weight: 700 !important;
        caret-color: #000000 !important;
    }

    .form-input::placeholder {
        color: #6b7280 !important;
        opacity: 1 !important;
    }

    .form-input:focus {
        background-color: #ffffff !important;
        color: #000000 !important;
        border-color: #3b82f6 !important;
        outline: none !important;
    }

    .form-input option {
        color: #000000 !important;
        background-color: #ffffff !important;
        font-weight: 600 !important;
    }

    .form-input:disabled {
        background-color: #f3f4f6 !important;
        color: #9ca3af !important;
        cursor: not-allowed;
    }

    /* Labels visibles en oscuro (sin editar cada label) */
    label {
        color: #1f2937 !important;
    }
</style>

<body class="text-gray-100 antialiased">
    <header class="sticky top-0 z-50 border-b border-gray-800 bg-black bg-opacity-40"
        style="backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="h-11 w-11 rounded-xl overflow-hidden glass-soft flex items-center justify-center">
                        <img src="{{ asset('imagenes/logo_app.png') }}" alt="Logo RECLUSOL"
                            class="w-10 h-10 object-contain">
                    </div>
                    <div class="leading-tight">
                        <h1 class="text-lg sm:text-xl font-semibold tracking-wide text-white">RECLUSOL</h1>
                        <p class="text-xs text-gray-400">Plataforma de Reclutamiento</p>
                    </div>
                </div>

                <!-- Navigation con Alpine.js (misma lógica) -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="md:hidden text-gray-200 text-2xl focus:outline-none">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div :class="{ 'block': open, 'hidden': !open }"
                        class="absolute right-0 mt-3 w-56 rounded-2xl glass p-3 md:p-0 md:static md:mt-0 md:w-auto md:bg-transparent md:border-0 md:shadow-none md:flex md:items-center md:space-x-3 hidden">
                        <a href="{{ route('login') }}"
                            class="btn-ghost inline-flex items-center justify-center space-x-2 text-gray-100 px-4 py-2 rounded-xl font-medium w-full md:w-auto">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>Iniciar sesión</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="min-h-[calc(100vh-64px)]">
        @yield('content')
    </main>
</body>

</html>
