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
            {{-- Sucursal --}}
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
                    @foreach($sucursales as $s)
                    <option value="{{ $s->SUCU_CODIGO }}">{{ $s->SUCU_DESCRIPCION }}</option>
                    @endforeach
                </select>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>

            {{-- Personal elegible --}}
            <div>
                <label for="personal" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1"></i>
                    Personal elegible
                </label>
                <select
                    name="personal_id"
                    id="personal"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
                    <option value="">Selecciona una persona</option>
                </select>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>

            {{-- Nombre de usuario --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-1"></i>
                    Nombre de Usuario
                </label>
                <input type="text"
                    id="name"
                    name="name"
                    required
                    class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors"
                    placeholder="Ingrese el nombre de usuario">
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>

            {{-- Cargo --}}
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


            {{-- Rol --}}
            <div>
                <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-shield mr-1"></i>
                    Rol
                </label>
                <select name="rol" id="rol" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors">
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
                    <input type="password"
                        id="contrasena"
                        name="contrasena"
                        required
                        minlength="8"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors pr-12"
                        placeholder="Mínimo 8 caracteres">
                    <input type="password" id="contrasena" name="contrasena">
                    <button type="button" id="contrasena-eye" class="fa fa-eye"></button>
                </div>
                <div class="error-message text-red-500 text-sm mt-1 hidden"></div>
            </div>
        </div>

        {{-- Botones --}}
        <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button type="button"
                onclick="closeCreateModal()"
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