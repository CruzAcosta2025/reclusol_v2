<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Models\TipoCargo;
use App\Models\TipoPersonal;
use App\Models\Cargo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Response;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\PostulanteEnListaNegra;
use App\Notifications\NuevoPostulanteRegistrado;


class PostulanteController extends Controller
{


    /* ---------- FUNCION PARA GUARDAR UN REGISTRO EXTERNO ---------- */

    /*
    public function storeExterno(Request $request)
    {

        Log::info('Iniciando registro externo de postulante', [
            'ip' => $request->ip(),
            'dni' => $request->input('dni')
        ]);

        /* ---------- 1. VALIDAR ---------- 
        $validated = $request->validate([
            'fecha_postula'      => 'required|date',
            'dni'                => 'required|string|size:8|unique:postulantes,dni',
            'nombres'            => 'required|string|max:50',
            'apellidos'          => 'required|string|max:50',
            'fecha_nacimiento'   => 'required|date',
            'edad'               => 'required|integer|min:18|max:120',
            'departamento'       => 'required|string|max:50',
            'provincia'          => 'required|string|max:50',
            'distrito'           => 'required|string|max:50',
            'nacionalidad'       => 'required|string|max:50',
            'grado_instruccion'  => 'required|string|max:50',
            'celular'            => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'tipo_cargo'         => 'required|string|max:50',
            'cargo'              => 'required|string|max:50',
            'experiencia_rubro'  => 'required|string|max:50',
            'sucamec'            => 'required|string|max:10',
            'carne_sucamec'      => 'required|string|max:10',
            'licencia_arma'      => 'required|string|max:10',
            'licencia_conducir'  => 'required|string|max:10',
            'servicio_militar'   => 'nullable|string|max:15',
            'estado_civil'       => 'nullable|string|max:15',
            // Archivos  (máx. 5 MB — ya los validas en el front)
            'cv'  => 'required|file|max:5120',
            'cul' => 'required|file|max:5120',
        ]);
        Log::info('Validación exitosa', $validated);


        $dni    = $validated['dni'];
        $disk   = config('filesystems.default', 'local');  // 'local' (privado) o 'public'
        $folder = "postulantes/{$dni}";

        /* ---------- 1b. Validación adicional: cargo existe y coincide con tipo_cargo ---------- 
        $cargo = Cargo::where('CODI_CARG', $validated['cargo'])->first();
        if (! $cargo) {
            return back()->withInput()->withErrors(['cargo' => 'El cargo seleccionado no existe.']);
        }

        // Verificamos que el cargo pertenece al tipo seleccionado (si almacenaste tipo_cargo en el formulario)
        if ((string) $cargo->TIPO_CARG !== (string) $validated['tipo_cargo']) {
            return back()->withInput()->withErrors(['cargo' => 'El cargo seleccionado no corresponde al tipo de cargo elegido.']);
        }

        /* ---------- 2) Determinar tipo de personal a partir del cargo ---------- 
        // CARGO_TIPO es la columna que, según muestras, relaciona con ADMI_TIPO_PERSONAL.TIPE_CODIGO
        $tipoPersonalCodigo = $cargo->CARGO_TIPO ?? null;
        $tipoPersonalDescripcion = null;

        if ($tipoPersonalCodigo) {
            $tipo = TipoPersonal::where('TIPE_CODIGO', $tipoPersonalCodigo)->first();
            $tipoPersonalDescripcion = $tipo?->TIPE_DESCRIPCION ?? null;
        }

        // Guarda con nombre hash único (recomendado). Si prefieres nombres fijos, usa putFileAs.
        $cvPath  = $request->file('cv')->store($folder, $disk);
        $culPath = $request->file('cul')->store($folder, $disk);

        // Guardamos solo las rutas relativas en la tabla
        $validated['cv']   = $cvPath;
        $validated['cul']  = $culPath;

        // Campos para rastrear origen y creador (ya agregados a tu tabla)
        $validated['origin']     = 'external';
        $validated['created_by'] = auth()->id() ?? null;      // <-- por si no hay login
        //$validated['created_by'] = auth()->id();

        // agregar tipo_personal al array que se guardará
        $validated['tipo_personal_codigo'] = $tipoPersonalCodigo;
        $validated['tipo_personal']        = $tipoPersonalDescripcion;

        /* ---------- 3) INSERTAR EN TRANSACCIÓN ---------- 
        try {
            $postulante = DB::transaction(function () use ($validated) {
                return Postulante::create($validated); // exige fillable en el modelo
            });
        } catch (\Throwable $e) {
            Log::error('Error al guardar postulante', [
                'error' => $e->getMessage(),
                'dni'   => $dni,
            ]);

            // Si falló la DB y no quieres dejar archivos “huérfanos”, intenta borrarlos
            if (isset($cvPath))  Storage::disk($disk)->delete($cvPath);
            if (isset($culPath)) Storage::disk($disk)->delete($culPath);

            return back()->withInput()->withErrors([
                'general' => 'Ocurrió un problema al guardar la postulación. Intenta de nuevo.'
            ]);
        }

        /* ---------- 4) OK ---------- 
        Log::info('Postulante guardado (interno)', ['id' => $postulante->id, 'dni' => $dni]);

        return back()->with('success', 'Información guardada');
    }

    */

