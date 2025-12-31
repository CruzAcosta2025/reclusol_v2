<div>
    <div class="grid md:grid-cols-2 gap-5 w-full">
        <!-- Fecha Límite de Reclutamiento -->
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-M3">
                <i class="fas fa-calendar-alt mr-2 text-M3"></i>
                Fecha de Solicitud
            </label>
            <input type="text" value="{{ now()->format('d-m-Y') }}" disabled
                class="form-input w-full px-3 py-2.5 border border-neutral bg-neutral-lightest text-sm text-neutral-darker rounded-lg focus:outline-none cursor-not-allowed">
            <input type="hidden" name="fecha_solicitud" value="{{ now()->format('Y-m-d') }}">
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-M3">
                <i class="fas fa-user-alt mr-2 text-M3"></i>
                Solicitado por *
            </label>
            <input type="text" value="{{ Auth::user()->name ?? 'INVITADO' }}" disabled
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral bg-neutral-lightest text-neutral-darker rounded-lg focus:outline-none cursor-not-allowed">
            <input type="hidden" name="user_id" value="{{ Auth::id() }}">
            <input type="hidden" name="cargo_usuario"
                value="{{ Auth::user()->cargoInfo?->DESC_TIPO_CARG ?? 'Sin rol' }}">
        </div>

        <!-- Sucursal -->
        <div class="space-y-1.5">
            <label for="sucursal" class="block text-xs font-medium text-M3">
                <i class="fas fa-map-marker-alt mr-2 text-M3"></i>
                Sucursal *
            </label>
            <select id="sucursal" name="sucursal" required
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
                <option class="text-neutral" value="">Selecciona la sucursal</option>
                @foreach ($sucursales as $suc => $descripcion)
                    <option value="{{ $suc }}">{{ $descripcion }}</option>
                @endforeach
            </select>
            <span class="error-message text-error text-xs hidden"></span>

        </div>

        <!-- Cliente -->
        <div class="space-y-1.5">
            <label for="cliente" class="block text-xs font-medium text-M3">
                <i class="fa-solid fa-user-tie mr-2 text-M3"></i>
                Cliente *
            </label>
            <select id="cliente" name="cliente" required
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
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
            <span class="error-message text-error text-xs hidden"></span>
        </div>

        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-M3">
                <i class="fas fa-user-alt mr-2 text-M3"></i>
                Tipo de Personal *
            </label>
            <select name="tipo_personal" id="tipo_personal" required
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
                <option value="">Selecciona el tipo de personal</option>
                @foreach ($tipoPersonal as $codigo => $desc)
                    <option value="{{ $codigo }}" @selected(old('tipo_personal') == $codigo)>
                        {{ $desc }}
                    </option>
                @endforeach
            </select>
            <span class="error-message text-error text-xs hidden"></span>
        </div>

        <!-- Tipo Cargo -->
        <div class="space-y-1.5">
            <label for="tipo_cargo" class="block text-xs font-medium text-M3">
                <i class="fas fa-briefcase mr-2 text-M3"></i>
                Tipo de Cargo *
            </label>
            <select id="tipo_cargo" name="tipo_cargo" required
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
                <option value="">Selecciona el cargo</option>
            </select>
            <span class="error-message text-error text-xs hidden"></span>
        </div>

        <!-- Cargo Especifico solicitado -->
        <div class="space-y-1.5">
            <label for="cargo_solicitado" class="block text-xs font-medium text-M3">
                <i class="fas fa-briefcase mr-2 text-M2"></i>
                Cargo solicitado *
            </label>
            <select id="cargo_solicitado" name="cargo_solicitado" required
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white">
                <option value="">Selecciona el cargo</option>
            </select>
            <span class="error-message text-error text-xs hidden"></span>
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
        <div class="space-y-1.5">
            <label for="cliente" class="block text-xs font-medium text-M3">
                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                Fecha de Inicio *
            </label>
            <input type="date" name="fecha_inicio" id="fecha_inicio"
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                required>
        </div>

        <div class="space-y-1.5">
            <label for="cliente" class="block text-xs font-medium text-M3">
                <i class="fas fa-calendar-alt mr-2 text-M2"></i>
                Fecha Fin *
            </label>
            <input type="date" name="fecha_fin" id="fecha_fin"
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                required>
        </div>

        <!-- Urgencia (automática por fechas) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-M3">
                <i class="fas fa-exclamation-triangle mr-2 text-M2"></i>
                Urgencia *
            </label>

            <!-- Valor real que se envía (no es selector) -->
            <input type="hidden" name="urgencia" id="urgencia" required>

            <!-- Indicador visual -->
            <div id="urgenciaAutoBox" class="hidden">
                <div id="urgenciaAuto"
                    class="text-sm rounded-lg px-3 py-2.5 font-semibold text-center transition-all duration-200 border">
                </div>
            </div>

            <span class="error-message text-error text-xs hidden"></span>
        </div>


        <!-- Cantidad requerida -->
        <div class="space-y-1.5">
            <label for="cantidad_requerida" class="block text-xs font-medium text-M3">
                <i class="fa-solid fa-users mr-2 text-M2"></i>
                Cantidad requerida *
            </label>
            <input type="number" id="cantidad_requerida" name="cantidad_requerida" required min="1"
                max="999"
                class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                placeholder="Número de personas">
            <span id="error-cantidad" class="error-message text-error text-xs hidden"></span>
        </div>

        <!-- Cantidad por sexo (opcional) -->
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-M3">
                <i class="fa-solid fa-venus-mars text-M2"></i>
                Sexo (opcional)
            </label>
            <div class="flex gap-2">
                <input type="number" id="cantidad_masculino" name="cantidad_masculino" placeholder="Masculino"
                    min="0" max="999"
                    class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                    >
                <input type="number" id="cantidad_femenino" name="cantidad_femenino" placeholder="Femenino"
                    min="0" max="999"
                    class="form-input w-full text-sm px-3 py-2.5 border border-neutral rounded-lg focus:ring-2 focus:ring-M1 focus:border-M1 outline-none transition-all duration-200 bg-white"
                    >
            </div>
            <span id="error-sexo" class="error-message text-error text-xs hidden"></span>
        </div>
    </div>
</div>
