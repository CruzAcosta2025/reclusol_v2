@extends('components.app-layout')

@section('content')
    <div class="min-h-screen gradient-bg flex items-center justify-center p-4 sm:p-0 pt-8 sm:pt-0 relative">
        <!-- Botón arriba solo escritorio -->
        <a href="{{ route('welcome') }}"
            class="hidden sm:flex absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors items-center space-x-2 group z-50">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al inicio</span>
        </a>

        <div class="w-full max-w-4xl sm:max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-12 items-center">
                <!-- Left Content -->
                <div class="text-white space-y-8 text-center lg:text-left">
                    <!-- Botón solo móviles -->
                    <div class="block sm:hidden w-full flex justify-start mb-2">
                        <a href="{{ route('welcome') }}"
                            class="flex items-center bg-blue-600 px-4 py-2 rounded-lg text-white hover:text-yellow-300 transition-colors space-x-2 group">
                            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                            <span class="font-medium">Volver al inicio</span>
                        </a>
                    </div>
                    <!-- Título, solo se baja en móvil -->
                    <div class="space-y-4">
                        <h1 class="text-2xl sm:text-4xl lg:text-5xl font-bold leading-tight mt-2 sm:mt-0">
                            Bienvenido de vuelta a
                            <span class="block text-yellow-300">RECLUSOL</span>
                        </h1>
                        <p class="text-base sm:text-xl text-blue-100 leading-relaxed max-w-md mx-auto lg:mx-0">
                            Accede a tu cuenta y continúa construyendo un mejor futuro para el país.
                        </p>
                    </div>

                    <!-- Animated Lock Icon -->
                    <div class="flex justify-center lg:justify-start">
                        <div
                            class="lock-icon w-24 h-24 sm:w-32 sm:h-32 rounded-full flex items-center justify-center floating-animation">
                            <i class="fas fa-shield-alt text-4xl sm:text-5xl text-white"></i>
                        </div>
                    </div>

                    <!-- Features List -->
                    <div class="space-y-4 max-w-md mx-auto lg:mx-0">
                        <div class="flex items-center space-x-3 text-blue-100">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span class="text-sm sm:text-base">Acceso seguro y protegido</span>
                        </div>
                        <div class="flex items-center space-x-3 text-blue-100">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span class="text-sm sm:text-base">Miles de oportunidades laborales</span>
                        </div>
                        <div class="flex items-center space-x-3 text-blue-100">
                            <i class="fas fa-check-circle text-green-400"></i>
                            <span class="text-sm sm:text-base">Conecta con empresas líderes</span>
                        </div>
                    </div>
                </div>

                <!-- Login Form -->
                <div class="flex justify-center">
                    <div class="bg-white rounded-3xl shadow-2xl p-5 sm:p-8 w-full max-w-md slide-in">
                        <div class="text-center mb-6 sm:mb-8">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-2">Iniciar Sesión</h2>
                            <p class="text-gray-600 text-sm sm:text-base">Ingresa tus credenciales para acceder a tu cuenta
                            </p>
                        </div>

                        <!-- Session Status -->
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <!-- Alertas de error bonitas -->
                        @if (session('login_error') || session('status') || $errors->any())
                            <div id="login-error" aria-live="assertive" class="mb-6 max-w-md mx-auto">
                                <div
                                    class="flex items-start space-x-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-4 shadow-sm animate-fade-in">
                                    <div class="pt-1 text-2xl">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="font-semibold">Error al iniciar sesión</p>
                                                <p class="text-sm mt-1">
                                                    @if (session('login_error'))
                                                        {{ session('login_error') }}
                                                    @elseif(session('status'))
                                                        {{ session('status') }}
                                                    @else
                                                        {{-- Muestra el primer error (validación) --}}
                                                        {{ $errors->first() }}
                                                    @endif
                                                </p>
                                            </div>

                                            <button type="button" onclick="dismissError()"
                                                class="ml-4 text-red-600 hover:text-red-800"
                                                aria-label="Cerrar mensaje de error">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}" class="space-y-6" autocomplete="off">
                            @csrf
                            <!-- USUARIO -->
                            <div class="space-y-2">
                                <x-input-label for="usuario" class="block text-base font-semibold text-blue-700">
                                    <i class="fas fa-user mr-2 text-blue-500"></i>
                                    {{ __('Usuario') }}
                                </x-input-label>
                                <x-text-input id="usuario"
                                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 hover:shadow-lg focus:shadow-lg focus:-translate-y-1"
                                    type="text" name="usuario" required autofocus autocomplete="off"
                                    placeholder="Ingrese usuario" />
                                <x-input-error :messages="$errors->get('usuario')" class="mt-2" />
                            </div>

                            <!-- CONTRASEÑA -->
                            <div class="space-y-2">
                                <x-input-label for="contrasena" class="block text-base font-semibold text-blue-700">
                                    <i class="fas fa-lock mr-2 text-blue-500"></i>
                                    {{ __('Contraseña') }}
                                </x-input-label>
                                <div class="relative">
                                    <x-text-input id="contrasena"
                                        class="form-input w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 hover:shadow-lg focus:shadow-lg focus:-translate-y-1"
                                        type="password" name="contrasena" required autocomplete="off"
                                        placeholder="••••••••" />
                                    <button type="button" onclick="togglePassword()"
                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-blue-500 transition-colors">
                                        <i id="password-icon" class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <x-input-error :messages="$errors->get('contrasena')" class="mt-2" />
                            </div>

                            <!-- Login Button -->
                            <x-primary-button
                                class="btn-primary w-full text-white py-3 rounded-lg font-semibold text-base sm:text-lg flex items-center justify-center space-x-2 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                                <i class="fas fa-sign-in-alt"></i>
                                <span>{{ __('Log in') }}</span>
                            </x-primary-button>
                        </form>

                        <!-- Divider -->
                        <div class="my-8 flex items-center">
                            <div class="flex-1 border-t border-gray-300"></div>
                            <div class="flex-1 border-t border-gray-300"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Iniciando sesión...</span>
            </div>
        </div>
    </div>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 50%, #1d4ed8 100%);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8) !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af) !important;
        }

        .lock-icon {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        /* Animación sutil */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 220ms ease-out;
        }


        .slide-in {
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>

    <script>
        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('contrasena');
            const passwordIcon = document.getElementById('password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }

        // Show loading overlay on form submit
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        });

        // Add focus effects to form inputs
        document.querySelectorAll('.form-input').forEach(function(input) {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('transform', 'scale-105');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('transform', 'scale-105');
            });
        });

        function dismissError() {
            const el = document.getElementById('login-error');
            if (!el) return;
            el.style.transition = 'opacity .25s ease, transform .25s ease';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';
            setTimeout(() => el.remove(), 300);
        }

        // Auto-ocultar después de 6 segundos
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('login-error');
            if (el) {
                setTimeout(dismissError, 6000);
            }

            // (tu código previo) efectos de focus...
        });

        // Mostrar overlay al enviar
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        });

        window.onload = function() {
            document.getElementById('usuario').value = '';
            document.getElementById('contrasena').value = '';
        };
    </script>
@endsection
