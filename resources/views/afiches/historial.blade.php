
<x-app-layout>
    <div class="min-h-screen gradient-bg py-10">
        <a href="{{ route('afiches.index') }}" class="absolute top-6 left-6 text-white hover:text-yellow-300 transition-colors flex items-center space-x-2 group z-10">
            <i class="fas fa-arrow-left group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-medium">Volver a Afiches</span>
        </a>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- FILTROS --}}
            <form method="GET" class="mb-8 bg-white rounded-xl shadow p-4 flex flex-wrap gap-4 items-center">
                @php
                $estadosSeleccionados = request('estados', ['activo', 'pausado']);
                @endphp

                <div class="flex gap-3">
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="estados[]" value="activo" class="rounded border-gray-300" {{ in_array('activo', $estadosSeleccionados) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Activo</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="estados[]" value="pausado" class="rounded border-gray-300" {{ in_array('pausado', $estadosSeleccionados) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Pausado</span>
                    </label>
                    <label class="flex items-center space-x-1">
                        <input type="checkbox" name="estados[]" value="finalizado" class="rounded border-gray-300" {{ in_array('finalizado', $estadosSeleccionados) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Finalizado</span>
                    </label>
                </div>

                <select name="periodo" class="border border-gray-300 rounded px-2 py-1 text-sm">
                    <option value="todos">Todos</option>
                    <option value="semana" {{ request('periodo') == 'semana' ? 'selected' : '' }}>Última semana</option>
                    <option value="mes" {{ request('periodo') == 'mes' ? 'selected' : '' }}>Último mes</option>
                    <option value="3meses" {{ request('periodo') == '3meses' ? 'selected' : '' }}>Últimos 3 meses</option>
                    <option value="año" {{ request('periodo') == 'año' ? 'selected' : '' }}>Último año</option>
                </select>

                <input type="text" name="search" class="border border-gray-300 rounded px-2 py-1 text-sm w-48" value="{{ request('search') }}" placeholder="Buscar...">

                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-sm">
                    <i class="fas fa-filter mr-1"></i> Filtrar
                </button>
            </form>

            {{-- ESTADÍSTICAS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['activos'] ?? 0 }}</div>
                    <div class="text-gray-600 text-sm">Activos</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold text-yellow-500">{{ $stats['pausados'] ?? 0 }}</div>
                    <div class="text-gray-600 text-sm">Pausados</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow text-center">
                    <div class="text-2xl font-bold text-red-500">{{ $stats['finalizados'] ?? 0 }}</div>
                    <div class="text-gray-600 text-sm">Finalizados</div>
                </div>
            </div>

            {{-- OPCIONES DE ORDEN Y VISTA --}}
            <div class="flex flex-col md:flex-row md:justify-between items-start md:items-center gap-3 mb-4">
                <div class="space-x-2">
                    <span class="text-gray-700 font-medium">Vista:</span>
                    <button id="grid-btn" onclick="toggleView('grid')" class="p-2 rounded text-purple-600 bg-purple-100 hover:bg-purple-200 text-sm">
                        Grid
                    </button>
                    <button id="list-btn" onclick="toggleView('list')" class="p-2 rounded text-gray-600 hover:bg-gray-100 text-sm">
                        Lista
                    </button>
                </div>
                <div>
                    <label class="text-gray-700 text-sm">Ordenar por:</label>
                    <select onchange="updateOrder(this.value)" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="reciente" {{ request('order','reciente')=='reciente' ? 'selected':'' }}>Más reciente</option>
                        <option value="antiguo" {{ request('order')=='antiguo' ? 'selected':'' }}>Más antiguo</option>
                        <option value="alfabetico" {{ request('order')=='alfabetico' ? 'selected':'' }}>Alfabético</option>
                    </select>
                </div>
            </div>

            {{-- GRID DE AFICHES --}}
            <div id="afiches-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {{-- @forelse($afiches as $afiche) --}}
                <div class="card-hover {{ $afiche->color_gradient ?? 'gradient-blue' }} rounded-xl overflow-hidden shadow-lg h-96 relative">
                    <div class="p-4">
                        <div class="flex justify-between">
                            {{-- <span class="font-bold text-white uppercase">{{ strtoupper($afiche->estado) }}</span> --}}
                            {{-- <span class="text-sm text-white">{{ $afiche->created_at->format('d/m/Y') }}</span> --}}
                        </div>
                        {{-- <h2 class="mt-2 text-lg font-bold text-white">{{ $afiche->titulo }}</h2> --}}
                        {{-- <p class="text-sm text-white mb-2">{{ $afiche->empresa }}</p> --}}
                        {{-- @if($afiche->icono_svg) --}}
                        {{-- <div class="mt-2">{!! $afiche->icono_svg !!}</div> --}}
                        {{-- @endif --}}
                        <div class="text-white mt-3 space-y-1">
                            {{-- @if($afiche->descripcion_array) 
                            @foreach(array_slice($afiche->descripcion_array, 0, 4) as $desc)
                            <div>- {{ $desc }}
                        </div>
                        @endforeach
                        @endif --}}
                    </div>
                    {{-- <span class="text-xs text-gray-200 absolute bottom-4 right-4">#{{ $afiche->codigo }}</span> --}}
                </div>
                <div class="absolute bottom-2 left-2 flex space-x-1">
                    {{-- <a href="{{ route('afiches.show', $afiche->id) }}" class="p-2 text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg" title="Ver">
                    <i class="fas fa-eye"></i>
                    </a> --}}
                    {{-- <a href="{{ route('afiches.download', $afiche->id) }}" class="p-2 text-green-600 hover:text-green-800 hover:bg-green-50 rounded-lg" title="Descargar">
                    <i class="fas fa-download"></i>
                    </a> --}}
                    {{-- <a href="{{ route('afiches.edit', $afiche->id) }}" class="p-2 text-purple-600 hover:text-purple-800 hover:bg-purple-50 rounded-lg" title="Editar">
                    <i class="fas fa-edit"></i>
                    </a> --}}
                    {{-- <button onclick="deleteAfiche({{ $afiche->id }})" class="p-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg" title="Eliminar">
                    <i class="fas fa-trash"></i>
                    </button> --}}
                </div>
            </div>
            {{-- @empty --}}
            <div class="col-span-3 text-center text-gray-600 py-12">
                <div class="text-3xl mb-4">😕</div>
                <div class="font-semibold">No se encontraron afiches con los filtros aplicados.</div>
            </div>
            {{-- @endforelse --}}
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-8">
            {{-- {{ $afiches->links() }} --}}
        </div>
    </div>

    {{-- MODAL DE ELIMINACIÓN --}}
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl max-w-md mx-auto">
            <h2 class="font-bold text-xl text-red-600 mb-3">¿Eliminar Afiche?</h2>
            <p class="mb-4">Esta acción no se puede deshacer. El afiche será eliminado permanentemente.</p>
            <div class="flex justify-end gap-2">
                <button onclick="closeDeleteModal()" class="px-4 py-2 rounded bg-gray-300 hover:bg-gray-400">Cancelar</button>
                <button onclick="confirmDelete()" class="px-4 py-2 rounded bg-red-600 text-white hover:bg-red-700">Eliminar</button>
            </div>
        </div>
    </div>
    </div>

    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>

    <script>
        let currentView = 'grid';
        let deleteAficheId = null;

        function toggleView(view) {
            const container = document.getElementById('afiches-container');
            const gridBtn = document.getElementById('grid-btn');
            const listBtn = document.getElementById('list-btn');
            if (view === 'grid') {
                container.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6';
                gridBtn.className = 'p-2 rounded text-purple-600 bg-purple-100';
                listBtn.className = 'p-2 rounded text-gray-600 hover:bg-gray-100';
            } else {
                container.className = 'space-y-4';
                gridBtn.className = 'p-2 rounded text-gray-600 hover:bg-gray-100';
                listBtn.className = 'p-2 rounded text-purple-600 bg-purple-100';
            }
        }

        function updateOrder(value) {
            const url = new URL(window.location);
            url.searchParams.set('order', value);
            window.location = url;
        }

        function deleteAfiche(id) {
            deleteAficheId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-modal').classList.add('flex');
        }

        function closeDeleteModal() {
            deleteAficheId = null;
            document.getElementById('delete-modal').classList.add('hidden');
            document.getElementById('delete-modal').classList.remove('flex');
        }

        function confirmDelete() {
            if (deleteAficheId) {
                fetch(`/afiches/${deleteAficheId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) location.reload();
                        else alert('Error al eliminar');
                    })
                    .catch(() => alert('Error al eliminar'));
            }
            closeDeleteModal();
        }
    </script>
</x-app-layout>