@props([
    /**
     * items: [
     *   ['label' => 'Home', 'href' => route('...')],
     *   ['label' => 'Projects', 'href' => route('...')],
     *   ['label' => 'Flowbite'], // último sin href (current)
     * ]
     */
    'items' => [],
    'ariaLabel' => 'Breadcrumb',
    'showHomeIcon' => true,
])

<nav
    class="flex"
    aria-label="{{ $ariaLabel }}"
    x-data="breadcrumb({ items: @js($items), showHomeIcon: @js((bool) $showHomeIcon) })"
>
    <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
        <template x-for="(item, index) in normalizedItems" :key="index">
            <li
                :aria-current="item.current ? 'page' : null"
                class="inline-flex items-center"
            >
                <template x-if="index === 0">
                    <a
                        :href="item.href || '#'
                        "
                        class="inline-flex items-center text-sm font-medium text-body hover:text-fg-brand"
                    >
                        <template x-if="showHomeIcon">
                            <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5"/>
                            </svg>
                        </template>
                        <span x-text="item.label"></span>
                    </a>
                </template>

                <template x-if="index !== 0">
                    <div class="flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 5 7 7-7 7"/>
                        </svg>

                        <template x-if="!item.current && item.href">
                            <a
                                :href="item.href"
                                class="inline-flex items-center text-sm font-medium text-body hover:text-fg-brand"
                                x-text="item.label"
                            ></a>
                        </template>

                        <template x-if="item.current || !item.href">
                            <span
                                class="inline-flex items-center text-sm font-medium text-body-subtle"
                                x-text="item.label"
                            ></span>
                        </template>
                    </div>
                </template>
            </li>
        </template>
    </ol>
</nav>

<script>
    // Registro seguro (evita duplicar Alpine.data si el componente se incluye varias veces)
    if (!window.__reclusolBreadcrumbRegistered) {
        window.__reclusolBreadcrumbRegistered = true;

        document.addEventListener('alpine:init', () => {
            Alpine.data('breadcrumb', (cfg) => ({
                items: Array.isArray(cfg?.items) ? cfg.items : [],
                showHomeIcon: cfg?.showHomeIcon !== false,

                get normalizedItems() {
                    const items = this.items
                        .filter(Boolean)
                        .map((item) => ({
                            label: item?.label ?? '',
                            href: item?.href ?? null,
                            current: Boolean(item?.current),
                        }));

                    // Si no se marcó explícitamente el current, el último es current
                    const hasExplicitCurrent = items.some((i) => i.current);
                    if (!hasExplicitCurrent && items.length > 0) {
                        items[items.length - 1].current = true;
                        items[items.length - 1].href = null;
                    }

                    // Si un item es current, no debe navegar
                    return items.map((i) => (i.current ? { ...i, href: null } : i));
                },
            }));
        });
    }
</script>