    public function storeExterno(Request $request)
    {
        // 1) Inferir si el cargo es OPERATIVO ('01') con una sola consulta
        $esOperativo = (string) Cargo::where('CODI_CARG', $request->input('cargo'))
            ->value('CARGO_TIPO') === '01';

        // 2) Validación compacta (lo operativo se vuelve obligatorio solo si corresponde)
        $validated = $request->validate([
            'fecha_postula'      => ['required', 'date'],
            'dni'                => ['required', 'string', 'size:8', 'unique:postulantes,dni'],
            'nombres'            => ['required', 'string', 'max:50'],
            'apellidos'          => ['required', 'string', 'max:50'],
            'fecha_nacimiento'   => ['required', 'date'],
            'edad'               => ['required', 'integer', 'min:18', 'max:120'],
            'departamento'       => ['required', 'string', 'max:50'],
            'provincia'          => ['required', 'string', 'max:50'],
            'distrito'           => ['required', 'string', 'max:50'],
            'nacionalidad'       => ['required', 'string', 'max:50'],
            'grado_instruccion'  => ['required', 'string', 'max:50'],
            'celular'            => ['required', 'digits:9'],
            'celular_referencia' => ['nullable', 'digits:9'],
            'tipo_cargo'         => ['required', 'string', 'max:50'],
            'cargo'              => ['required', 'exists:cargos,CODI_CARG'],
            'experiencia_rubro'  => ['required', 'string', 'max:50'],

            // === Solo obligatorios si $esOperativo ===
            'sucamec'           => [Rule::requiredIf($esOperativo), 'nullable', 'in:SI,NO'],
            'carne_sucamec'     => [Rule::requiredIf($esOperativo), 'nullable', 'in:SI,NO'],
            'licencia_arma'     => [Rule::requiredIf($esOperativo), 'nullable', 'string', 'max:10'],
            'licencia_conducir' => [Rule::requiredIf($esOperativo), 'nullable', 'string', 'max:10'],

            // Archivos
            'cv'  => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'cul' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'exists'   => 'El valor seleccionado en :attribute no es válido.',
            'in'       => 'Selecciona una opción válida en :attribute.',
            'mimes'    => 'El archivo :attribute debe ser PDF.',
        ], [
            'carne_sucamec'     => 'carné SUCAMEC vigente',
            'sucamec'           => 'curso SUCAMEC vigente',
            'licencia_arma'     => 'licencia de arma',
            'licencia_conducir' => 'licencia de conducir',
            'experiencia_rubro' => 'experiencia en el rubro',
            'cargo'             => 'cargo solicitado',
            'tipo_cargo'        => 'tipo de cargo',
            'cv'                => 'Currículum (PDF)',
            'cul'               => 'Certificado Único Laboral (PDF)',
        ]);

        // 3) Si NO es operativo, limpia estos campos antes de grabar (2 líneas)
        if (!$esOperativo) {
            $validated['sucamec'] = $validated['carne_sucamec'] = $validated['licencia_arma'] = $validated['licencia_conducir'] = null;
        }

        // 4) Subida de archivos y guardado (igual que ya tenías)
        $dni  = $validated['dni'];
        $disk = config('filesystems.default', 'local');
        $dir  = "postulantes/{$dni}";
        $validated['cv']  = $request->file('cv')->store($dir, $disk);
        $validated['cul'] = $request->file('cul')->store($dir, $disk);

        // (opcional) guardar tipo_personal según el cargo por si lo necesitas
        $cargoTipo = \App\Models\Cargo::where('CODI_CARG', $validated['cargo'])->value('CARGO_TIPO');
        $validated['tipo_personal_codigo'] = $cargoTipo;
        $validated['tipo_personal']        = ($cargoTipo === '01') ? 'OPERATIVO' : 'ADMINISTRATIVO';

        $validated['origin']     = 'external';
        $validated['created_by'] = auth()->id();

        DB::transaction(fn() => \App\Models\Postulante::create($validated));

        return back()->with('success', 'Información guardada');
    }



    /* ---------- FUNCION PARA GUARDAR UN REGISTRO INTERNO
    POR EL USUARIO DEL SISTEMA ---------- 
    public function storeInterno(Request $request)
    {
        /* ---------- 1. VALIDAR ---------- 
        $validated = $request->validate([
            'fecha_postula'      => 'required|date',
            'dni'                => 'required|string|size:8|unique:postulantes,dni',
            'nombres'            => 'required|string|max:50',
            'apellidos'          => 'required|string|max:50',
            'fecha_nacimiento'   => 'required|date',
            'edad'               => 'required|integer|min:18|max:120',
            'departamento'       => 'required|string|max:50',
            'provincia'          => 'required|string|max:50',
            'distrito'           => 'required|string|max:50',
            'nacionalidad'       => 'required|string|max:50',
            'grado_instruccion'  => 'required|string|max:50',
            'celular'            => 'required|digits:9',
            'celular_referencia' => 'nullable|digits:9',
            'tipo_cargo'         => 'required|string|max:50',
            'cargo'              => 'required|string|max:50',
            'experiencia_rubro'  => 'required|string|max:50',
            'sucamec'            => 'required|string|max:10',
            'carne_sucamec'      => 'required|string|max:10',
            'licencia_arma'      => 'required|string|max:10',
            'licencia_conducir'  => 'required|string|max:10',
            'servicio_militar'   => 'nullable|string|max:15',
            'estado_civil'       => 'nullable|string|max:15',

            // Archivos  (máx. 5 MB — ya los validas en el front)
            'cv'  => 'required|file|max:5120',
            'cul' => 'required|file|max:5120',

            // Checkbox términos
            //'terms' => 'accepted',
        ]);

        /* ---------- 2) SUBIR ARCHIVOS AL DISCO ---------- 
        $dni    = $validated['dni'];
        $disk   = config('filesystems.default', 'local');  // 'local' (privado) o 'public'
        $folder = "postulantes/{$dni}";                    // carpeta por postulante

        /* ---------- 1b. Validación adicional: cargo existe y coincide con tipo_cargo ----------
        $cargo = Cargo::where('CODI_CARG', $validated['cargo'])->first();
        if (! $cargo) {
            return back()->withInput()->withErrors(['cargo' => 'El cargo seleccionado no existe.']);
        }

        // Verificamos que el cargo pertenece al tipo seleccionado (si almacenaste tipo_cargo en el formulario)
        if ((string) $cargo->TIPO_CARG !== (string) $validated['tipo_cargo']) {
            return back()->withInput()->withErrors(['cargo' => 'El cargo seleccionado no corresponde al tipo de cargo elegido.']);
        }

        /* ---------- 2) Determinar tipo de personal a partir del cargo ---------- 
        // CARGO_TIPO es la columna que, según muestras, relaciona con ADMI_TIPO_PERSONAL.TIPE_CODIGO
        $tipoPersonalCodigo = $cargo->CARGO_TIPO ?? null;
        $tipoPersonalDescripcion = null;

        if ($tipoPersonalCodigo) {
            $tipo = TipoPersonal::where('TIPE_CODIGO', $tipoPersonalCodigo)->first();
            $tipoPersonalDescripcion = $tipo?->TIPE_DESCRIPCION ?? null;
        }

        // Guarda con nombre hash único (recomendado). Si prefieres nombres fijos, usa putFileAs.
        $cvPath  = $request->file('cv')->store($folder, $disk);
        $culPath = $request->file('cul')->store($folder, $disk);

        // Guardamos solo las rutas relativas en la tabla
        $validated['cv']   = $cvPath;
        $validated['cul']  = $culPath;

        // Campos para rastrear origen y creador (ya agregados a tu tabla)
        $validated['origin']     = 'internal';
        $validated['created_by'] = auth()->id();

        // agregar tipo_personal al array que se guardará
        $validated['tipo_personal_codigo'] = $tipoPersonalCodigo;
        $validated['tipo_personal']        = $tipoPersonalDescripcion;

        /* ---------- 3) INSERTAR EN TRANSACCIÓN ---------- 
        try {
            $postulante = DB::transaction(function () use ($validated) {
                return Postulante::create($validated); // exige fillable en el modelo
            });
        } catch (\Throwable $e) {
            Log::error('Error al guardar postulante', [
                'error' => $e->getMessage(),
                'dni'   => $dni,
            ]);

            // Si falló la DB y no quieres dejar archivos “huérfanos”, intenta borrarlos
            if (isset($cvPath))  Storage::disk($disk)->delete($cvPath);
            if (isset($culPath)) Storage::disk($disk)->delete($culPath);

            return back()->withInput()->withErrors([
                'general' => 'Ocurrió un problema al guardar la postulación. Intenta de nuevo.'
            ]);
        }

        /* ---------- 4) OK ---------- 
        Log::info('Postulante guardado (interno)', ['id' => $postulante->id, 'dni' => $dni]);

        return back()->with('success', 'Información guardada');
    }
    */



