@use('Illuminate\Support\Str')

@props([
    'columns' => [],
    'rows' => [],
    'emptyMessage' => 'No records found.',
    'initialPerPage' => 10,
])

<div x-data="{
        search: '',
        perPage: {{ (int) $initialPerPage }},
        page: 1,
        gotoPage(p){ this.page = p; this.applyFilters(); },
        applyFilters(){
            // filter rows by search and apply pagination visibility
            const q = (this.search || '').toLowerCase();
            const rows = Array.from($refs.tbody.querySelectorAll('tr'));
            const visible = [];
            rows.forEach(r => {
                const hay = (r.dataset.search || '').includes(q);
                r.style.display = hay ? '' : 'none';
                if (hay) visible.push(r);
            });

            // pagination
            const start = (this.page - 1) * this.perPage;
            const end = start + this.perPage;
            visible.forEach((r, idx) => {
                r.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            // cards (mobile list)
            const cards = Array.from($refs.cards.querySelectorAll('[data-id]'));
            cards.forEach(c => {
                const show = (c.dataset.search || '').includes(q);
                c.style.display = show ? '' : 'none';
            });

        }
    }">

    <div class="p-4 border-b border-neutral flex flex-col md:flex-row md:items-center md:justify-between gap-3">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Mostrar</label>
            <select x-model="perPage" @change="page=1; applyFilters()" class="text-sm rounded border-gray-200">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
            </select>
        </div>

        <div class="flex items-center gap-2">
            <input x-model.debounce="search" @input="page=1; applyFilters()" type="search" placeholder="Buscar..."
                class="px-3 py-2 rounded border border-gray-200 text-sm w-64" />
        </div>
    </div>

    <!-- Desktop table -->
    <div class="overflow-x-auto">
        <table class="w-full bg-white">
            <thead class="bg-neutral-lightest rounded-t-2xl border border-neutral">
                <tr class="text-left text-sm">
                    @foreach ($columns as $col)
                        @php $align = $col['align'] ?? 'text-center'; @endphp
                        <th class="px-4 py-3 font-bold text-M2 uppercase {{ $align }}">
                            {{ $col['label'] }}
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody x-ref="tbody" class="divide-y divide-neutral">
                @forelse ($rows as $r)
                    @php
                        // build searchable string and data attributes
                        $searchParts = [];
                        $dataAttrs = '';
                        foreach($columns as $col){
                            $k = $col['key'];
                            $val = data_get($r, $k, '');
                            $searchParts[] = strip_tags((string) $val);
                            $dataKey = 'data-col-' . str_replace('_','-',$k);
                            $dataAttrs .= ' ' . $dataKey . '="' . e((string) $val) . '"';
                        }
                        $searchValue = strtolower(implode(' ', $searchParts));
                    @endphp
                    <tr data-search="{{ $searchValue }}" {!! $dataAttrs !!}>
                        @foreach($columns as $col)
                            @php $k = $col['key']; $align = $col['align'] ?? 'text-center'; @endphp
                            <td class="px-4 py-3 text-M2 text-sm {{ $align }}">
                                {!! data_get($r, $k, '—') !!}
                            </td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) }}" class="text-center py-6 text-M2 text-sm">{{ $emptyMessage }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile cards -->
    <div class="hidden" x-ref="cards">
        @forelse($rows as $r)
            @php
                $searchParts = [];
                foreach($columns as $col){ $searchParts[] = strip_tags((string) data_get($r, $col['key'], '')); }
                $searchValue = strtolower(implode(' ', $searchParts));
            @endphp
            <div data-id="{{ data_get($r,'id', Str::random(6)) }}" data-search="{{ $searchValue }}" class="bg-white rounded-lg border p-3 shadow-sm">
                @foreach($columns as $col)
                    <div class="flex justify-between py-1">
                        <div class="text-xs text-gray-500">{{ $col['label'] }}</div>
                        <div class="text-sm text-M2">{!! data_get($r, $col['key'], '—') !!}</div>
                    </div>
                @endforeach
            </div>
        @empty
            <div class="text-center py-6 text-M2 text-sm">{{ $emptyMessage }}</div>
        @endforelse
    </div>

    <script>
        // Ensure initial pagination/filter run after Alpine initializes
        document.addEventListener('alpine:init', () => {
            // nothing; we rely on component's local x-data to call applyFilters via input events
        });
    </script>

</div>
