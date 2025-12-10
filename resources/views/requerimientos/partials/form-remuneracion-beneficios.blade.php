<div class="grid lg:grid-cols-1 gap-6 ">
    <!-- Validaciones y Remuneración -->
    <div>
        <div class="flex items-center mb-6">
            <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
            </div>
            <div>
                <h2 class="text-lg font-bold text-M2">Remuneración y Beneficios</h2>
            </div>
        </div>

        <div class="space-y-6 w-full">
            <label for="sueldo_basico" class="block text-sm font-semibold text-gray-700">
                <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                Sueldo básico (S/) *
            </label>

            <div class="relative">
                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 select-none">S/</span>
                <input type="number" id="sueldo_basico" name="sueldo_basico" inputmode="decimal" min="0"
                    step="0.01" placeholder="0.00" value="{{ old('sueldo_basico') }}" required
                    class="form-input w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
            </div>

            <div class="space-y-2">
                <label for="beneficios" class="block text-sm font-semibold text-gray-700">
                    <i class="fas fa-chart-line mr-2 text-purple-500"></i>
                    Beneficios adicionales incluidos *
                </label>
                <select id="beneficios" name="beneficios" required
                    class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition-all duration-300">
                    <option value="">Selecciona</option>
                    <option value="escala_a">Seguro de Salud</option>
                    <option value="escala_b">CTS</option>
                    <option value="escala_c">Vacaciones</option>
                    <option value="escala_d">Asignación familiar</option>
                    <option value="escala_d">Utilidades</option>
                </select>
                <span class="error-message text-red-500 text-sm hidden"></span>
            </div>
            <span class="error-message text-red-500 text-sm hidden"></span>
        </div>
    </div>
</div>
