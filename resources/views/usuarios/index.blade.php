@extends('layouts.app')

@section('content')
    <div class="min-h-screen gradient-bg py-8">
        {{-- Botón volver --}}
        <a href="{{ route('dashboard') }}"
            class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver al Dashboard</span>
        </a>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            {{-- Encabezado --}}
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-800">Gestión de Usuarios</h1>
                        <p class="text-gray-600 mt-1">Administra los usuarios del sistema y sus permisos</p>
                    </div>
                    <button onclick="openCreateModal()"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-200 flex items-center space-x-2">
                        <i class="fas fa-plus"></i>
                        <span>Nuevo Usuario</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <form id="filter-form" method="GET"
                class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-2xl shadow-lg">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Buscar Usuario</label>
                    <input type="text" name="buscar" value="{{ request('buscar') }}" placeholder="Nombre..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 transition-colors">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                    <select name="estado"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-blue-500 focus:ring-0 bg-white/80 transition-colors">
                        <option value="">Todos</option>
                        <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activos</option>
                        <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivos</option>
                    </select>
                </div>


                <div class="flex items-end">
                    <div class="flex space-x-2 w-full">
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl transition-colors flex items-center justify-center space-x-2">
                            <i class="fas fa-filter"></i>
                            <span>Filtrar</span>
                        </button>
                        <button type="button" onclick="limpiarFiltros()"
                            class="px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Estadísticas --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                {{-- Total Usuarios --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-blue-500 text-sm font-medium">Total</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $totalUsers }}</h3>
                    <p class="text-gray-600 text-sm">Usuarios registrados</p>
                </div>

                {{-- Usuarios Activos --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-check text-green-600 text-xl"></i>
                        </div>
                        <span class="text-green-500 text-sm font-medium">Activos</span>
                    </div>
                    {{-- <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $activeUsers }}</h3> --}}
                    <p class="text-gray-600 text-sm">Usuarios activos</p>
                </div>

                {{-- Usuarios Inactivos --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-times text-red-600 text-xl"></i>
                        </div>
                        <span class="text-red-500 text-sm font-medium">Inactivos</span>
                    </div>
                    {{-- <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $inactiveUsers }}</h3> --}}
                    <p class="text-gray-600 text-sm">Usuarios inactivos</p>
                </div>

                {{-- Nuevos este mes --}}
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-purple-500 text-sm font-medium">Nuevos</span>
                    </div>
                    <h3 class="text-3xl font-bold text-gray-800 mb-1">{{ $newUsersThisMonth }}</h3>
                    <p class="text-gray-600 text-sm">Este mes</p>
                </div>
            </div>
        </div>

        {{-- Tabla de usuarios --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                {{-- Encabezado de tabla --}}
                <div
                    class="bg-gradient-to-r from-emerald-500 to-emerald-600 text-white px-6 py-4 flex justify-between items-center">
                    <h2 class="flex items-center text-lg font-semibold">
                        <i class="fas fa-list mr-2"></i>
                        Lista de Usuarios
                    </h2>
                    <span class="text-sm opacity-80">
                        {{ $users->total() }} usuarios encontrados
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100">
                            <tr class="text-left">
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Usuario</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Nombres y Apellidos</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Rol</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Estado</th>
                                <th class="px-6 py-4 text-xs font-bold text-gray-600 uppercase tracking-wider text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr class="hover:bg-blue-50 transition-colors">
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $user->usuario ?? 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4  text-center">
                                        <div class="flex flex-col">
                                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500">Registrado:
                                                {{ $user->created_at->format('d/m/Y') }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            {{ $user->rol ?? 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4  text-center">
                                        @if ($user->habilitado)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>
                                                Activo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button onclick="editUser({{ $user->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-green-50 hover:bg-green-100 text-green-600 transition"
                                                title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button onclick="toggleUserStatus({{ $user->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $user->habilitado ? 'bg-green-50 hover:bg-green-100 text-green-600' : 'bg-orange-50 hover:bg-orange-100 text-orange-600' }} transition"
                                                title="{{ $user->habilitado ? 'Desactivar' : 'Activar' }}">
                                                <i
                                                    class="fas fa-{{ $user->habilitado ? 'toggle-on' : 'toggle-off' }}"></i>
                                            </button>

                                            <button onclick="deleteUser({{ $user->id }})"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-600 transition"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                            <p class="text-gray-500 text-lg">No se encontraron usuarios</p>
                                            <p class="text-gray-400 text-sm">Ajusta los filtros o crea un nuevo usuario</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($users->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Modal de Creación --}}
        <div id="create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" id="create-modal-content">
                {{-- Contenido del modal se carga aquí --}}
            </div>
        </div>

        {{-- Modal de Edición --}}
        <div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" id="edit-modal-content">
                {{-- Contenido del modal se carga aquí --}}
            </div>
        </div>

        {{-- Modal de Visualización --}}
        <div id="view-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4" id="view-modal-content">
                {{-- Contenido del modal se carga aquí --}}
            </div>
        </div>

        {{-- Modal de Eliminación --}}
        <div id="delete-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-4 p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">¿Eliminar Usuario?</h3>
                    <p class="text-sm text-gray-600 mb-6">Esta acción no se puede deshacer. El usuario será eliminado
                        permanentemente.</p>
                    <div class="flex space-x-3">
                        <button onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                            Cancelar
                        </button>
                        <button onclick="confirmDelete()"
                            class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                            Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.ROUTE_DNI_SIMPLE = @json(route('usuarios.dni.simple', ['dni' => 'DNI_PLACEHOLDER']));

        let deleteUserId = null;

        // ------ Filtros y tabla ------
        function limpiarFiltros() {
            document.getElementById('filter-form').reset();
            window.location.href = window.location.pathname;
        }

        // ------ Modales ------
        function openCreateModal() {
            fetch('/usuarios/create')
                .then(response => response.text())
                .then(html => {
                    document.getElementById('create-modal-content').innerHTML = html;
                    document.getElementById('create-modal').classList.remove('hidden');
                    inicializarEventosCreateModal(); // Importante!
                });
        }

        function closeCreateModal() {
            document.getElementById('create-modal').classList.add('hidden');
        }

        function toggleUserStatus(userId) {
            fetch(`/usuarios/${userId}/habilitar`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'No se pudo cambiar el estado');
                    }
                })
                .catch(() => {
                    alert('Ocurrió un error');
                });
        }

        // ... Aquí las demás funciones de modales, editar, eliminar, etc. (igual que antes) ...

        // ------ Cerrar modales al hacer clic fuera ------
        document.addEventListener('click', function(event) {
            const modals = ['create-modal', 'edit-modal', 'view-modal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && event.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });

        // ------ Función para el modal de crear usuario ------
        function inicializarEventosCreateModal() {
            // PASSWORD
            const passEyeBtn = document.getElementById('contrasena-eye');
            const passField = document.getElementById('contrasena');
            if (passEyeBtn && passField) {
                passEyeBtn.onclick = function() {
                    if (passField.type === 'password') {
                        passField.type = 'text';
                        passEyeBtn.classList.remove('fa-eye');
                        passEyeBtn.classList.add('fa-eye-slash');
                    } else {
                        passField.type = 'password';
                        passEyeBtn.classList.remove('fa-eye-slash');
                        passEyeBtn.classList.add('fa-eye');
                    }
                };
            }

            // SUBMIT FORMULARIO
            const form = document.getElementById('create-user-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);

                    // Limpiar errores anteriores
                    form.querySelectorAll('.error-message').forEach(el => {
                        el.classList.add('hidden');
                        el.textContent = '';
                    });

                    fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest', // ← AGREGA ESTA LÍNEA

                            },
                            body: formData
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                window.location.reload();
                            } else {
                                alert(data.message || 'Error al crear el usuario');
                            }
                        })
                        .catch(error => {
                            if (error.errors) {
                                Object.keys(error.errors).forEach(field => {
                                    const errorDiv = form.querySelector(`[name="${field}"]`)?.parentNode
                                        ?.querySelector('.error-message');
                                    if (errorDiv) {
                                        errorDiv.textContent = error.errors[field][0];
                                        errorDiv.classList.remove('hidden');
                                    }
                                });
                            } else {
                                alert('Error al crear el usuario');
                            }
                        });
                });
            }

            // --- AUTOCOMPLETADO POR DNI (RENIEC) ---
            const modal = document.getElementById('create-modal-content');
            const dni = modal.querySelector('#dni');
            const nombres = modal.querySelector('#nombres');
            const apellidos = modal.querySelector('#apellidos');

            if (dni && nombres && apellidos) {
                const urlFor = d => window.ROUTE_DNI_SIMPLE.replace('DNI_PLACEHOLDER', d);
                let t = null;

                async function lookup(d) {
                    try {
                        const r = await fetch(urlFor(d), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const j = await r.json();
                        if (j.ok) {
                            nombres.value = j.data.nombres || '';
                            apellidos.value = j.data.apellidos || '';
                        } else {
                            nombres.value = '';
                            apellidos.value = '';
                        }
                    } catch (e) {
                        console.error(e);
                    }
                }

                dni.addEventListener('input', (e) => {
                    const v = e.target.value.replace(/\D/g, '').slice(0, 8);
                    e.target.value = v;
                    clearTimeout(t);
                    if (v.length === 8) t = setTimeout(() => lookup(v), 300);
                });

                dni.addEventListener('blur', (e) => {
                    const v = e.target.value.replace(/\D/g, '');
                    if (v.length === 8) lookup(v);
                });

                // Si ya llega con 8 dígitos (por autofill), dispara:
                if (dni.value && dni.value.length === 8) lookup(dni.value);
            }

        }
    </script>
@endsection
