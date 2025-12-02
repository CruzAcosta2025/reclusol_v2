@props([
    'name' => 'sidebar',
    'show' => false,
    'logo' => 'RECLUSOL',
    'subtitle' => 'Sistema de Gestión',
    'items' => [
        ['label' => 'Dashboard', 'href' => '#', 'icon' => 'fa-home'],
    ],
    'logout' => [
        'label' => 'Logout',
        'href' => '#',
        'icon' => 'fa-sign-out-alt',
    ],
])

<div x-data="{
    show: @js($show),
    isMobile: window.innerWidth < 768,
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
            'w-64': !isMobile && (typeof sidebarOpen === 'undefined' ? true : sidebarOpen),
            'w-0 overflow-hidden': !isMobile && !(typeof sidebarOpen === 'undefined' ? true : sidebarOpen)
        }"
        class="bg-M2 text-M6 transition-all duration-300 overflow-hidden md:relative md:translate-x-0">
        <div class="p-5 h-screen w-full flex flex-col">
            <!-- Logo Section -->
            <div class="mb-5 pb-6 border-b border-M3">
                <h1 class="text-xl font-bold text-accent">{{ $logo }}</h1>
                <p class="text-xs text-neutral-lighter mt-1">{{ $subtitle }}</p>
            </div>

            <!-- Navigation -->
            <nav class="space-y-1 flex-1">
                @foreach($items as $item)
                    <a href="{{ $item['href'] }}"
                        class="block px-3 py-3 rounded-lg transition-all duration-200 text-sm text-M6 hover:bg-M1"
                        @click="if(isMobile){ show = false; window.dispatchEvent(new CustomEvent('close-parent-sidebar')) }">
                        <span class="flex items-center gap-3">
                            <i class="fas {{ $item['icon'] ?? 'fa-link' }} w-5"></i>
                            <span>{{ $item['label'] }}</span>
                        </span>
                    </a>
                @endforeach
            </nav>
        </div>
    </aside>
</div>
