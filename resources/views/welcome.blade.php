<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RECLUSOL - Plataforma de Reclutamiento</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="{{ asset('imagenes/logo_app.png') }}">

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #1d4ed8 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            transform: translateY(-1px);
        }
        .avatar-container {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border: 4px solid white;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm border-b">
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
                    <a href="{{ route('login') }}" class="flex items-center space-x-2 text-gray-600 hover:text-blue-600 transition-colors">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar Sesión</span>
                    </a>
                    <a href="{{ route('register') }}" class="btn-primary text-white px-6 py-2 rounded-lg font-medium">
                        <i class="fas fa-user-plus mr-2"></i>
                        Postular
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="gradient-bg min-h-screen flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white space-y-6">
                    <h1 class="text-5xl lg:text-6xl font-bold leading-tight">
                        Encuentra tu
                        <span class="block text-yellow-300">Próximo Empleo</span>
                    </h1>
                    <p class="text-xl text-blue-100 leading-relaxed">
                        Miles de personas ya forman parte de nuestra comunidad laboral. ¡Súmate!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 pt-4">
                        <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-rocket mr-2"></i>
                            Comenzar Ahora
                        </a>
                        <a href="#features" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors inline-flex items-center justify-center">
                            <i class="fas fa-info-circle mr-2"></i>
                            Saber Más
                        </a>
                    </div>
                </div>

                <!-- Right Content - Avatar Card -->
                <div class="flex justify-center">
                    <div class="bg-white rounded-3xl p-8 shadow-2xl max-w-md w-full">
                        <div class="text-center space-y-6">
                            <div class="avatar-container w-32 h-32 rounded-full mx-auto flex items-center justify-center">
                                <i class="fas fa-hard-hat text-4xl text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-800 mb-2">
                                    ¡Únete a SOLMAR Security!
                                </h3>
                                <p class="text-gray-600 leading-relaxed">
                                    Miles de profesionales ya encontraron su trabajo ideal con nosotros.
                                </p>
                            </div>
                            <div class="flex justify-center space-x-2">
                                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-300 rounded-full"></div>
                                <div class="w-2 h-2 bg-blue-300 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    ¿Por qué elegir a SOLMAR Security?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Descubre las ventajas que nos convierten en la plataforma líder de reclutamiento
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border">
                    <div class="w-20 h-20 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-address-book text-3xl text-orange-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Red de contactos</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Conecta con miles de empresas y profesionales en nuestra extensa red de contactos laborales.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-laptop-code text-3xl text-green-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Empleos de calidad</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Accede a oportunidades laborales verificadas y de alta calidad en empresas reconocidas.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="card-hover bg-white rounded-2xl p-8 text-center shadow-lg border">
                    <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-globe-americas text-3xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Infraestructura de primer nivel</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Plataforma robusta y segura con tecnología de vanguardia para una experiencia óptima.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="gradient-bg py-20">
        <div class="max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-white mb-6">
                ¿Listo para encontrar tu próximo empleo?
            </h2>
            <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                Únete a miles de profesionales que ya encontraron su trabajo ideal
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors inline-flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i>
                    Crear Cuenta Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-crown text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">RECLUSOL</h3>
                            <p class="text-gray-400 text-sm">Plataforma de Reclutamiento</p>
                        </div>
                    </div>
                    <p class="text-gray-400 leading-relaxed">
                        La plataforma líder en reclutamiento que conecta talento con oportunidades excepcionales.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Enlaces</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Empleos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Empresas</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sobre Nosotros</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contacto</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Síguenos</h4>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} RECLUSOL. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- Smooth Scroll Script -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>