    /* ---------- GUARDAR POSTULANTE INTERNO ---------- */
    public function storeInterno(Request $request)
    {
        // 0) Fuente de verdad: el cargo y su tipo desde BD
        $cargo = \App\Models\Cargo::where('CODI_CARG', $request->input('cargo'))->first();
        if (!$cargo) {
            return back()->withInput()->withErrors(['cargo' => 'El cargo seleccionado no existe.']);
        }

        // TIPO_CARG: '01' = Operativo, '02' = Administrativo
        $tipoCodigo = str_pad((string)$cargo->TIPO_CARG, 2, '0', STR_PAD_LEFT);
        $esOperativo = ($tipoCodigo === '01');

        // 1) Validación (NO confiamos en tipo_cargo del form)
        $validated = $request->validate([
            'fecha_postula'      => ['required', 'date'],
            'dni'                => ['required', 'string', 'size:8', 'unique:postulantes,dni'],
            'nombres'            => ['required', 'string', 'max:50'],
            'apellidos'          => ['required', 'string', 'max:50'],
            'fecha_nacimiento'   => ['required', 'date'],
            'edad'               => ['required', 'integer', 'min:18', 'max:120'],
            'departamento'       => ['required', 'string', 'max:50'],
            'provincia'          => ['required', 'string', 'max:50'],
            'distrito'           => ['required', 'string', 'max:50'],
            'nacionalidad'       => ['required', 'string', 'max:50'],
            'grado_instruccion'  => ['required', 'string', 'max:50'],
            'celular'            => ['required', 'digits:9'],
            'celular_referencia' => ['nullable', 'digits:9'],
            'cargo'              => ['required', 'exists:CARGOS,CODI_CARG'],
            'experiencia_rubro'  => ['required', 'string', 'max:50'],

            // Solo obligatorios si es Operativo
            'sucamec'           => [Rule::requiredIf($esOperativo), 'nullable', 'in:SI,NO'],
            'carne_sucamec'     => [Rule::requiredIf($esOperativo), 'nullable', 'in:SI,NO'],
            'licencia_arma'     => [Rule::requiredIf($esOperativo), 'nullable', 'string', 'max:10'],
            'licencia_conducir' => [Rule::requiredIf($esOperativo), 'nullable', 'string', 'max:10'],

            // Archivos
            'cv'  => ['required', 'file', 'mimes:pdf', 'max:5120'],
            'cul' => ['required', 'file', 'mimes:pdf', 'max:5120'],

            'servicio_militar'  => ['nullable', 'string', 'max:15'],
            'estado_civil'      => ['nullable', 'string', 'max:15'],
            // 'tipo_cargo'      => ['sometimes'], // si lo envías, NO se usa
        ], [
            'required' => 'El campo :attribute es obligatorio.',
            'exists'   => 'El valor seleccionado en :attribute no es válido.',
            'in'       => 'Selecciona una opción válida en :attribute.',
            'mimes'    => 'El archivo :attribute debe ser PDF.',
        ], [
            'carne_sucamec'     => 'carné SUCAMEC vigente',
            'sucamec'           => 'curso SUCAMEC vigente',
            'licencia_arma'     => 'licencia de arma',
            'licencia_conducir' => 'licencia de conducir',
            'experiencia_rubro' => 'experiencia en el rubro',
            'cargo'             => 'cargo solicitado',
            'cv'                => 'Currículum (PDF)',
            'cul'               => 'Certificado Único Laboral (PDF)',
        ]);

        // 2) Normalizar campos según tipo real
        if (!$esOperativo) {
            // si tus columnas son NOT NULL, evita NULL con 'NO'
            foreach (['sucamec', 'carne_sucamec', 'licencia_arma', 'licencia_conducir'] as $k) {
                $validated[$k] = $validated[$k] ?? 'NO';
            }
        }

        // 3) Subir archivos
        $dni  = $validated['dni'];
        $disk = config('filesystems.default', 'local');
        $dir  = "postulantes/{$dni}";
        $validated['cv']  = $request->file('cv')->store($dir, $disk);
        $validated['cul'] = $request->file('cul')->store($dir, $disk);

        // 4) Sobrescribir tipo en el payload con lo de BD (ignorar form)
        $validated['tipo_cargo']           = $tipoCodigo; // guarda '01'/'02' si tu columna lo espera
        $validated['tipo_personal_codigo'] = $tipoCodigo;
        $validated['tipo_personal']        = $cargo->tipo->DESC_TIPO_CARG
            ?? ($esOperativo ? 'OPERATIVO' : 'ADMINISTRATIVO');
        $validated['origin']               = 'internal';
        $validated['created_by']           = auth()->id() ?? 0;

        // 5) Guardar en transacción
        try {
            DB::transaction(fn() => \App\Models\Postulante::create($validated));
        } catch (\Throwable $e) {
            // limpiar archivos si falla
            if (!empty($validated['cv']))  Storage::disk($disk)->delete($validated['cv']);
            if (!empty($validated['cul'])) Storage::disk($disk)->delete($validated['cul']);
            Log::error('Error al guardar postulante (interno)', ['dni' => $dni, 'err' => $e->getMessage()]);
            return back()->withInput()->withErrors(['general' => 'Ocurrió un problema al guardar la postulación. Intenta de nuevo.']);
        }

        Log::info('Postulante guardado (interno)', ['dni' => $dni, 'tipo' => $tipoCodigo]);
        return back()->with('success', 'Información guardada');
    }



