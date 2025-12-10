@props([
    'items' => [],
    'active' => null,
    'id' => 'tabs-'.(Illuminate\Support\Str::random(6)),
])

@php
    $items = $items ?? [];
    $defaultActive = $active ?? ($items[0]['id'] ?? null);
    $ids = array_values(array_map(fn($it) => $it['id'] ?? null, $items));
@endphp

<div
    x-data="{
        active: @js($defaultActive),
        tabs: @js($ids),
        select(id){ this.active = id; },
        next(){ if(!this.tabs.length) return; let i = this.tabs.indexOf(this.active); this.active = this.tabs[(i + 1) % this.tabs.length]; },
        prev(){ if(!this.tabs.length) return; let i = this.tabs.indexOf(this.active); this.active = this.tabs[(i - 1 + this.tabs.length) % this.tabs.length]; }
    }"
    x-on:keydown.arrow-right.prevent="next()"
    x-on:keydown.arrow-left.prevent="prev()"
    class="w-full"
>
    <!-- Tab list -->
    <div class="mb-4 border-b border-neutral">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
            @foreach($items as $it)
                @php $tid = $it['id'] ?? 'tab-'.\Illuminate\Support\Str::slug($it['label'] ?? 'tab'); @endphp
                <li role="presentation" class="me-2">
                    <button
                        id="{{ $tid }}-tab"
                        type="button"
                        role="tab"
                        x-on:click.prevent="select('{{ $it['id'] }}')"
                        :aria-selected="(active === '{{ $it['id'] }}') + ''"
                        :class="active === '{{ $it['id'] }}' ? 'border-M1 text-M1' : 'border-transparent text-neutral-600 hover:text-M1 hover:border-M1'"
                        class="inline-block p-4 border-b-2 rounded-t-base focus:outline-none transition"
                    >
                        {{ $it['label'] ?? '' }}
                    </button>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Panels -->
    <div id="{{ $id }}-content">
        @foreach($items as $it)
            <div
                id="{{ $it['id'] }}"
                role="tabpanel"
                aria-labelledby="{{ $it['id'] }}-tab"
                x-show="active === '{{ $it['id'] }}'"
                x-cloak
                class="p-4 rounded-base bg-neutral-secondary-soft"
            >
                {!! $it['content'] ?? '' !!}
            </div>
        @endforeach
    </div>

</div>
