<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RECLUSOL - Plataforma de Reclutamiento</title>

    <!-- Mantengo tus dependencias (solo estética) -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_app.png') }}">

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
    </style>
</head>

<body class="text-gray-100 antialiased">
    <!-- Header -->
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

                        <a href="{{ route('postulantes.formExterno') }}"
                            class="btn-primary inline-flex items-center justify-center px-4 py-2 rounded-xl font-semibold w-full md:w-auto mt-2 md:mt-0">
                            <i class="fas fa-user-plus mr-2"></i>
                            Postular
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 rounded-full badge px-3 py-1 text-xs text-gray-200">
                        <span class="h-2 w-2 rounded-full bg-green-400"></span>
                        Postulación rápida • Validación • Entrevistas
                    </div>

                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight text-white">
                        Encuentra tu
                        <span class="block" style="color: rgba(165,180,252,1);">Próximo Empleo</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-gray-300 leading-relaxed max-w-2xl">
                        Miles de personas ya forman parte de nuestra comunidad laboral. ¡Súmate!
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 pt-2">
                        <a href="{{ route('login') }}"
                            class="btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold">
                            <i class="fas fa-rocket mr-2"></i>
                            Comenzar ahora
                        </a>

                        <a href="#features"
                            class="btn-ghost inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold text-gray-100">
                            <i class="fas fa-info-circle mr-2"></i>
                            Saber más
                        </a>
                    </div>

                    <div class="flex items-center gap-4 pt-2 text-sm text-gray-400">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-gray-300"></i>
                            Flujo seguro
                        </div>
                        <div class="h-4 w-px bg-gray-700"></div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-bolt text-gray-300"></i>
                            Respuesta rápida
                        </div>
                        <div class="h-4 w-px bg-gray-700"></div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-gray-300"></i>
                            Proceso ordenado
                        </div>
                    </div>
                </div>

                <!-- Right Content - Professional Card -->
                <div class="flex justify-center">
                    <div class="glass rounded-3xl p-8 w-full max-w-md card-hover">
                        <div class="text-center space-y-6">
                            <div class="w-28 h-28 rounded-2xl mx-auto avatar-ring flex items-center justify-center">
                                <div
                                    class="w-24 h-24 rounded-2xl bg-black bg-opacity-40 flex items-center justify-center overflow-hidden border border-white border-opacity-10">
                                    <img src="{{ asset('imagenes/SOLMAR_1.png') }}" alt="Logo SOLMAR"
                                        class="w-20 h-20 object-contain">
                                </div>
                            </div>

                            <div>
                                <h3 class="text-2xl font-semibold text-white mb-2">
                                    SOLMAR Security
                                </h3>
                                <p class="text-gray-300 leading-relaxed">
                                    Miles de profesionales ya encontraron su trabajo ideal con nosotros.
                                </p>
                            </div>

                            <div class="grid grid-cols-3 gap-3">
                                <div class="glass-soft rounded-2xl p-3 text-center">
                                    <div class="text-xl font-bold text-white">+1K</div>
                                    <div class="text-xs text-gray-400">Trabajadores</div>
                                </div>
                                <div class="glass-soft rounded-2xl p-3 text-center">
                                    <div class="text-xl font-bold text-white">24/7</div>
                                    <div class="text-xs text-gray-400">Acceso</div>
                                </div>
                                <div class="glass-soft rounded-2xl p-3 text-center">
                                    <div class="text-xl font-bold text-white">100%</div>
                                    <div class="text-xs text-gray-400">Digital</div>
                                </div>
                            </div>

                            <div class="flex justify-center space-x-2">
                                <div class="w-2 h-2 bg-white rounded-full"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                                <div class="w-2 h-2 bg-gray-500 rounded-full"></div>
                            </div>

                            <div class="pt-2">
                                <a href="{{ route('postulantes.formExterno') }}"
                                    class="btn-ghost inline-flex items-center justify-center w-full px-6 py-3 rounded-xl font-semibold">
                                    <i class="fas fa-user-check mr-2"></i>
                                    Postular ahora
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /Right -->
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16 sm:py-20">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12 sm:mb-16">
                <h2 class="text-2xl sm:text-4xl font-bold text-white mb-4">
                    ¿Por qué elegir a SOLMAR Security?
                </h2>
                <p class="text-base sm:text-xl text-gray-300 max-w-3xl mx-auto">
                    Descubre las ventajas que nos convierten en una plataforma sólida y confiable para reclutamiento.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
                <!-- Feature 1 -->
                <div class="card-hover glass rounded-3xl p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl mx-auto mb-6 flex items-center justify-center glass-soft">
                        <i class="fas fa-address-book text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Red de contactos</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Conecta con empresas y profesionales en una red laboral en crecimiento.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover glass rounded-3xl p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl mx-auto mb-6 flex items-center justify-center glass-soft">
                        <i class="fas fa-laptop-code text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Empleos de calidad</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Accede a oportunidades verificadas y con información clara para postular con confianza.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover glass rounded-3xl p-8 text-center">
                    <div class="w-16 h-16 rounded-2xl mx-auto mb-6 flex items-center justify-center glass-soft">
                        <i class="fas fa-globe-americas text-2xl text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-3">Infraestructura robusta</h3>
                    <p class="text-gray-300 leading-relaxed">
                        Experiencia fluida, segura y optimizada para postulantes y reclutadores.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    {{--
        <section class="py-16 sm:py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass rounded-3xl p-10 text-center">
                <h2 class="text-2xl sm:text-4xl font-bold text-white mb-4">
                    ¿Listo para encontrar tu próximo empleo?
                </h2>
                <p class="text-base sm:text-xl text-gray-300 mb-8 leading-relaxed">
                    Únete a miles de profesionales que ya encontraron su trabajo ideal.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="{{ route('postulantes.formExterno') }}"
                        class="btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-user-plus mr-2"></i>
                        Postular
                    </a>
                    <a href="{{ route('login') }}"
                        class="btn-ghost inline-flex items-center justify-center px-6 py-3 rounded-xl font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Iniciar sesión
                    </a>
                </div>
            </div>
        </div>
    </section>
    --}}

    <!-- Footer -->
        <footer class="border-t border-gray-800 bg-black bg-opacity-40">
        <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="h-11 w-11 rounded-xl overflow-hidden glass-soft flex items-center justify-center">
                            <img src="{{ asset('imagenes/logo_app.png') }}" alt="Logo RECLUSOL"
                                class="w-10 h-10 object-contain">
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">RECLUSOL</h3>
                            <p class="text-sm text-gray-400">Plataforma de Reclutamiento</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed max-w-xl">
                        Una experiencia moderna y segura para conectar talento con oportunidades.
                    </p>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-white">Enlaces</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="https://gruposolmar.pe/" class="hover:text-white transition-colors">¿Quiénes Somos?</a></li>
                        <li><a href="{{ route('postulantes.formExterno') }}"
                                class="hover:text-white transition-colors">Postular</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-white transition-colors">Iniciar
                                sesión</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold mb-4 text-white">Síguenos</h4>
                    <div class="flex space-x-4 text-gray-400">
                        <a href="https://www.facebook.com/p/Solmar-Security-100083610968047/" class="hover:text-white transition-colors" aria-label="Facebook">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="https://www.instagram.com/solmar.security/" class="hover:text-white transition-colors" aria-label="Instagram">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/grupo-solmar/?originalSubdomain=pe" class="hover:text-white transition-colors" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-10 pt-8 text-center text-gray-500">
                <p>&copy; {{ date('Y') }} SOLMAR SECURITY. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
    

    <!-- Smooth Scroll Script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = this.getAttribute('href');
                if (!target || target === '#') return;
                const el = document.querySelector(target);
                if (!el) return;

                e.preventDefault();
                el.scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>

    <!-- Alpine.js por CDN -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js" defer></script>
</body>

</html>
