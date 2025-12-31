<div>
    <!-- ESTADO -->
    <div class="space-y-2 w-full">
        <label for="estado" class="block text-xs font-medium text-M3">Estado del
            Requerimiento</label>
        <select id="estado" name="estado" required
            class="form-input w-full px-3 py-2.5 text-sm border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
            <option value="">Selecciona un estado</option>
            @foreach ($estados as $estado)
                <option value="{{ $estado['id'] }}" {{ old('estado') == $estado['id'] ? 'selected' : '' }}>
                    {{ $estado['nombre'] }}
                </option>
            @endforeach
        </select>
        <span class="error-message text-error text-xs hidden"></span>
    </div>
</div>
