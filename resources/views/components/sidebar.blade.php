@props([
    'name' => 'sidebar',
    'show' => false,
    'logo' => 'RECLUSOL',
    'subtitle' => 'Sistema de Reclutamiento',
    'items' => [
        ['label' => 'Dashboard', 'href' => '#', 'icon' => 'fa-home'],
    ],
    'logout' => [
        'label' => 'Logout',
        'href' => '#',
        'icon' => 'fa-sign-out-alt',
    ],
])

@php
// Helper para determinar si un item tiene subitems
function hasSubitems($item) {
    return !empty($item['subitems']) && is_array($item['subitems']);
}

if (!function_exists('sidebar_is_active')) {
    function sidebar_is_active($href) {
        if (!$href || $href === '#') return false;

        $current = rtrim(url()->current(), '/');
        $target = rtrim($href, '/');

        return $current === $target;
    }
}
@endphp

<div x-data="{
    show: @js($show),
    isMobile: window.innerWidth < 768,
    expandedItems: {},
    toggleExpand(key) {
        this.expandedItems[key] = !this.expandedItems[key];
    },
    focusables() {
        let selector = 'a, button, input:not([type=\'hidden\']), textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'
        return [...$el.querySelectorAll(selector)].filter(el => !el.hasAttribute('disabled'))
    },
}" x-init="$watch('show', value => {
    if (value) document.body.classList.add('overflow-y-hidden');
    else document.body.classList.remove('overflow-y-hidden');
})"
    x-on:open-sidebar.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-sidebar.window="$event.detail == '{{ $name }}' ? show = false : null"
    @resize.window="isMobile = window.innerWidth < 768">
    <!-- Overlay: only on mobile when shown -->
    <div x-show="(show || (typeof sidebarOpen !== 'undefined' ? sidebarOpen : false)) && isMobile"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-60 z-40 md:hidden h-full"
        @click.stop="show = false; window.dispatchEvent(new CustomEvent('close-parent-sidebar'))"></div>

    <!-- Sidebar panel -->
    <aside x-show="show || (typeof sidebarOpen !== 'undefined' ? sidebarOpen : false)"
        x-transition:enter="transform transition ease-out duration-300" x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0" x-transition:leave="transform transition ease-in duration-200"
        x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
        :class="{
            'fixed left-0 top-0 h-screen z-50 w-64 shadow-lg': isMobile,
            'w-64': !isMobile
        }"
        class="bg-M2 text-M6 transition-transform duration-300 overflow-hidden md:relative md:translate-x-0 border-r border-M3">
        <div class="p-5 h-screen w-full flex flex-col">
            <!-- Logo Section -->
            <div class="mb-5 pb-6 border-b border-M3">
                <h1 class="text-xl font-semibold text-neutral-lighter">{{ $logo }}</h1>
                <p class="text-xs text-neutral-lighter mt-1">{{ $subtitle }}</p>
            </div>

            <!-- Navigation -->
            <nav class="space-y-1 flex-1">
                @foreach($items as $index => $item)
                    @if(hasSubitems($item))
                        {{-- Item con subitems --}}
                        <div x-data="{ expanded: false }" class="space-y-1">
                            @php
                                $isGroupActive = false;
                                foreach (($item['subitems'] ?? []) as $sub) {
                                    if (sidebar_is_active($sub['href'] ?? null)) {
                                        $isGroupActive = true;
                                        break;
                                    }
                                }
                            @endphp
                            <button @click="expanded = !expanded; $dispatch('sidebar-toggle', { key: {{ $index }} })"
                                @class([
                                    'w-full flex items-center justify-between px-3 py-3 rounded-lg transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-M3 focus:ring-opacity-40',
                                    'bg-M1 text-neutral-light' => $isGroupActive,
                                    'text-neutral-lighter hover:bg-M1' => !$isGroupActive,
                                ])>
                                <span class="flex items-center gap-3">
                                    <i class="fas {{ $item['icon'] ?? 'fa-folder' }} w-5"></i>
                                    <span>{{ $item['label'] }}</span>
                                </span>
                                <i class="fas fa-chevron-down transition-transform duration-200" :class="{ 'rotate-180': expanded }"></i>
                            </button>

                            {{-- Subitems (colapsables) --}}
                            <div x-show="expanded" x-transition:enter="ease-out duration-200"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2" class="space-y-1 pl-4 border-l border-M3">
                                @foreach($item['subitems'] as $subitem)
                                    @php
                                        $isSubActive = sidebar_is_active($subitem['href'] ?? null);
                                    @endphp
                                    <a href="{{ $subitem['href'] }}"
                                        @class([
                                            'block px-3 py-2 rounded-lg transition-all duration-200 text-xs',
                                            'bg-M1 text-neutral-light' => $isSubActive,
                                            'text-neutral-lighter hover:bg-M1' => !$isSubActive,
                                        ])
                                        @click="if(isMobile){ show = false; window.dispatchEvent(new CustomEvent('close-parent-sidebar')) }">
                                        <span class="flex items-center gap-2">
                                            <i class="fas {{ $subitem['icon'] ?? 'fa-link' }} w-4"></i>
                                            <span>{{ $subitem['label'] }}</span>
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Item simple (sin subitems) --}}
                        @php
                            $isActive = sidebar_is_active($item['href'] ?? null);
                        @endphp
                        <a href="{{ $item['href'] }}"
                            @class([
                                'block px-3 py-3 rounded-lg transition-all duration-200 text-sm focus:outline-none focus:ring-2 focus:ring-M3 focus:ring-opacity-40',
                                'bg-M1 text-neutral-light' => $isActive,
                                'text-neutral-lighter hover:bg-M1' => !$isActive,
                            ])
                            @click="if(isMobile){ show = false; window.dispatchEvent(new CustomEvent('close-parent-sidebar')) }">
                            <span class="flex items-center gap-3">
                                <i class="fas {{ $item['icon'] ?? 'fa-link' }} w-5"></i>
                                <span>{{ $item['label'] }}</span>
                            </span>
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </aside>
</div>
