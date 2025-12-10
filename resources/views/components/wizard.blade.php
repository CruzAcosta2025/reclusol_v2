@props([
    // Array de pasos: cada item requiere 'id', 'label', 'content' (renderizado en la vista)
    'steps' => [],
    'action' => '#',
    'method' => 'POST',
])

<form
    x-data="{
        step: 0,
        stepCount: {{ count($steps) }},
        next() {
            if (this.validateStep(this.step) && this.step < this.stepCount - 1) {
                this.step++;
            }
        },
        prev() {
            if (this.step > 0) this.step--;
        },
        validateStep(idx) {
            const container = this.$refs[`step-${idx}`];
            if (!container) return true;
            const required = container.querySelectorAll('[required]');
            let ok = true;
            required.forEach((el) => {
                const val = (el.value || '').trim();
                if (!val) {
                    el.classList.add('border-red-500');
                    const err = el.parentElement?.querySelector('.error-message');
                    if (err) err.classList.remove('hidden');
                    ok = false;
                } else {
                    el.classList.remove('border-red-500');
                    const err = el.parentElement?.querySelector('.error-message');
                    if (err) err.classList.add('hidden');
                }
            });
            return ok;
        },
        validateAll() {
            for (let i = 0; i < this.stepCount; i++) {
                if (!this.validateStep(i)) return false;
            }
            return true;
        },
        submitForm() {
            if (!this.validateAll()) {
                this.step = 0;
                return;
            }
            this.$el.submit();
        },
    }"
    x-on:submit.prevent="submitForm"
    method="{{ $method }}"
    action="{{ $action }}"
    class="space-y-4"
>
    @csrf

    <!-- Indicador de pasos -->
    <div class="flex items-center justify-between gap-4">
        @foreach ($steps as $index => $step)
            <div class="flex-1 flex items-center">
                <div class="flex flex-col items-center text-center w-full">
                    <div
                        class="w-10 h-10 flex items-center justify-center rounded-full border-2"
                        :class="step === {{ $index }} ? 'border-M1 text-M1 bg-M6' : 'border-neutral text-M3 bg-white'"
                    >
                        {{ $index + 1 }}
                    </div>
                    <p class="mt-2 text-sm" :class="step === {{ $index }} ? 'text-M1 font-semibold' : 'text-M3'">
                        {{ $step['label'] ?? 'Paso' }}
                    </p>
                </div>
                @if (!$loop->last)
                    <div class="h-px flex-1 bg-neutral hidden md:block"></div>
                @endif
            </div>
        @endforeach
    </div>

    <!-- Contenido de pasos -->
    <div class="bg-M6 rounded-lg border border-neutral shadow-sm p-4">
        @foreach ($steps as $index => $step)
            <div x-show="step === {{ $index }}" x-ref="step-{{ $index }}">
                {!! $step['content'] ?? '' !!}
            </div>
        @endforeach
    </div>

    <!-- Controles -->
    <div class="flex justify-between items-center">
        <div>
            <x-cancel-button type="button" x-on:click="$dispatch('close-modal', 'crearRequerimiento')">Cancelar</x-cancel-button>
        </div>
        <div class="flex gap-2">
            <x-secondary-button type="button" x-on:click="prev" x-show="step > 0">Anterior</x-secondary-button>
            <x-primary-button type="button" x-on:click="next" x-show="step < stepCount - 1">Siguiente</x-primary-button>
            <x-confirm-button type="submit" x-show="step === stepCount - 1">Guardar</x-confirm-button>
        </div>
    </div>
</form>