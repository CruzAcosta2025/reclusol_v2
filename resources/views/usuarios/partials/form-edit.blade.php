@php
    // Separar nombre completo en nombres y apellidos (heurística simple)
    $full = trim($user->name ?? '');
    $parts = preg_split('/\s+/', $full);
    $nombresPref = $full;
    $apellidosPref = '';
    if (count($parts) >= 3) {
        $apellidosPref = implode(' ', array_slice($parts, -2));
        $nombresPref = implode(' ', array_slice($parts, 0, -2));
    } elseif (count($parts) == 2) {
        $nombresPref = $parts[0];
        $apellidosPref = $parts[1];
    }
    $rolActual = $user->rol ?? ($user->roles->first()->name ?? '');
@endphp

<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-user-edit text-blue-600 mr-2"></i>
            Editar Usuario
        </h3>
        <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <form id="edit-user-form" action="{{ route('usuarios.update', $user) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-4">
            <div class="space-y-2">
                <label for="dni" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-id-card mr-2 text-blue-500"></i>
                    DNI
                </label>
                <input type="text" id="dni" name="dni" maxlength="8" pattern="[0-9]{8}"
                    value="{{ old('dni', $user->dni ? str_pad($user->dni, 8, '0', STR_PAD_LEFT) : '') }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                    placeholder="12345678">
                <span class="error-message text-red-500 text-sm hidden"></span>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            {{-- Nombres --}}
            <div class="space-y-2">
                <label for="nombres" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Nombres *
                </label>
                <input type="text" id="nombres" name="nombres" value="{{ old('nombres', $nombresPref) }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                    placeholder="Ingresa nombres">
                <span class="error-message text-red-500 text-sm hidden"></span>
            </div>

            {{-- Apellidos --}}
            <div class="space-y-2">
                <label for="apellidos" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-user mr-2 text-blue-500"></i>
                    Apellidos *
                </label>
                <input type="text" id="apellidos" name="apellidos" value="{{ old('apellidos', $apellidosPref) }}"
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                    placeholder="Ingresa apellidos">
                <span class="error-message text-red-500 text-sm hidden"></span>
            </div>

            {{-- Nombre de usuario (username) --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1"></i>
                    Nombre de Usuario *
                </label>
                <input type="text" id="name" name="name" required value="{{ old('name', $user->usuario) }}"
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                    placeholder="Usuario de inicio de sesión">
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>

            {{-- Rol --}}
            <div>
                <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-shield mr-1"></i>
                    Rol *
                </label>
                <select name="rol" id="rol" required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                    <option value="">Seleccione el rol</option>
                    <option value="ADMINISTRADOR" {{ old('rol', $rolActual) === 'ADMINISTRADOR' ? 'selected' : '' }}>
                        Administrador</option>
                    <option value="USUARIO OPERATIVO"
                        {{ old('rol', $rolActual) === 'USUARIO OPERATIVO' ? 'selected' : '' }}>Usuario Operativo
                    </option>
                </select>
            </div>

            {{-- Contraseña (opcional) --}}
            <div class="col-span-2">
                <label for="contrasena" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-lock mr-1"></i>
                    Contraseña (opcional)
                </label>
                <div class="relative">
                    <input type="password" id="contrasena" name="contrasena" minlength="8"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors pr-12"
                        placeholder="Dejar en blanco para mantener">
                    <button type="button" id="contrasena-eye"
                        class="fa fa-eye absolute right-3 top-1/2 transform -translate-y-1/2"></button>
                </div>
                <div class="text-xs text-gray-500 mt-1">Si no ingresas nada, se conserva la contraseña actual.</div>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button type="button" onclick="closeEditModal()"
                class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                <i class="fas fa-times mr-2"></i> Cancelar
            </button>
            <button type="submit"
                class="flex-1 px-4 py-3 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-xl transition-all">
                <i class="fas fa-save mr-2"></i> Guardar
            </button>
        </div>
    </form>
</div>
