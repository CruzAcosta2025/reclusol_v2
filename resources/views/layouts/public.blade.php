<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'RECLUSOL')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <header class="sticky top-0 z-50 bg-white/80 backdrop-blur border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span
                    class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-slate-900 text-white font-bold">
                    R
                </span>
                <div class="leading-tight">
                    <div class="font-extrabold tracking-tight">RECLUSOL</div>
                    <div class="text-xs text-slate-500 -mt-0.5">Plataforma de reclutamiento</div>
                </div>
            </a>

            <div class="flex items-center gap-2">
                <a href="{{ route('login') }}"
                    class="px-4 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-sm font-semibold transition">
                    Iniciar sesión
                </a>
            </div>
        </div>
    </header>

    <main class="min-h-[calc(100vh-64px)]">
        @yield('content')
    </main>
</body>

</html>
