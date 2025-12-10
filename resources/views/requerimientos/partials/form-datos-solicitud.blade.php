<div>
    <div class="flex items-center mb-6">
        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-4">
            <i class="fas fa-info-circle text-M2 text-xl"></i>
        </div>
        <div>
            <h2 class="text-lg font-bold text-M2">Datos de la Solicitud</h2>
            <p class="text-sm text-M3">Datos básicos del requerimiento de personal</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6 w-full">
        <!-- Fecha Límite de Reclutamiento -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-M3">
                <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                Fecha de Solicitud
            </label>
            <input type="text" value="{{ now()->format('d-m-Y') }}" disabled
                class="form-input w-full px-4 py-3 border border-gray-300 bg-gray-100 text-sm text-gray-600 rounded-lg focus:outline-none cursor-not-allowed">
            <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d') }}">
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-M3">
                <i class="fas fa-user-alt mr-2 text-blue-500"></i>
                Solicitado por *
            </label>
            <input type="text" value="{{ Auth::user()->name ?? 'INVITADO' }}" disabled
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 bg-gray-100 text-gray-600 rounded-lg focus:outline-none cursor-not-allowed">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="cargo_usuario"
                value="{{ Auth::user()->cargoInfo?->DESC_TIPO_CARG ?? 'Sin rol' }}">
        </div>

        <!-- Sucursal -->
        <div class="space-y-2">
            <label for="sucursal" class="block text-sm font-semibold text-M3">
                <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                Sucursal *
            </label>
            <select id="sucursal" name="sucursal" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                <option class="text-neutral" value="">Selecciona la sucursal</option>
                @foreach ($sucursales as $suc => $descripcion)
                    <option value="{{ $suc }}">{{ $descripcion }}</option>
                @endforeach
            </select>
            <span class="error-message text-red-500 text-sm hidden"></span>

        </div>

        <!-- Cliente -->
        <div class="space-y-2">
            <label for="cliente" class="block text-sm font-semibold text-M3">
                <i class="fa-solid fa-user-tie mr-2 text-blue-500"></i>
                Cliente *
            </label>
            <select id="cliente" name="cliente" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                <option value="">Selecciona un cliente</option>
                @foreach ($clientes as $k => $c)
                    @php
                        if (is_object($c)) {
                            $val = $c->CODIGO_CLIENTE ?? $c->codigo_cliente ?? $k;
                            $label = $c->NOMBRE_CLIENTE ?? $c->nombre_cliente ?? (string) $c;
                        } else {
                            // $clientes may be an associative array [code => name]
                            $val = (is_string($k) && $k !== '0') ? $k : (string) $c;
                            $label = (string) $c;
                        }
                    @endphp
                    <option value="{{ $val }}" @selected(old('cliente') == $val)>{{ $label }}</option>
                @endforeach
            </select>
            <span class="error-message text-red-500 text-sm hidden"></span>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-M3">
                <i class="fas fa-user-alt mr-2 text-blue-500"></i>
                Tipo de Personal *
            </label>
            <select name="tipo_personal" id="tipo_personal" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-neutral focus:border-transparent outline-none transition-all duration-300">
                <option value="">Selecciona el tipo de personal</option>
                @foreach ($tipoPersonal as $codigo => $desc)
                    <option value="{{ $codigo }}" @selected(old('tipo_personal') == $codigo)>
                        {{ $desc }}
                    </option>
                @endforeach
            </select>
            <span class="error-message text-red-500 text-sm hidden"></span>
        </div>

        <!-- Tipo Cargo -->
        <div class="space-y-2">
            <label for="tipo_cargo" class="block text-sm font-semibold text-M3">
                <i class="fas fa-briefcase mr-2 text-blue-500"></i>
                Tipo de Cargo *
            </label>
            <select id="tipo_cargo" name="tipo_cargo" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                <option value="">Selecciona el cargo</option>
            </select>
            <span class="error-message text-red-500 text-sm hidden"></span>
        </div>

        <!-- Cargo Especifico solicitado -->
        <div class="space-y-2">
            <label for="cargo_solicitado" class="block text-sm font-semibold text-M3">
                <i class="fas fa-briefcase mr-2 text-M2"></i>
                Cargo solicitado *
            </label>
            <select id="cargo_solicitado" name="cargo_solicitado" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                <option value="">Selecciona el cargo</option>
            </select>
            <span class="error-message text-red-500 text-sm hidden"></span>
        </div>

        <!-- Ubicación servicio -->
        {{-- <div class="space-y-2">
                        <label for="ubicacion_servicio" class="block text-sm font-semibold text-gray-700">
                            <i class="fa-solid fa-map-location-dot mr-2 text-blue-500"></i>
                            Ubicación del Servicio *
                        </label>
                        <input type="text" name="ubicacion_servicio" class="form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300">
                        </div> --}}

        <!-- Fechas y urgencia -->
        <div class="space-y-2">
            <label for="cliente" class="block text-sm font-semibold text-M3">
                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                Fecha de Inicio *
            </label>
            <input type="date" name="fecha_inicio" id="fecha_inicio"
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                required>
        </div>

        <div class="space-y-2">
            <label for="cliente" class="block text-sm font-semibold text-M3">
                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                Fecha Fin *
            </label>
            <input type="date" name="fecha_fin" id="fecha_fin"
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                required>
        </div>

        <!-- Urgencia automática por fechas -->
        <div id="urgenciaAutoBox" class="mt-2">
            <div id="urgenciaAuto"
                class="text-sm rounded-lg px-4 py-3 font-semibold text-center transition-all duration-300 bg-gray-200 text-gray-700">
                NO SE SELECCIONÓ LA FECHA
            </div>
        </div>

        <div class="space-y-2">
            <label for="urgencia" class="block text-sm font-semibold text-M3">
                <i class="fas fa-exclamation-triangle mr-2 text-M2"></i>
                Urgencia *
            </label>
            <select name="urgencia" id="urgencia" required
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 bg-gray-200 text-gray-700"
                readonly tabindex="-1" style="pointer-events: none;">
                <option value="">NO HAY URGENCIA</option>
                <option value="Alta">Alta (1 semana)</option>
                <option value="Media">Media (2 semanas)</option>
                <option value="Baja">Baja (1 mes)</option>
                <option value="Mayor">Plazo mayor a 1 mes</option>
                <option value="Invalida">¡Fechas inválidas!</option>
            </select>
        </div>


        <!-- Cantidad requerida -->
        <div class="space-y-2">
            <label for="cantidad_requerida" class="block text-sm font-semibold text-M3">
                <i class="fa-solid fa-users mr-2 text-M2"></i>
                Cantidad requerida *
            </label>
            <input type="number" id="cantidad_requerida" name="cantidad_requerida" required min="1"
                max="999"
                class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                placeholder="Número de personas">
            <span id="error-cantidad" class="error-message text-red-500 text-sm hidden"></span>
        </div>

        <!-- Cantidad por sexo -->
        <div class="space-y-2">
            <label class="block text-sm font-semibold text-M3">
                <i class="fa-solid fa-venus-mars text-M2"></i>
                Sexo requerido *
            </label>
            <div class="flex gap-2">
                <input type="number" id="cantidad_masculino" name="cantidad_masculino" placeholder="Masculino"
                    min="0" max="999"
                    class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                    required>
                <input type="number" id="cantidad_femenino" name="cantidad_femenino" placeholder="Femenino"
                    min="0" max="999"
                    class="form-input w-full text-sm px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300"
                    required>
            </div>
            <span id="error-sexo" class="error-message text-red-500 text-sm hidden"></span>
        </div>
    </div>
</div>
