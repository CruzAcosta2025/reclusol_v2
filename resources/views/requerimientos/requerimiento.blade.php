@extends('layouts.app')

@section('content')
    <div class="space-y-4">
        {{-- Botón volver --}}
        <a href="{{ route('requerimientos.filtrar') }}"
            class="bg-M6 hover:bg-neutral-lightest text-M2 rounded-lg border border-neutral shadow-sm p-5 font-semibold  transition-colors flex items-center space-x-3 px-6 py-3 text-sm z-10 group">
            <i class="fas fa-arrow-left text-M2 group-hover:-translate-x-1 transition-transform"></i>
            <span class="font-bold">Volver</span>
        </a>
        <!-- Header -->
        <x-block>
            <div class="flex flex-col gap-1">
                <h1 class="text-xl font-bold text-M2">
                    Nueva Solicitud
                </h1>
                <p class="text-M3 mt-1 text-base">
                    Complete la información para crear una nueva solicitud de personal
                </p>
            </div>
        </x-block>

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('requerimientos.store') }}">
            @csrf
            <div class="grid lg:grid-cols-2 gap-8 mb-8">

            </div>


            <!-- Save Button -->
            <div class="flex justify-center">
                <button type="submit"
                    class="btn-primary bg-blue-600 hover:bg-blue-700 text-white px-16 py-4 rounded-lg font-semibold text-lg flex items-center space-x-3 shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <i class="fas fa-save text-xl"></i>
                    <span>Guardar Requerimiento</span>
                </button>
            </div>
        </form>
        <x-alerts /> {{-- SweetAlert success / error --}}

        <!-- Loading Overlay -->
        <div id="loading-overlay"
            class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <span class="text-gray-700 font-medium">Guardando requerimiento...</span>
            </div>
        </div>

        <script>
            function validateForm() {
                const requiredFields = document.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    const errorMessage = field.parentElement.querySelector('.error-message');
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        if (errorMessage) {
                            errorMessage.textContent = 'Este campo es obligatorio';
                            errorMessage.classList.remove('hidden');
                        }
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                        if (errorMessage) {
                            errorMessage.classList.add('hidden');
                        }
                    }
                });

                return isValid;
            }


            document.addEventListener('DOMContentLoaded', function() {
                // Selecciona los campos
                const cantidadRequerida = document.getElementById('cantidad_requerida');
                const cantidadMasculino = document.getElementById('cantidad_masculino');
                const cantidadFemenino = document.getElementById('cantidad_femenino');
                const errorCantidad = document.getElementById('error-cantidad');
                const errorSexo = document.getElementById('error-sexo');

                function validarSumaSexo() {
                    // Valores a enteros (o 0)
                    const req = parseInt(cantidadRequerida.value) || 0;
                    const masc = parseInt(cantidadMasculino.value) || 0;
                    const fem = parseInt(cantidadFemenino.value) || 0;

                    // Reset errores
                    cantidadRequerida.classList.remove('border-red-500');
                    cantidadMasculino.classList.remove('border-red-500');
                    cantidadFemenino.classList.remove('border-red-500');
                    errorCantidad.classList.add('hidden');
                    errorSexo.classList.add('hidden');

                    if (req === 0 && (masc > 0 || fem > 0)) {
                        // Si no hay cantidad requerida pero sí en sexo
                        errorCantidad.textContent = "Primero indique la cantidad requerida.";
                        errorCantidad.classList.remove('hidden');
                        cantidadRequerida.classList.add('border-red-500');
                        return false;
                    }
                    if ((masc + fem) > req) {
                        errorSexo.textContent = `La suma (${masc + fem}) supera la cantidad requerida (${req}).`;
                        errorSexo.classList.remove('hidden');
                        cantidadMasculino.classList.add('border-red-500');
                        cantidadFemenino.classList.add('border-red-500');
                        return false;
                    }
                    if ((masc + fem) < req) {
                        errorSexo.textContent = `La suma (${masc + fem}) es menor que la cantidad requerida (${req}).`;
                        errorSexo.classList.remove('hidden');
                        cantidadMasculino.classList.add('border-red-500');
                        cantidadFemenino.classList.add('border-red-500');
                        return false;
                    }
                    // Si es igual está correcto
                    return true;
                }

                // Valida en cada cambio
                [cantidadRequerida, cantidadMasculino, cantidadFemenino].forEach(input => {
                    input.addEventListener('input', validarSumaSexo);
                });

                // Si usas validación al enviar formulario, puedes impedir submit si hay error
                const form = cantidadRequerida.closest('form');
                form.addEventListener('submit', function(e) {
                    if (!validarSumaSexo()) {
                        e.preventDefault();
                    }
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                const tipoPersonal = document.getElementById('tipo_personal');
                const camposOperativo = document.getElementById('campos-operativo');
                if (!tipoPersonal || !camposOperativo) return;

                // Si mañana agregas Operativo 5º (03), solo lo sumas aquí.
                const CODIGOS_OPERATIVO = ['01']; // 01 = Operativo 4º

                const inputsOperativo = camposOperativo.querySelectorAll('select, input, textarea');

                function toggleCamposOperativo() {
                    const esOperativo = CODIGOS_OPERATIVO.includes(tipoPersonal.value);

                    camposOperativo.style.display = esOperativo ? '' : 'none';

                    inputsOperativo.forEach(el => {
                        el.required = esOperativo;
                        if (!esOperativo) {
                            el.value = '';
                            // Si prefieres bloquearlos en lugar de limpiar, usa:
                            // el.disabled = true; (y quítalo cuando esOperativo)
                        } else {
                            // el.disabled = false;
                        }
                    });
                }

                tipoPersonal.addEventListener('change', toggleCamposOperativo);
                toggleCamposOperativo(); // inicial (por si vienes de "editar")
            });

            document.addEventListener('DOMContentLoaded', () => {
                const selSucursal = document.getElementById('sucursal');
                const selCliente = document.getElementById('cliente');
                const URL_CLIENTES = "{{ route('requerimientos.clientes_por_sucursal') }}"; // usa tu ruta al SP

                function setOptions(select, items, placeholder) {
                    select.innerHTML = '';
                    const first = document.createElement('option');
                    first.value = '';
                    first.textContent = placeholder;
                    select.appendChild(first);

                    items.forEach(x => {
                        const value = (x.CODIGO_CLIENTE ?? x.codigo_cliente ?? '').toString().trim();
                        const text = (x.NOMBRE_CLIENTE ?? x.nombre_cliente ?? value);
                        const op = document.createElement('option');
                        op.value = value;
                        op.textContent = text;
                        select.appendChild(op);
                    });
                }

                async function cargarClientesPorSucursal(codSucursal) {
                    if (!codSucursal) {
                        setOptions(selCliente, [], 'Selecciona un cliente');
                        return;
                    }
                    selCliente.innerHTML = '<option value="">Cargando...</option>';

                    try {
                        const u = new URL(URL_CLIENTES, window.location.origin);
                        u.searchParams.set('codigo_sucursal', codSucursal);

                        const res = await fetch(u, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                        const data = await res.json();

                        if (!Array.isArray(data) || data.length === 0) {
                            setOptions(selCliente, [], 'Sin clientes para esta sucursal');
                            return;
                        }
                        setOptions(selCliente, data, 'Selecciona un cliente');
                    } catch (e) {
                        console.error(e);
                        setOptions(selCliente, [], 'Error al cargar');
                    }
                }

                if (selSucursal) {
                    selSucursal.addEventListener('change', () => {
                        cargarClientesPorSucursal(selSucursal.value || '');
                    });

                    // Si ya viene seleccionada (editar / volver con old inputs)
                    if (selSucursal.value) selSucursal.dispatchEvent(new Event('change'));
                }
            });


            document.addEventListener('DOMContentLoaded', function() {
                const fechaInicio = document.getElementById('fecha_inicio');
                const fechaFin = document.getElementById('fecha_fin');
                const urgenciaBox = document.getElementById('urgenciaAutoBox');
                const urgenciaDiv = document.getElementById('urgenciaAuto');
                const urgenciaSelect = document.getElementById('urgencia');

                function setUrgencia(valor, texto, colorClass) {
                    urgenciaDiv.textContent = texto;
                    urgenciaDiv.className =
                        'rounded-lg px-4 py-2 font-semibold text-center transition-all duration-300 ' + colorClass;
                    urgenciaSelect.value = valor;
                    urgenciaSelect.className =
                        'form-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all duration-300 ' +
                        colorClass;
                }

                function calcularUrgencia() {
                    if (fechaInicio.value && fechaFin.value) {
                        const inicio = new Date(fechaInicio.value);
                        const fin = new Date(fechaFin.value);
                        const diffMs = fin - inicio;
                        const diffDias = diffMs / (1000 * 60 * 60 * 24);

                        if (diffDias < 0) {
                            setUrgencia("Invalida", "¡Fechas inválidas!", "bg-gray-400 text-white");
                        } else if (diffDias <= 7) {
                            setUrgencia("Alta", "Nivel de urgencia: Alta (1 semana)", "bg-red-500 text-white");
                        } else if (diffDias > 7 && diffDias <= 14) {
                            setUrgencia("Media", "Nivel de urgencia: Media (2 semanas)", "bg-yellow-400 text-gray-900");
                        } else if (diffDias > 14 && diffDias <= 31) {
                            setUrgencia("Baja", "Nivel de urgencia: Baja (1 mes)", "bg-green-500 text-white");
                        } else {
                            setUrgencia("Mayor", "Plazo mayor a 1 mes", "bg-blue-400 text-white");
                        }
                    } else {
                        setUrgencia("", "NO SE SELECCIONÓ LA FECHA", "bg-gray-200 text-gray-700");
                    }
                }

                // Escuchar cambios en las fechas
                fechaInicio.addEventListener('change', calcularUrgencia);
                fechaFin.addEventListener('change', calcularUrgencia);

                // Inicializar estado al cargar
                calcularUrgencia();
            });

            // Real-time validation
            document.querySelectorAll('.form-input').forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.hasAttribute('required') && !this.value.trim()) {
                        this.classList.add('border-red-500');
                        const errorMessage = this.parentElement.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.textContent = 'Este campo es obligatorio';
                            errorMessage.classList.remove('hidden');
                        }
                    } else {
                        this.classList.remove('border-red-500');
                        const errorMessage = this.parentElement.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.classList.add('hidden');
                        }
                    }
                });
            });

            document.getElementById('cliente').addEventListener('change', function() {
                let clienteId = this.value;
                let sedeSelect = document.getElementById('sede');
                sedeSelect.innerHTML = '<option value="">Cargando...</option>';

                if (clienteId) {
                    fetch('/requerimientos/sedes-por-cliente?codigo_cliente=' + clienteId)
                        .then(response => response.json())
                        .then(sedes => {
                            sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                            sedes.forEach(sede => {
                                sedeSelect.innerHTML +=
                                    `<option value="${sede.CODIGO}">${sede.SEDE}</option>`;
                            });
                        })
                        .catch(() => {
                            sedeSelect.innerHTML = '<option value="">Error al cargar</option>';
                        });
                } else {
                    sedeSelect.innerHTML = '<option value="">Selecciona una sede</option>';
                }
            });


            document.addEventListener('DOMContentLoaded', async () => {
                const selTipoPersonal = document.getElementById('tipo_personal');
                const selTipoCargo = document.getElementById('tipo_cargo');
                const selCargoSolicitado = document.getElementById('cargo_solicitado');

                const URL_TIPOS = "{{ route('api.tipos_cargo') }}";
                const URL_CARGOS = "{{ route('api.cargos') }}";

                function setLoading(select, texto = 'Cargando...') {
                    select.innerHTML = `<option value="">${texto}</option>`;
                    select.disabled = true;
                }

                function setEmpty(select, texto = 'Seleccione...') {
                    select.innerHTML = `<option value="">${texto}</option>`;
                    select.disabled = true;
                }

                function fillSelect(select, data, placeholder = 'Seleccione...') {
                    select.innerHTML = `<option value="">${placeholder}</option>`;
                    data.forEach(it => {
                        const o = document.createElement('option');
                        o.value = String(it.value).trim();
                        o.textContent = it.label;
                        select.appendChild(o);
                    });
                    select.disabled = data.length === 0;
                }

                async function cargarTiposCargo(tipoPersonal, preselect = null) {
                    if (!tipoPersonal) {
                        setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
                        setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                        return;
                    }
                    setLoading(selTipoCargo);
                    setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                    try {
                        const res = await fetch(
                            `${URL_TIPOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}`);
                        const data = await res.json(); // [{value,label}]
                        fillSelect(selTipoCargo, data, 'Seleccione el tipo de cargo');
                        if (preselect) {
                            selTipoCargo.value = preselect;
                            selTipoCargo.dispatchEvent(new Event('change'));
                        }
                    } catch {
                        setEmpty(selTipoCargo, 'Error al cargar');
                    }
                }

                async function cargarCargos(tipoPersonal, tipoCargo, preselect = null) {
                    if (!tipoPersonal || !tipoCargo) {
                        setEmpty(selCargoSolicitado, 'Seleccione el cargo');
                        return;
                    }
                    setLoading(selCargoSolicitado);
                    try {
                        const res = await fetch(
                            `${URL_CARGOS}?tipo_personal=${encodeURIComponent(tipoPersonal)}&tipo_cargo=${encodeURIComponent(tipoCargo)}`
                        );
                        const data = await res.json(); // [{value,label}]
                        fillSelect(selCargoSolicitado, data, 'Seleccione el cargo');
                        if (preselect) selCargoSolicitado.value = preselect;
                    } catch {
                        setEmpty(selCargoSolicitado, 'Error al cargar');
                    }
                }

                // Eventos
                selTipoPersonal.addEventListener('change', () => cargarTiposCargo(selTipoPersonal.value));
                selTipoCargo.addEventListener('change', () => cargarCargos(selTipoPersonal.value, selTipoCargo
                    .value));

                // Estado inicial
                setEmpty(selTipoCargo, 'Seleccione el tipo de cargo');
                setEmpty(selCargoSolicitado, 'Seleccione el cargo');

                // Precarga por old()
                const oldTipoPersonal = "{{ old('tipo_personal') }}";
                const oldTipoCargo = "{{ old('tipo_cargo') }}";
                const oldCargoSolicitado = "{{ old('cargo_solicitado') }}";
                if (oldTipoPersonal) {
                    selTipoPersonal.value = oldTipoPersonal;
                    await cargarTiposCargo(oldTipoPersonal, oldTipoCargo);
                    if (oldTipoCargo) {
                        await cargarCargos(oldTipoPersonal, oldTipoCargo, oldCargoSolicitado);
                    }
                }
            });



            // Update scale info based on selection
            document.getElementById('beneficios').addEventListener('change', function() {
                const infoBox = document.querySelector('.bg-blue-50 p strong');
                if (infoBox && this.value) {
                    infoBox.textContent = `Escala seleccionada: ${this.options[this.selectedIndex].text}`;
                }
            });
        </script>
    @endsection
