<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center">
            <i class="fas fa-user text-blue-600 mr-2"></i>
            Detalles del Usuario
        </h3>
        <button onclick="closeViewModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <div class="space-y-6">
        {{-- Avatar y información básica --}}
        <div class="text-center pb-6 border-b border-gray-200">
            <div class="w-20 h-20 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4">
                {{ substr($user->name, 0, 2) }}
            </div>
            <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
            <p class="text-gray-600">{{ $user->email }}</p>

            {{-- Estado --}}
            <div class="mt-3">
                @if($user->email_verified_at)
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    Usuario Activo
                </span>
                @else
                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                    <i class="fas fa-times-circle mr-2"></i>
                    Usuario Inactivo
                </span>
                @endif
            </div>
        </div>

        {{-- Información del cargo --}}
        <div class="bg-indigo-50 rounded-xl p-4">
            <h4 class="text-sm font-medium text-indigo-800 mb-3 flex items-center">
                <i class="fas fa-briefcase mr-2"></i>
                Información del Cargo
            </h4>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <span class="text-xs text-indigo-600 font-medium">Cargo Asignado</span>
                    <p class="text-sm text-indigo-800 font-semibold">
                        {{ $user->cargoInfo?->DESC_TIPO_CARG ?? 'Sin cargo asignado' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Información de fechas --}}
        <div class="bg-gray-50 rounded-xl p-4">
            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center">
                <i class="fas fa-calendar-alt mr-2"></i>
                Información de Fechas
            </h4>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <span class="text-xs text-gray-500 font-medium">Fecha de Registro</span>
                    <p class="text-sm text-gray-800">{{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</p>
                </div>

                <div>
                    <span class="text-xs text-gray-500 font-medium">Última Actualización</span>
                    <p class="text-sm text-gray-800">{{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-500">{{ $user->updated_at->diffForHumans() }}</p>
                </div>

                @if($user->email_verified_at)
                <div>
                    <span class="text-xs text-gray-500 font-medium">Email Verificado</span>
                    <p class="text-sm text-gray-800">{{ $user->email_verified_at->format('d/m/Y H:i') }}</p>
                    <p class="text-xs text-gray-500">{{ $user->email_verified_at->diffForHumans() }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Estadísticas de actividad --}}
        <div class="bg-blue-50 rounded-xl p-4">
            <h4 class="text-sm font-medium text-blue-800 mb-3 flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                Estadísticas de Actividad
            </h4>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ \Carbon\Carbon::parse($user->created_at)->diffInDays() }}
                    </div>
                    <div class="text-xs text-blue-600">Días en el sistema</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">
                        {{ $user->email_verified_at ? 'Verificado' : 'Pendiente' }}
                    </div>
                    <div class="text-xs text-blue-600">Estado de verificación</div>
                </div>
            </div>
        </div>

        {{-- Botones de acción --}}
        <div class="flex space-x-3 pt-4 border-t border-gray-200">
            <button onclick="closeViewModal()"
                class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-xl transition-colors">
                <i class="fas fa-times mr-2"></i>
                Cerrar
            </button>
            <button onclick="closeViewModal(); editUser({{ $user->id }})"
                class="flex-1 px-4 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-xl transition-all">
                <i class="fas fa-edit mr-2"></i>
                Editar Usuario
            </button>
        </div>
    </div>
</div>