    /* ---------- FUNCION PARA VERIFICAR SI UN POSTULANTE ESTUVO O NO
    EN LA LISTA NEGRA ---------- */

    public function verificarListaNegra($dni)
    {
        try {
            $listaNegra = collect(DB::select(
                'EXEC SP_PERSONAL_CESADO @dni = :dni, @nombre = :nombre',
                [
                    'dni' => $dni,
                    'nombre' => null
                ]
            ));

            if ($listaNegra->isNotEmpty()) {
                // Si el SP devuelve campos como "motivo", puedes mostrarlo
                $mensaje = 'Este postulante está en la lista negra.';
                if (isset($listaNegra[0]->motivo)) {
                    $mensaje .= ' Motivo: ' . $listaNegra[0]->motivo;
                }
                return response()->json(['enListaNegra' => true, 'mensaje' => $mensaje]);
            } else {
                return response()->json(['enListaNegra' => false]);
            }
        } catch (\Exception $e) {
            // Responde el error para debug rápido
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /* ---------- FUNCION PARA ABRIR Y MOSTRAR LA VISTA DE EDICION ---------- 
    public function edit(Postulante $postulante)
    {
        // resources/views/postulantes/partials/form-edit.blade.php
        return view('postulantes.partials.form-edit', compact('postulante'));
    }
    /*


    /* ---------- FUNCION PARA ACTUALIZAR LOS DATOS DE UN POSTULANTE---------- 

    public function update(Request $request, Postulante $postulante)
    {
        // 1. Validar sólo los campos editables
        $data = $request->validate([
            'nombres'             => 'required|string|max:50',
            'apellidos'           => 'required|string|max:50',
            'dni'                 => ['required', 'string', 'size:8', Rule::unique('postulantes', 'dni')->ignore($postulante->id),],
            'edad'                => 'required|integer|min:18|max:120',
            'departamento'        => 'required|string|max:50',
            'provincia'           => 'required|string|max:50',
            'distrito'            => 'required|string|max:50',
            'celular'             => 'required|string|max:50',
            'celular_referencia'  => 'nullable|digits:9',
            'estado_civil'        => 'required|string|max:50',
            'nacionalidad'        => 'required|string|max:50',
            'tipo_cargo'        => 'required|string|max:50',
            'cargo'               => 'required|string|max:50',
            'fecha_postula'       => 'required|date',
            'experiencia_rubro'   => 'required|string|max:50',
            'sucamec'             => 'required|string|max:10',
            'grado_instruccion'   => 'required|string|max:50',
            'servicio_militar'    => 'required|string|max:15',
            'licencia_arma'       => 'required|string|max:10',
            'licencia_conducir'   => 'required|string|max:10',

            // si permites cambiar archivos, validalos y guárdalos aquí
        ]);

        // 2. Actualizar
        try {
            $postulante->update($data);
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar postulante', [
                'id'    => $postulante->id,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar. Intenta de nuevo.'
            ], 500);
        }
    }
        */

    /* ---------- FUNCION PARA ABRIR Y MOSTRAR LA VISTA DE EDICION (PARA MODAL) ---------- */
    public function edit(Postulante $postulante)
    {
        // Mapas simples (código => texto)
        $departamentos = Departamento::forSelect();   // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $tipoCargos    = TipoCargo::forSelect();      // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        // Cargos vigentes (si ya tiene tipo_cargo, los filtramos para el select)
        $cargos = Cargo::vigentes()
            ->when($postulante->tipo_cargo, fn($q) => $q->porTipo($postulante->tipo_cargo))
            ->get(['CODI_CARG', 'DESC_CARGO', 'TIPO_CARG'])
            ->keyBy('CODI_CARG');

        // Normalizador para códigos numéricos (rellena con ceros)
        $pad = function ($val, int $len) {
            $v = preg_replace('/\D+/', '', (string)$val);
            return $v === '' ? null : str_pad($v, $len, '0', STR_PAD_LEFT);
        };

        // Provincias y distritos completos (el front filtrará por dependencias)
        $provincias = Provincia::query()
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->orderBy('PROVI_DESCRIPCION')
            ->get()
            ->map(function ($p) use ($pad) {
                $p->PROVI_CODIGO = $pad($p->PROVI_CODIGO, 4);
                $p->DEPA_CODIGO  = $pad($p->DEPA_CODIGO,  2);
                return $p;
            })
            ->keyBy('PROVI_CODIGO');

        $distritos = Distrito::query()
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->orderBy('DIST_DESCRIPCION')
            ->get()
            ->map(function ($d) use ($pad) {
                $d->DIST_CODIGO  = $pad($d->DIST_CODIGO,  6);
                $d->PROVI_CODIGO = $pad($d->PROVI_CODIGO, 4);
                return $d;
            })
            ->keyBy('DIST_CODIGO');

        // IMPORTANTe: devolvemos también los catálogos que el partial usa
        return view('postulantes.partials.form-edit', compact(
            'postulante',
            'departamentos',
            'tipoCargos',
            'cargos',
            'provincias',
            'distritos'
        ));
    }

    /* ---------- FUNCION PARA ACTUALIZAR LOS DATOS DE UN POSTULANTE (AJAX) ---------- */
    public function update(Request $request, Postulante $postulante)
    {
        // Normalizador: solo dígitos + padding
        $pad = function ($val, int $len) {
            $v = preg_replace('/\D+/', '', (string)$val);
            return $v === '' ? null : str_pad($v, $len, '0', STR_PAD_LEFT);
        };

        // VALIDACIÓN (DNI ya NO se edita)
        $data = $request->validate([
            'nombres'           => ['required', 'string', 'max:50'],
            'apellidos'         => ['required', 'string', 'max:50'],
            'edad'              => ['required', 'integer', 'min:18', 'max:120'],

            // geo (se normalizan abajo)
            'departamento'      => ['required', 'string', 'max:6'],
            'provincia'         => ['required', 'string', 'max:6'],
            'distrito'          => ['required', 'string', 'max:6'],

            'celular'           => ['required', 'digits:9'],
            // quitados: celular_referencia, estado_civil

            'nacionalidad'      => ['required', 'string', 'max:50'],

            // tipo + cargo (checamos consistencia abajo)
            'tipo_cargo'        => ['required', 'string', 'max:2'],
            'cargo'             => ['required', 'exists:CARGOS,CODI_CARG'],

            'fecha_postula'     => ['required', 'date'],
            'experiencia_rubro' => ['required', 'string', 'max:50'],

            'sucamec'           => ['required', 'string', 'max:10'],
            'grado_instruccion' => ['required', 'string', 'max:50'],
            'servicio_militar'  => ['nullable', 'string', 'max:15'],
            'licencia_arma'     => ['required', 'string', 'max:10'],
            'licencia_conducir' => ['required', 'string', 'max:10'],
        ]);

        // Normalizaciones (coincidir con el almacenamiento)
        $data['departamento'] = $pad($data['departamento'], 2);
        $data['provincia']    = $pad($data['provincia'], 4);
        $data['distrito']     = $pad($data['distrito'], 6);
        $data['tipo_cargo']   = $pad($data['tipo_cargo'], 2);

        // Coherencia cargo ↔ tipo_cargo
        $cargoTipo = Cargo::where('CODI_CARG', $data['cargo'])->value('TIPO_CARG'); // '01'/'02'
        if ($cargoTipo && $data['tipo_cargo'] !== str_pad((string)$cargoTipo, 2, '0', STR_PAD_LEFT)) {
            return response()->json([
                'success' => false,
                'message' => 'El cargo seleccionado no corresponde al tipo de cargo elegido.',
                'errors'  => ['cargo' => ['El cargo no pertenece al tipo seleccionado.']]
            ], 422);
        }

        try {
            // DNI no se toca
            unset($data['dni']);
            $postulante->update($data);
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al actualizar postulante', [
                'id' => $postulante->id,
                'error' => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo actualizar. Intenta de nuevo.'
            ], 500);
        }
    }



