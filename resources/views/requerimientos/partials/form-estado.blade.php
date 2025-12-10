<div>
    <div class="flex items-center mb-6">
        <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-M2">Estado</h2>
            <p class="text-sm text-M3">Define el estado actual de la solicitud</p>
        </div>
    </div>
    <!-- ESTADO -->
    <div class="space-y-2 w-full">
        <label for="estado" class="block text-sm font-medium text-gray-700">Estado del
            Requerimiento</label>
        <select id="estado" name="estado" required
            class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
            <option value="">Selecciona un estado</option>
            @foreach ($estados as $estado)
                <option value="{{ $estado['id'] }}" {{ old('estado') == $estado['id'] ? 'selected' : '' }}>
                    {{ $estado['nombre'] }}
                </option>
            @endforeach
        </select>
        <span class="error-message text-red-500 text-sm hidden"></span>
    </div>
</div>
