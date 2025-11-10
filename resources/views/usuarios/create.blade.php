@extends('layouts.app')

@section('content')
    <div class="p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800 flex items-center">
                <i class="fas fa-user-plus text-blue-600 mr-2"></i>
                Crear Nuevo Usuario
            </h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>

        <form id="create-user-form" action="{{ route('usuarios.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div class="space-y-2">
                    <label for="dni" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-id-card mr-2 text-blue-500"></i>
                        DNI *
                    </label>
                    <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{8}"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                        placeholder="12345678">
                    <span class="error-message text-red-500 text-sm hidden"></span>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <!-- Nombres -->
                <div class="space-y-2">
                    <label for="nombres" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Nombres *
                    </label>
                    <input type="text" id="nombres" name="nombres"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                        placeholder="Ingresa tus nombres">
                    <span class="error-message text-red-500 text-sm hidden"></span>
                </div>

                <!-- Apellidos -->
                <div class="space-y-2">
                    <label for="apellidos" class="block text-sm font-semibold text-gray-700">
                        <i class="fas fa-user mr-2 text-blue-500"></i>
                        Apellidos *
                    </label>
                    <input type="text" id="apellidos" name="apellidos"
                        class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                        placeholder="Ingresa tus apellidos">
                    <span class="error-message text-red-500 text-sm hidden"></span>
                </div>
                {{-- Sucursal 
            <div>
                <label for="sucursal" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-building mr-1"></i>
                    Sucursal
                </label>
                <select
                    name="sucursal"
                    id="sucursal"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                    <option value="">Seleccione una sucursal</option>
                    @foreach ($sucursales as $s)
                    <option value="{{ $s->SUCU_CODIGO }}">{{ $s->SUCU_DESCRIPCION }}</option>
                    @endforeach
                </select>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>
            --}}

                {{-- Nombre de usuario --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1"></i>
                        Nombre de Usuario
                    </label>
                    <input type="text" id="name" name="name" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                        placeholder="Ingrese el nombre de usuario">
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>

                {{-- Cargo 
            <div>
                <label for="cargo" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-briefcase mr-1"></i>
                    Cargo
                </label>
                <input type="text"
                    id="cargo"
                    name="cargo"
                    readonly
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors bg-gray-100"
                    placeholder="Cargo del personal seleccionado">
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>
            --}}

                {{-- Rol --}}
                <div>
                    <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-shield mr-1"></i>
                        Rol
                    </label>
                    <select name="rol" id="rol" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                        <option value="">Seleccione el rol</option>
                        <option value="ADMINISTRADOR">Administrador</option>
                        <option value="USUARIO OPERATIVO">Usuario Operativo</option>
                    </select>
                </div>

                {{-- Contraseña --}}
                <div>
                    <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-1"></i>
                        Contraseña
                    </label>
                    <div class="relative">
                        <input type="password" id="contrasena" name="contrasena" required minlength="8"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors pr-12"
                            placeholder="Mínimo 8 caracteres">
                        <button type="button" id="contrasena-eye"
                            class="fa fa-eye absolute right-3 top-1/2 transform -translate-y-1/2"></button>
                    </div>
                    <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
                </div>
            </div>

            {{-- Botones --}}
            <div class="flex space-x-3 pt-4 border-t border-gray-200">
                <button type="button" onclick="closeCreateModal()"
                    class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                    <i class="fas fa-times mr-2"></i>
                    Cancelar
                </button>
                <button type="submit"
                    class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Crear Usuario
                </button>
            </div>
        </form>
    </div>
@endsection
