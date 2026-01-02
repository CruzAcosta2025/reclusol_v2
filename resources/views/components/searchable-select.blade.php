@props([
    /**
     * Props (Blade)
     * - name: nombre del campo para submit (requerido)
     * - options: array de objetos/arrays con { value, label } (requerido)
     * - selected: value inicial seleccionado
     * - placeholder: placeholder del input
     * - noResultsText: texto cuando no hay coincidencias
     * - disabled: deshabilita el componente
     */
    'name',
    'options' => [],
    'selected' => null,
    'placeholder' => 'Selecciona…',
    'noResultsText' => 'No existe',
    'disabled' => false,
])

@php
    // Pasamos los atributos wire:model* al input hidden (Livewire).
    // El resto de atributos (ej. class) se aplican al contenedor.
    $wireAttributes = $attributes->whereStartsWith('wire:model');
    $wireKeys = array_keys($wireAttributes->getAttributes());
    $wrapperAttributes = $attributes->except($wireKeys);

    $id = $wrapperAttributes->get('id') ?: ($name . '_searchable');
    $listboxId = $id . '_listbox';
@endphp

<div
    {{ $wrapperAttributes->merge(['class' => 'relative w-full']) }}
    x-data="searchableSelect({
        id: @js($id),
        listboxId: @js($listboxId),
        options: @js($options),
        selected: @js($selected),
        noResultsText: @js($noResultsText),
        disabled: @js((bool) $disabled),
    })"
    x-init="init()"
    @keydown.escape.stop.prevent="close()"
    @mousedown.outside="close()"
>
    <!--
      HTML
      - Input visible: permite escribir/filtrar
      - Input hidden: emite el value seleccionado (submit y Livewire)
    -->

    <input
        type="hidden"
        name="{{ $name }}"
        x-model="selectedValue"
        {{ $wireAttributes }}
    />

    <div class="relative">
        <input
            type="text"
            :disabled="disabled"
            :aria-expanded="open.toString()"
            :aria-controls="listboxId"
            role="combobox"
            autocomplete="off"
            spellcheck="false"
            x-model="query"
            @focus="openList()"
            @click="openList()"
            @input="onInput()"
            @keydown.down.prevent="move(1)"
            @keydown.up.prevent="move(-1)"
            @keydown.enter.prevent="commitActive()"
            class="w-full rounded-lg border border-neutral bg-white px-3 py-2 text-sm text-neutral-darker shadow-sm outline-none focus:ring-2 focus:ring-M1/30"
            placeholder="{{ $placeholder }}"
        />

        <!-- Chevron -->
        <button
            type="button"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-neutral-dark"
            :disabled="disabled"
            @click="toggle()"
            tabindex="-1"
            aria-label="Abrir opciones"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <!--
      Dropdown
      - Muestra filtrado si query tiene texto
      - Si query está vacío, muestra todo
      - Si no hay resultados, muestra "No existe"
    -->
    <div
        x-show="open"
        x-transition.origin.top.left
        class="absolute z-30 mt-1 w-full overflow-hidden rounded-lg border border-neutral bg-white shadow-lg"
    >
        <ul
            :id="listboxId"
            role="listbox"
            class="max-h-60 overflow-auto py-1 text-sm"
            x-ref="list"
        >
            <template x-if="filteredOptions.length === 0">
                <li class="px-3 py-2 text-neutral-dark" x-text="noResultsText"></li>
            </template>

            <template x-for="(opt, idx) in filteredOptions" :key="opt._key">
                <li
                    role="option"
                    :aria-selected="(opt.value === selectedValue).toString()"
                    :data-index="idx"
                    @mousedown.prevent="select(opt)"
                    @mousemove="activeIndex = idx"
                    class="cursor-pointer px-3 py-2"
                    :class="{
                        'bg-M6 text-M2': idx === activeIndex,
                        'text-neutral-darker': idx !== activeIndex,
                    }"
                >
                    <span class="block truncate" x-text="opt.label"></span>
                </li>
            </template>
        </ul>
    </div>

    <!-- Alpine.js (comportamiento)
         Nota: se registra una sola vez con @once para evitar duplicados.
    -->
    @once
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('searchableSelect', (cfg) => ({
                    // Config
                    id: cfg.id,
                    listboxId: cfg.listboxId,
                    noResultsText: cfg.noResultsText ?? 'No existe',
                    disabled: !!cfg.disabled,

                    // State
                    open: false,
                    query: '',
                    selectedValue: '',
                    selectedLabel: '',
                    activeIndex: 0,

                    // Normalización defensiva: acepta {value,label}
                    options: (Array.isArray(cfg.options) ? cfg.options : []).map((o, i) => {
                        const value = (o && (o.value ?? o.id ?? o.key)) ?? '';
                        const label = (o && (o.label ?? o.name ?? o.text)) ?? '';
                        return {
                            value: String(value),
                            label: String(label),
                            _key: String(value) + '__' + String(i),
                        };
                    }),

                    init() {
                        // Inicializa selección (si viene selected)
                        if (cfg.selected !== null && cfg.selected !== undefined && String(cfg.selected) !== '') {
                            const initial = this.options.find(o => o.value === String(cfg.selected));
                            if (initial) {
                                this.selectedValue = initial.value;
                                this.selectedLabel = initial.label;
                                this.query = initial.label;
                            }
                        }

                        // Si el usuario borra el input, limpia selección
                        this.$watch('query', (val) => {
                            if ((val ?? '').trim() === '') {
                                this.selectedValue = '';
                                this.selectedLabel = '';
                                this.activeIndex = 0;
                            }
                        });
                    },

                    get filteredOptions() {
                        const q = (this.query ?? '').trim().toLowerCase();
                        if (!q) return this.options;
                        return this.options.filter(o => o.label.toLowerCase().includes(q));
                    },

                    openList() {
                        if (this.disabled) return;
                        this.open = true;
                        this.activeIndex = 0;
                        this.$nextTick(() => this.scrollActiveIntoView());
                    },

                    close() {
                        this.open = false;
                    },

                    toggle() {
                        if (this.disabled) return;
                        this.open ? this.close() : this.openList();
                    },

                    onInput() {
                        this.openList();
                    },

                    move(delta) {
                        if (!this.open) this.openList();
                        const max = this.filteredOptions.length - 1;
                        if (max < 0) return;
                        const next = this.activeIndex + delta;
                        this.activeIndex = Math.max(0, Math.min(max, next));
                        this.scrollActiveIntoView();
                    },

                    commitActive() {
                        if (!this.open) {
                            this.openList();
                            return;
                        }
                        const opt = this.filteredOptions[this.activeIndex];
                        if (!opt) return;
                        this.select(opt);
                    },

                    select(opt) {
                        this.selectedValue = opt.value;
                        this.selectedLabel = opt.label;
                        this.query = opt.label;
                        this.close();
                    },

                    scrollActiveIntoView() {
                        const list = this.$refs.list;
                        if (!list) return;
                        const el = list.querySelector(`[data-index="${this.activeIndex}"]`);
                        if (el && typeof el.scrollIntoView === 'function') {
                            el.scrollIntoView({ block: 'nearest' });
                        }
                    },
                }));
            });
        </script>
    @endonce
</div>
