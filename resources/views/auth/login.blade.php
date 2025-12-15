@extends('components.app-layout')

@section('content')
    <div
        class="min-h-screen bg-slate-950 text-slate-100 flex items-center justify-center p-4 sm:p-0 pt-8 sm:pt-0 relative overflow-hidden">
        <!-- Decorative background -->
        <div class="pointer-events-none absolute inset-0">
            <div class="absolute -top-24 -left-24 h-72 w-72 rounded-full bg-white/5 blur-3xl"></div>
            <div class="absolute top-10 right-0 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
            <div class="absolute bottom-0 left-1/3 h-80 w-80 rounded-full bg-emerald-500/10 blur-3xl"></div>
        </div>

        <!-- Botón arriba solo escritorio -->
        <a href="{{ route('welcome') }}"
            class="hidden sm:flex absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors items-center space-x-2 group z-50">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al inicio</span>
        </a>

        <div class="w-full max-w-md mx-auto relative z-10">
            <!-- Login Form -->
            <div class="flex justify-center">
                <div
                    class="bg-slate-900/70 border border-white/10 rounded-3xl shadow-2xl shadow-black/40 backdrop-blur-xl p-6 sm:p-8 w-full max-w-md slide-in">
                    <div class="text-center mb-6 sm:mb-8">
                        <h2 class="text-2xl sm:text-3xl font-bold text-slate-100 mb-2">Iniciar Sesión</h2>
                        <p class="text-slate-300 text-sm sm:text-base">Ingresa tus credenciales para acceder a tu cuenta
                        </p>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />
                    <!-- Alertas de error bonitas -->
                    @if (session('login_error') || session('status') || $errors->any())
                        <div id="login-error" aria-live="assertive" class="mb-6 max-w-md mx-auto">
                            <div
                                class="flex items-start gap-4 bg-red-500/10 border border-red-500/30 text-red-200 rounded-xl p-4 shadow-lg shadow-black/20 backdrop-blur animate-fade-in">
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
                                            class="ml-4 text-red-200/80 hover:text-red-100"
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
                            <x-input-label for="usuario" class="block text-base font-semibold text-slate-200">
                                <i class="fas fa-user mr-2 text-slate-400"></i>
                                {{ __('Usuario') }}
                            </x-input-label>
                            <x-text-input id="usuario"
                                class="form-input bg-slate-950/40 text-slate-100 placeholder-slate-500 w-full px-4 py-3 border border-white/10 rounded-lg focus:ring-2 focus:ring-white/20 focus:border-transparent outline-none transition-all duration-300 hover:shadow-lg focus:shadow-lg focus:-translate-y-1"
                                type="text" name="usuario" required autofocus autocomplete="off"
                                placeholder="Ingrese usuario" />
                            <x-input-error :messages="$errors->get('usuario')" class="mt-2" />
                        </div>

                        <!-- CONTRASEÑA -->
                        <div class="space-y-2">
                            <x-input-label for="contrasena" class="block text-base font-semibold text-slate-200">
                                <i class="fas fa-lock mr-2 text-slate-400"></i>
                                {{ __('Contraseña') }}
                            </x-input-label>
                            <div class="relative">
                                <x-text-input id="contrasena"
                                    class="form-input bg-slate-950/40 text-slate-100 placeholder-slate-500 w-full px-4 py-3 pr-12 border border-white/10 rounded-lg focus:ring-2 focus:ring-white/20 focus:border-transparent outline-none transition-all duration-300 hover:shadow-lg focus:shadow-lg focus:-translate-y-1"
                                    type="password" name="contrasena" required autocomplete="off" placeholder="••••••••" />
                                <button type="button" onclick="togglePassword()"
                                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-slate-400 hover:text-slate-400 transition-colors">
                                    <i id="password-icon" class="fas fa-eye"></i>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('contrasena')" class="mt-2" />
                        </div>

                        <!-- Login Button -->
                        <x-primary-button
                            class="btn-primary w-full inline-flex items-center justify-center gap-2 rounded-xl px-4 py-3 font-semibold shadow-lg shadow-black/30 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">
                            <i class="fas fa-sign-in-alt"></i>
                            <span>{{ __('Log in') }}</span>
                        </x-primary-button>
                    </form>

                    <!-- Divider -->
                    <div class="my-8 flex items-center">
                        <div class="flex-1 border-t border-white/10"></div>
                        <div class="flex-1 border-t border-white/10"></div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Loading Overlay -->
        <div id="loading-overlay" class="fixed inset-0 bg-black/70 flex items-center justify-center hidden z-50">
            <div
                class="bg-slate-900/80 border border-white/10 rounded-2xl p-6 flex items-center gap-4 shadow-xl shadow-black/40 backdrop-blur-xl">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white/60"></div>
                <span class="text-slate-200 font-medium">Iniciando sesión...</span>
            </div>
        </div>
    </div>

    <style>
        .gradient-bg {
            background: radial-gradient(1200px circle at 20% 0%, rgba(99, 102, 241, .20), transparent 55%), radial-gradient(900px circle at 80% 20%, rgba(16, 185, 129, .14), transparent 55%), linear-gradient(135deg, #020617 0%, #0b1220 55%, #020617 100%);
        }

        .form-input {
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #ffffff, #e2e8f0) !important;
            color: #0f172a !important;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #e2e8f0, #cbd5e1) !important;
            color: #0f172a !important;
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
