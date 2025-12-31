<div class="grid lg:grid-cols-1 gap-6 ">
    <!-- Validaciones y Remuneración -->
    <div>
        <div class="space-y-5 w-full">
            <label for="sueldo_basico" class="block text-xs font-medium text-M3">
                <i class="fas fa-chart-line mr-2 text-M3"></i>
                Sueldo básico (S/) *
            </label>

            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral-dark select-none">S/</span>
                <input type="number" id="sueldo_basico" name="sueldo_basico" inputmode="decimal" min="0"
                    step="0.01" placeholder="0.00" value="{{ old('sueldo_basico') }}" required
                    class="form-input w-full pl-9 px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
            </div>

            <div class="space-y-2">
                <label class="block text-xs font-medium text-M3">
                    <i class="fas fa-chart-line mr-2 text-M3"></i>
                    Beneficios adicionales incluidos *
                </label>

                @php
                    $oldBeneficios = old('beneficios');
                    $oldBeneficiosArr = is_array($oldBeneficios) ? $oldBeneficios : (is_string($oldBeneficios) ? array_filter(explode(',', $oldBeneficios)) : []);
                    $beneficiosFromReq = isset($requerimiento) ? ($requerimiento->beneficios ?? '') : '';
                    $beneficiosFromReqArr = is_string($beneficiosFromReq) ? array_filter(explode(',', $beneficiosFromReq)) : [];
                    $checkedBeneficios = !empty($oldBeneficiosArr) ? $oldBeneficiosArr : $beneficiosFromReqArr;

                    $beneficiosOptions = [];
                    if (!empty($beneficios) && is_array($beneficios)) {
                        $beneficiosOptions = $beneficios;
                    } else {
                        $beneficiosOptions = [
                            'escala_a' => 'Seguro de Salud',
                            'escala_b' => 'CTS',
                            'escala_c' => 'Vacaciones',
                            'escala_d' => 'Asignación familiar',
                            'escala_e' => 'Utilidades',
                        ];
                    }
                @endphp

                <div id="beneficios-group" class="rounded-lg border border-neutral bg-white p-3">
                    <div class="grid sm:grid-cols-2 gap-2">
                        @foreach ($beneficiosOptions as $key => $label)
                            @php
                                $isChecked = in_array((string) $key, array_map('strval', $checkedBeneficios), true);
                            @endphp
                            <label class="flex items-center gap-2 rounded-lg border border-neutral px-3 py-2 cursor-pointer hover:bg-neutral-lightest">
                                <input type="checkbox" name="beneficios[]" value="{{ $key }}" @checked($isChecked)
                                    class="h-4 w-4 rounded border-neutral text-M1 focus:ring-M1">
                                <span class="text-sm text-neutral-darker">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <p id="beneficios-info" class="text-xs text-neutral-dark mt-2"></p>
                <span id="beneficios-error" class="error-message text-error text-xs hidden"></span>
            </div>
            <span class="error-message text-error text-xs hidden"></span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const group = document.getElementById('beneficios-group');
        const info = document.getElementById('beneficios-info');
        if (!group || !info) return;

        function updateBeneficiosInfo() {
            const checked = Array.from(group.querySelectorAll('input[type="checkbox"][name="beneficios[]"]:checked'));
            if (checked.length === 0) {
                info.textContent = '';
                return;
            }

            const labels = checked
                .map((cb) => cb.closest('label')?.querySelector('span')?.textContent?.trim())
                .filter(Boolean);

            info.textContent = labels.length ? ('Seleccionado: ' + labels.join(', ')) : '';
        }

        group.addEventListener('change', updateBeneficiosInfo);
        updateBeneficiosInfo();
    });
</script>