    /* ---------- FUNCION PARA ELIMINAR UN POSTULANTE ---------- */
    public function destroy(Postulante $postulante)
    {
        try {
            $postulante->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $e) {
            Log::error('Error al eliminar postulante', [
                'error' => $e->getMessage(),
                'id'    => $postulante->id,
            ]);
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar. Intenta de nuevo.'
            ], 500);
        }
    }


    /* ---------- FUNCION PARA DESCARGAR UN ARCHIVO : CUL O CV ---------- */

    public function descargarArchivo($id, $tipo)
    {
        $postulante = Postulante::findOrFail($id);

        // Solo permitir 'cv' o 'cul'
        if (!in_array($tipo, ['cv', 'cul'])) {
            abort(404, 'Tipo de archivo no válido');
        }

        $archivo = $tipo === 'cv' ? $postulante->cv : $postulante->cul;

        // Asegúrate que la ruta no tenga '/' al principio
        $ruta = '\\\\192.168.10.5\\sicos\\RECLUSOL\\DOCUMENTOS\\POSTULANTES\\' . $archivo;

        // Verifica si el archivo existe
        if (!file_exists($ruta)) {
            abort(404, 'Archivo no encontrado');
        }

        // Descargar el archivo
        return Response::download($ruta, $tipo . '_' . $postulante->dni . '.pdf', [
            'Content-Type' => 'application/pdf',
        ]);
    }


    /* ---------- FUNCION PARA ABRIR Y MOSTRAR LA VISTA ---------- */
    public function ver()
    {
        return view('postulantes.selection');
    }


    /* ---------- FUNCION PARA ABRIR Y MOSTRAR LA VISTA ---------- */

    public function formExterno()
    {
        // Usar forSelect() cuando el modelo lo provee
        $departamentos = Departamento::forSelect();   // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $tipoCargos    = TipoCargo::forSelect();      // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        // Inicialmente dejamos cargos vacío (se llenará por AJAX según tipo seleccionado)
        $cargos = collect();

        // Provincias / distritos: si no vas a precargar, déjalos vacíos y los pides por AJAX
        $provincias = collect();
        $distritos  = collect();

        return view('postulantes.registroExterno', compact(
            'departamentos',
            'provincias',
            'distritos',
            'cargos',
            'tipoCargos',
            //'nivelEducativo',
            //'estadoCivil'
        ));
    }

    public function formInterno()
    {

        // Usar forSelect() cuando el modelo lo provee
        $departamentos = Departamento::forSelect();   // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $tipoCargos    = TipoCargo::forSelect();      // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        // Inicialmente dejamos cargos vacío (se llenará por AJAX según tipo seleccionado)
        $cargos = collect();

        // Provincias / distritos: si no vas a precargar, déjalos vacíos y los pides por AJAX
        $provincias = collect();
        $distritos  = collect();


        return view('postulantes.registroInterno', compact(
            'departamentos',
            'provincias',
            'distritos',
            'cargos',
            'tipoCargos',
            //'nivelEducativo',
            //'estadoCivil'
        ));
    }

    public function getCargosPorTipo($tipoCodigo)
    {
        // devuelve array de objetos [{CODI_CARG, DESC_CARGO}, ...]
        $cargos = Cargo::vigentes()->porTipo($tipoCodigo)->get(['CODI_CARG', 'DESC_CARGO']);
        return response()->json($cargos);
    }

    // provincias por departamento
    public function getProvincias($depa)
    {
        return response()->json(Provincia::forSelectByDepartamento($depa)->map(function ($v, $k) {
            return ['PROVI_CODIGO' => $k, 'PROVI_DESCRIPCION' => $v];
        })->values());
    }

    // distritos por provincia
    public function getDistritos($prov)
    {
        return response()->json(Distrito::forSelectByProvincia($prov)->map(function ($v, $k) {
            return ['DIST_CODIGO' => $k, 'DIST_DESCRIPCION' => $v];
        })->values());
    }


    /* -- 
    public function filtrar(Request $request)
    {
        //$query = Requerimiento::query();
        //$query = Postulante::with('estado');
        $query = Postulante::query();

        // Filtros dinámicos
        //if ($request->filled('sucursal')) {
        //  $query->where('sucursal', $request->sucursal);
        //}

        //if ($request->filled('cliente')) {
        //  $query->where('cliente', $request->cliente);
        //}

        // if ($request->filled('area_solicitante')) {
        //   $query->where('area_solicitante', $request->area_solicitante);
        //}

        if ($request->filled('tipo_cargo')) {
            $query->where('tipo_cargo', $request->tipo_cargo);
        }

        if ($request->filled('cargo')) {
            $query->where('cargo', $request->cargo);
        }


        if ($request->filled('departamento')) {
            $query->where('departamento', $request->departamento);
        }
        if ($request->filled('provincia')) {
            $query->where('provincia', $request->provincia);
        }
        if ($request->filled('distrito')) {
            $query->where('distrito', $request->distrito);
        }


        // Ordenar
        $query->orderBy('created_at', 'desc');

        // Paginación
        $postulantes = $query->paginate(15)->withQueryString();

        /*-------------------------------------------------
        |   Mapeo de nombres legibles
        -------------------------------------------------
        // Cargar catálogos

        // Usar forSelect() cuando el modelo lo provee
        $departamentos = Departamento::forSelect();   // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $tipoCargos    = TipoCargo::forSelect();      // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        // Inicialmente dejamos cargos vacío (se llenará por AJAX según tipo seleccionado)
        $cargos = Cargo::forSelect();

        // Provincias / distritos: si no vas a precargar, déjalos vacíos y los pides por AJAX
        $provincias = Provincia::forSelect();
        $distritos  = Distrito::forSelect();


        // Mapear nombres legibles con str_pad
        foreach ($postulantes as $r) {
            // Convertir a string sin ceros a la izquierda
            $codigoTipoCargo = (string) $r->tipo_cargo;
            $codigoCargo = (string)$r->cargo;
            $codigoSucursal = (string)$r->sucursal;
            $codigoDepartamento = (string)$r->departamento;
            $codigoProvincia = (string)$r->provincia;
            $codigoDistrito = (string)$r->distrito;
            //$codigoNivel = (string)$r->grado_instruccion;
            //$codigoEstado = (string)$r->estado_civil;

            // Buscar normalizado
            $r->tipo_cargo_nombre = $tipoCargos->get($codigoTipoCargo)?->DESC_TIPO_CARG ?? $r->tipo_cargo;
            $r->cargo_nombre = $cargos->get($codigoCargo)?->DESC_CARGO ?? $r->cargo;
            //$r->sucursal_nombre = $sucursales->get($codigoSucursal)?->SUCU_DESCRIPCION ?? $r->sucursal;
            $r->departamento_nombre = $departamentos->get($codigoDepartamento)?->DEPA_DESCRIPCION ?? $r->departamento;
            $r->provincia_nombre = $provincias->get($codigoProvincia)?->PROVI_DESCRIPCION ?? $r->provincia;
            $r->distrito_nombre = $distritos->get($codigoDistrito)?->DIST_DESCRIPCION ?? $r->distrito;
            //$r->nivel_nombre = $nivelEducativo->get($codigoNivel)?->NIED_DESCRIPCION ?? $r->grado_instruccion;
            //$r->estado_nombre = $estadoCivil->get($codigoEstado)?->ESCI_DESCRIPCION ?? $r->estado_civil;
        }

        // Contar total general
        // $requerimientosProcesos = Postulante::where('estado', 1)->count();

        // Contar cubiertos
        //$requerimientosCubiertos = Postulante::where('estado', 2)->count(); // suponiendo que estado 2 = Cubierto

        // Contar cancelados
        // $requerimientosCancelados = Postulante::where('estado', 3)->count(); // estado 3 = Cancelado

        // Contar vencidos
        //$requerimientosVencidos = Postulante::where('estado', 4)->count(); // estado 4 = Vencido


        return view('postulantes.filtrar', compact(
            'postulantes',
            'tipoCargos',
            'cargos',
            //'sucursales',
            'departamentos',
            'provincias',
            'distritos',
            //'nivelEducativo',
            //'estadoCivil'
            //'requerimientosProcesos',
            //'requerimientosCubiertos',
            //'requerimientosCancelados',
            //'requerimientosVencidos'
        ));
    }*/


    /*
    public function filtrar(Request $request)
    {
        $query = Postulante::query();

        // --- Normalizar códigos (padding) según longitudes típicas en tu BD ---
        $norm = function (?string $val, int $len = null) {
            if ($val === null || $val === '') return null;
            $val = (string) $val;
            // si tiene caracteres no numéricos, devolver tal cual (por si usas códigos alfanuméricos)
            if (!ctype_digit($val) || $len === null) return $val;
            return str_pad($val, $len, '0', STR_PAD_LEFT);
        };

        $tipoCargo = $request->filled('tipo_cargo') ? $request->input('tipo_cargo') : null;
        $cargo     = $request->filled('cargo') ? $request->input('cargo') : null;
        $depa      = $norm($request->input('departamento'), 2);   // ej. "02"
        $provi     = $norm($request->input('provincia'), 4);      // ej. "0218"
        $distr     = $norm($request->input('distrito'), 6);       // ej. "021801"

        // --- Filtros dinámicos (más legible con when) ---
        $query->when($tipoCargo, fn($q) => $q->where('tipo_cargo', $tipoCargo));
        $query->when($cargo,     fn($q) => $q->where('cargo', $cargo));
        $query->when($depa,      fn($q) => $q->where('departamento', $depa));
        $query->when($provi,     fn($q) => $q->where('provincia', $provi));
        $query->when($distr,     fn($q) => $q->where('distrito', $distr));

        // Orden
        $query->orderBy('created_at', 'desc');

        // Paginación (mantiene query string con withQueryString)
        $postulantes = $query->paginate(15)->withQueryString();

        // -------------------------------------------------
        // Catálogos y listas del formulario (carga condicional)
        // -------------------------------------------------
        $departamentos = Departamento::forSelect(); // siempre útil
        $tipoCargos    = TipoCargo::forSelect();    // siempre útil

        // CARGOS: si hay tipo_cargo seleccionado, traer solo los cargos de ese tipo (útil para poblar select)
        if ($tipoCargo) {
            $cargos = Cargo::where('TIPO_CARG', $tipoCargo)->forSelect();
        } else {
            // si prefieres dejar vacío para cargar por AJAX desde el front:
            $cargos = collect(); // vacío
            // o, si la lista es pequeña y quieres mostrarla completa:
            // $cargos = Cargo::forSelect();
        }

        // PROVINCIAS / DISTRITOS: cargar solo si el usuario filtró departamento/provincia
        if ($depa) {
            $provincias = Provincia::where('DEPA_CODIGO', $depa)->forSelect();
        } else {
            // si quieres precargar todo (no recomendado si hay muchas filas):
            $provincias = collect();
        }

        if ($provi) {
            $distritos = Distrito::where('PROVI_CODIGO', $provi)->forSelect();
        } else {
            $distritos = collect();
        }

        // -------------------------------------------------
        // Mapear nombres legibles (tolerante a diversos formatos de forSelect)
        // -------------------------------------------------
        // Helper inline para recuperar texto desde colecciones tipo [codigo => 'texto']
        $label = function ($collection, $code, $fieldName = null) {
            if (empty($code) || !$collection) return $code;
            // Si la colección es un array/Collection de strings (value directo)
            if ($collection->has($code)) {
                $val = $collection->get($code);
                if (is_object($val)) {
                    // si devuelve objeto, intentar el campo dado o el primer property útil
                    if ($fieldName && isset($val->{$fieldName})) return $val->{$fieldName};
                    // fallback: convertir a string si posible
                    return (string) $val;
                }
                return $val;
            }
            // fallback: intentar acceso por índice directo (array)
            if (is_array($collection) && array_key_exists($code, $collection)) {
                $val = $collection[$code];
                return is_object($val) ? ($fieldName && isset($val->{$fieldName}) ? $val->{$fieldName} : (string)$val) : $val;
            }
            return $code;
        };

        foreach ($postulantes as $r) {
            // convertimos a string por si vienen con ceros a la izquierda en BD
            $codigoTipoCargo = (string) ($r->tipo_cargo ?? '');
            $codigoCargo     = (string) ($r->cargo ?? '');
            $codigoDepartamento = (string) ($r->departamento ?? '');
            $codigoProvincia = (string) ($r->provincia ?? '');
            $codigoDistrito  = (string) ($r->distrito ?? '');

            // Intentar obtener nombre legible; forSelect puede devolver string o objeto
            $r->tipo_cargo_nombre = $label($tipoCargos, $codigoTipoCargo, 'DESC_TIPO_CARG') ?? $r->tipo_cargo;
            $r->cargo_nombre      = $label($cargos, $codigoCargo, 'DESC_CARGO') ?? $r->cargo;
            $r->departamento_nombre = $label($departamentos, $codigoDepartamento, 'DEPA_DESCRIPCION') ?? $r->departamento;
            $r->provincia_nombre  = $label($provincias, $codigoProvincia, 'PROVI_DESCRIPCION') ?? $r->provincia;
            $r->distrito_nombre   = $label($distritos, $codigoDistrito, 'DIST_DESCRIPCION') ?? $r->distrito;
        }

        return view('postulantes.filtrar', compact(
            'postulantes',
            'tipoCargos',
            'cargos',
            'departamentos',
            'provincias',
            'distritos'
        ));
    }
    */
    /* ---------- FUNCION PARA FILTRAR ---------- */
    public function filtrar(Request $request)
    {
        $query = Postulante::query();

        // Normalizadores
        $norm = function (?string $val, int $len = null) {
            if ($val === null || $val === '') return null;
            $val = (string) $val;
            if (!ctype_digit($val) || $len === null) return $val;
            return str_pad($val, $len, '0', STR_PAD_LEFT);
        };
        // Solo dígitos + pad (a prueba de espacios, NBSP, guiones)
        $normDigits = function (?string $val, int $len) {
            $raw = preg_replace('/\D+/', '', (string)($val ?? ''));
            if ($raw === '') return null;
            return str_pad($raw, $len, '0', STR_PAD_LEFT);
        };

        // Inputs
        $dni       = trim((string)$request->input('dni', ''));
        $nombre    = trim((string)$request->input('nombre', ''));
        $tipoCargo = $request->filled('tipo_cargo') ? (string)$request->input('tipo_cargo') : null;
        $cargo     = $request->filled('cargo')      ? (string)$request->input('cargo')      : null;
        $depa      = $norm($request->input('departamento'), 2);
        $provi     = $norm($request->input('provincia'),     4);
        $distr     = $norm($request->input('distrito'),      6);

        // Filtros
        $query->when($dni !== '', fn($q) => $q->where('dni', 'like', "%{$dni}%"));
        $query->when($nombre !== '', function ($q) use ($nombre) {
            $terms = preg_split('/\s+/', $nombre, -1, PREG_SPLIT_NO_EMPTY);
            foreach ($terms as $t) {
                $like = "%{$t}%";
                $q->where(function ($qq) use ($like) {
                    $qq->where('nombres', 'like', $like)
                        ->orWhere('apellidos', 'like', $like);
                });
            }
        });
        $query->when($tipoCargo, fn($q) => $q->where('tipo_cargo', $tipoCargo));
        $query->when($cargo,     fn($q) => $q->where('cargo', $cargo));
        $query->when($depa,      fn($q) => $q->where('departamento', $depa));
        $query->when($provi,     fn($q) => $q->where('provincia', $provi));
        $query->when($distr,     fn($q) => $q->where('distrito', $distr));

        // Stats con mismos filtros
        $base = clone $query;
        $stats = [
            'total'    => (clone $base)->count(),
            'aptos'    => (clone $base)->where('decision', 'apto')->count(),
            'no_aptos' => (clone $base)->where('decision', 'no_apto')->count(),
            // Alternativa por 'estado':
            // 'aptos'    => (clone $base)->where('estado', 2)->count(),
            // 'no_aptos' => (clone $base)->where('estado', 3)->count(),
        ];

        // Orden + paginación
        $query->orderBy('created_at', 'desc');
        $postulantes = $query->paginate(15)->withQueryString();

        // Catálogos para la vista
        $departamentos = Departamento::forSelect(); // [DEPA_CODIGO => DEPA_DESCRIPCION]
        $tipoCargos    = TipoCargo::forSelect();    // [CODI_TIPO_CARG => DESC_TIPO_CARG]

        $cargos = Cargo::vigentes()
            ->get(['CODI_CARG', 'DESC_CARGO', 'TIPO_CARG'])
            ->keyBy('CODI_CARG');

        /* === Provincias y Distritos desde SQL Server (tablas reales) === */
        // PROVINCIAS
        $provinciasList = DB::connection('sqlsrv')->table('ADMI_PROVINCIA')
            ->select('PROVI_CODIGO', 'PROVI_DESCRIPCION', 'DEPA_CODIGO')
            ->where('PROVI_VIGENCIA', 'SI')
            ->get()
            ->map(function ($p) use ($normDigits) {
                $p->PROVI_CODIGO = $normDigits($p->PROVI_CODIGO, 4);
                $p->DEPA_CODIGO  = $normDigits($p->DEPA_CODIGO,  2);
                return $p;
            });
        $provinciasStr = $provinciasList->keyBy('PROVI_CODIGO');                 // '0608'
        $provinciasNum = $provinciasList->keyBy(fn($p) => (int)$p->PROVI_CODIGO); // 608

        // DISTRITOS
        $distritosList = DB::connection('sqlsrv')->table('ADMI_DISTRITO')
            ->select('DIST_CODIGO', 'DIST_DESCRIPCION', 'PROVI_CODIGO')
            ->where('DIST_VIGENCIA', 'SI')
            ->get()
            ->map(function ($d) use ($normDigits) {
                $d->DIST_CODIGO  = $normDigits($d->DIST_CODIGO,  6);
                $d->PROVI_CODIGO = $normDigits($d->PROVI_CODIGO, 4);
                return $d;
            });
        $distritosStr = $distritosList->keyBy('DIST_CODIGO');                     // '060808'
        $distritosNum = $distritosList->keyBy(fn($d) => (int)$d->DIST_CODIGO);    // 60808

        // Helper que intenta con clave string y numérica
        $label2 = function ($collStr, $collNum, $code, $field) {
            if ($code === null || $code === '') return $code;
            if ($collStr->has($code)) {
                $o = $collStr->get($code);
                return $o->{$field} ?? (string)$o;
            }
            $numKey = (int)preg_replace('/\D+/', '', $code);
            if ($collNum->has($numKey)) {
                $o = $collNum->get($numKey);
                return $o->{$field} ?? (string)$o;
            }
            return $code;
        };

        // Mapeo legible por fila
        foreach ($postulantes as $r) {
            $dep  = $normDigits($r->departamento ?? '', 2);
            $prov = $normDigits($r->provincia    ?? '', 4);
            $dist = $normDigits($r->distrito     ?? '', 6);

            $r->cargo_nombre        = $cargos->get((string)$r->cargo)->DESC_CARGO ?? $r->cargo;

            // Departamentos: mapa simple [codigo => texto]
            $r->departamento_nombre = $departamentos[$dep] ?? $r->departamento;

            // Provincias/Distritos: catálogos completos (dos índices)
            $r->provincia_nombre    = $label2($provinciasStr, $provinciasNum, $prov, 'PROVI_DESCRIPCION');
            $r->distrito_nombre     = $label2($distritosStr,  $distritosNum,  $dist, 'DIST_DESCRIPCION');

            if (!$distritosStr->has($dist) && !$distritosNum->has((int)$dist)) {
                Log::warning('Distrito no encontrado en catálogo', [
                    'id_postulante' => $r->id,
                    'codigo_norm'   => $dist,
                    'valor_original' => $r->distrito,
                ]);
            }
        }

        // Para la vista/JS seguimos exponiendo $provincias y $distritos
        $provincias = $provinciasStr;
        $distritos  = $distritosStr;

        return view('postulantes.filtrar', compact(
            'postulantes',
            'tipoCargos',
            'cargos',
            'departamentos',
            'provincias',
            'distritos',
            'stats'
        ));
    }


    /* ---------- FUNCION PARA VER EL PDF EN UNA VISTA ENVUELTA ---------- */

    public function verPdfEnvuelto(Postulante $postulante, string $tipo)
    {
        abort_unless(in_array($tipo, ['cv', 'cul'], true), 404);

        $rel  = $postulante->{$tipo};
        $disk = config('filesystems.default', 'local'); // mismo que usaste al guardar

        abort_if(!$rel || !Storage::disk($disk)->exists($rel), 404);

        $streamUrl = route('postulantes.ver-archivo', ['postulante' => $postulante->id, 'tipo' => $tipo]);
        $titulo = \Illuminate\Support\Str::upper($postulante->apellidos) . ' - ' . strtoupper($tipo);

        return view('postulantes.pdf-wrapper', compact('streamUrl', 'titulo'));
    }

    public function verArchivoPostulante(Postulante $postulante, string $tipo)
    {
        $path = $postulante->{$tipo};
        $disk = config('filesystems.default', 'local');

        abort_if(!$path || !Storage::disk($disk)->exists($path), 404);

        return Storage::disk($disk)->response($path, basename($path), [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function cargoTipo($codiCarg)
    {
        $tipo = DB::connection('sqlsrv')->table('CARGOS')
            ->where('CODI_CARG', $codiCarg)
            ->value('CARGO_TIPO'); // '01' operativo, '02' administrativo

        return response()->json([
            'cargo_tipo' => $tipo ? str_pad($tipo, 2, '0', STR_PAD_LEFT) : null
        ]);
    }

    public function validarPostulante(Request $request, $postulanteId)
    {
        $data = $request->validate([
            'decision'   => 'required|in:apto,no_apto',
            'comentario' => 'nullable|string|max:500|required_if:decision,no_apto',
        ]);

        $postulante = Postulante::findOrFail($postulanteId);

        $estadoId = $data['decision'] === 'apto' ? 2 : 3;

        $postulante->update([
            'estado' => $estadoId,
            'decision' => $data['decision'],
            'comentario' => $data['decision'] === 'no_apto' ? ($data['comentario'] ?? null) : null,
        ]);

        return back()->with('ok', $data['decision'] === 'apto'
            ? 'Postulante marcado como APTO'
            : 'Postulante marcado como NO APTO');
    }

    public function updateEstado(Request $request, $postulante)
    {
        $data = $request->validate([
            'estado' => 'required|integer|exists:estado_postulantes,id',
        ]);

        $postulante->estado = (int)$data['estado'];
        $postulante->save();

        return response()->json([
            'ok' => true,
            'estado' => $postulante->estado,
        ]);
    }
}
