@props([
    'tabs' => [], // Array de tabs: [['id' => 'section-id', 'label' => 'Label', 'shortLabel' => 'Short'], ...]
])

<div class="flex flex-col lg:flex-row gap-4 lg:gap-6 min-h-full">
    
    <!-- Sidebar con tabs -->
    <div class="w-full lg:w-56 border-b lg:border-b-0 lg:border-r border-neutral pb-4 lg:pb-0 lg:pr-6 flex flex-col">
        @isset($title)
            <h2 class="text-base text-M2 font-bold mb-4">{{ $title }}</h2>
        @endisset
        
        <nav class="space-y-1 flex flex-row lg:flex-col gap-1 flex-wrap lg:flex-nowrap flex-1">
            @foreach($tabs as $tab)
                <button 
                    onclick="switchTab('{{ $tab['id'] }}')"
                    class="tab-button flex-1 lg:flex-none w-full text-left px-3 py-2 lg:py-3 rounded font-medium transition text-xs lg:text-sm whitespace-nowrap lg:whitespace-normal text-M3 hover:bg-neutral-lightest"
                    data-tab-id="{{ $tab['id'] }}"
                >
                    <span class="lg:hidden">{{ $tab['shortLabel'] }}</span>
                    <span class="hidden lg:inline">{{ $tab['label'] }}</span>
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="flex-1 overflow-y-auto pr-2 lg:pr-4">
        {{ $slot }}
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Actualizar estilos de botones
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border', 'border-M1', 'bg-neutral-lightest');
            btn.classList.add('text-M3', 'hover:bg-neutral-lightest');
        });
        
        const activeBtn = document.querySelector(`.tab-button[data-tab-id="${tabId}"]`);
        if (activeBtn) {
            activeBtn.classList.remove('text-M3', 'hover:bg-neutral-lightest');
            activeBtn.classList.add('border', 'border-M1', 'bg-neutral-lightest');
        }
        
        // Ocultar todas las secciones
        document.querySelectorAll('.view-section').forEach(section => {
            section.style.display = 'none';
        });
        
        // Mostrar la sección seleccionada
        const targetSection = document.getElementById('section-' + tabId + '-section');
        if (targetSection) {
            targetSection.style.display = 'block';
        }
    }
    
    // Inicializar al cargar la página
    document.addEventListener('DOMContentLoaded', function() {
        // Ocultar todas excepto la primera
        document.querySelectorAll('.view-section').forEach((section, index) => {
            section.style.display = index === 0 ? 'block' : 'none';
        });
        
        // Marcar el primer botón como activo
        const firstBtn = document.querySelector('.tab-button');
        if (firstBtn) {
            firstBtn.classList.remove('text-M3', 'hover:bg-neutral-lightest');
            firstBtn.classList.add('border', 'border-M1', 'bg-neutral-lightest');
        }
    });
</script>
