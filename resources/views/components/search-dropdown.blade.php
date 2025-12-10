@props([
    'name' => '',
    'id' => '',
    'label' => '',
    'placeholder' => 'Busca...',
    'options' => [],
    'value' => null,
    'icon' => '',
])

<div class="relative">
    @if($label)
        <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
    @endif
    
    <div 
        x-data="searchDropdown({
            name: '{{ $name }}',
            currentValue: '{{ $value }}',
            options: @js($options)
        })"
        class="relative"
    >
        <!-- Hidden input for form submission -->
        <input type="hidden" :name="name" :value="selectedValue">
        
        <!-- Trigger button -->
        <button 
            type="button"
            @click="open = !open"
            @keydown.escape="open = false"
            class="w-full px-4 py-2 border-1 border-gray-200 rounded-xl bg-white text-left flex items-center justify-between hover:border-gray-300 transition focus:outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-60"
        >
            <span class="flex items-center gap-2 truncate">
                @if($icon)
                    <i class="fas {{ $icon }} text-gray-500"></i>
                @endif
                <span x-text="displayText" class="truncate"></span>
            </span>
            <i class="fas fa-chevron-down text-gray-400 transition" :class="open && 'rotate-180'"></i>
        </button>
        
        <!-- Dropdown panel -->
        <div 
            x-show="open"
            @click.away="open = false"
            x-transition
            class="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-xl shadow-lg overflow-hidden"
        >
            <!-- Search input -->
            <input
                x-model="search"
                type="text"
                placeholder="{{ $placeholder }}"
                @keydown.arrow-down.prevent="highlightNext()"
                @keydown.arrow-up.prevent="highlightPrev()"
                @keydown.enter.prevent="selectHighlighted()"
                @keydown.escape.prevent="open = false"
                class="w-full px-4 py-2 border-b border-gray-200 outline-none focus:ring-2 focus:ring-accent focus:ring-opacity-30"
                autofocus
            >
            
            <!-- Options list -->
            <ul class="max-h-64 overflow-y-auto">
                <template x-for="(label, key, index) in filtered" :key="key">
                    <li 
                        @click="selectOption(key)"
                        @mouseenter="highlighted = index"
                        :class="highlighted === index ? 'bg-accent/10 text-accent' : 'hover:bg-gray-50'"
                        class="px-4 py-2 cursor-pointer transition flex items-center justify-between"
                    >
                        <span x-text="label" class="truncate"></span>
                        <i x-show="selectedValue === key" class="fas fa-check text-accent"></i>
                    </li>
                </template>
                
                <li x-show="Object.keys(filtered).length === 0" class="px-4 py-3 text-center text-gray-500 text-sm">
                    Sin resultados
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('searchDropdown', (config) => ({
            open: false,
            search: '',
            highlighted: 0,
            options: config.options || {},
            selectedValue: config.currentValue || '',
            
            get displayText() {
                return this.options[this.selectedValue] || (this.selectedValue ? this.selectedValue : '');
            },
            
            get filtered() {
                const search = this.search.toLowerCase();
                return Object.entries(this.options)
                    .reduce((acc, [key, label]) => {
                        if (String(label).toLowerCase().includes(search)) {
                            acc[key] = label;
                        }
                        return acc;
                    }, {});
            },
            
            selectOption(key) {
                this.selectedValue = key;
                this.search = '';
                this.open = false;
                this.highlighted = 0;
            },
            
            highlightNext() {
                const keys = Object.keys(this.filtered);
                if (keys.length > 0 && this.highlighted < keys.length - 1) {
                    this.highlighted++;
                }
            },
            
            highlightPrev() {
                if (this.highlighted > 0) {
                    this.highlighted--;
                }
            },
            
            selectHighlighted() {
                const keys = Object.keys(this.filtered);
                if (keys.length > 0) {
                    this.selectOption(keys[this.highlighted]);
                }
            }
        }));
    });
</script>
