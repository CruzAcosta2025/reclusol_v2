<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cargo;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ExampleController extends Controller
{
    public function index()
    {
        $buscar = request('buscar');
        $estado = request('estado');
        $cargo  = request('cargo');

        $users = User::query()
            // Filtro por nombre completo o usuario
            ->when($buscar, function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")   // nombres + apellidos
                      ->orWhere('usuario', 'like', "%{$buscar}%");
                });
            })
            // Filtro por cargo (si lo usas)
            ->when($cargo, function ($query, $cargo) {
                $query->where('cargo', $cargo);
            })
            // Filtro por estado HABILITADO / INHABILITADO
            ->when($estado, function ($query, $estado) {
                if ($estado === 'habilitado') {
                    $query->where('habilitado', 1);
                } elseif ($estado === 'inhabilitado') {
                    $query->where('habilitado', 0);
                }
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString(); // mantiene los filtros en la paginación

        // Estadísticas globales
        $totalUsers        = User::count();
        $activeUsers       = User::where('habilitado', 1)->count();
        $inactiveUsers     = User::where('habilitado', 0)->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $cargos = Cargo::forSelect();

        return view('usuarios.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'inactiveUsers',
            'newUsersThisMonth',
            'cargos'
        ));
    }

    /*
    public function index()
    {
        $users = User::query()
            ->when(request('buscar'), function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%");
                    //->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->when(request('cargo'), function ($query, $cargo) {
                $query->where('cargo', $cargo);
            })
            ->when(request('estado'), function ($query, $estado) {
                if ($estado === 'activo') {
                    $query->where('habilitado', 1);
                } elseif ($estado === 'inactivo') {
                    $query->where('habilitado', 0);
                }
            })
            ->paginate(15);


        // Obtener estadísticas
        $totalUsers = User::count();
        //$activeUsers = User::whereNotNull('email_verified_at')->count();
        //$inactiveUsers = User::whereNull('email_verified_at')->count();
        $newUsersThisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        // Obtener datos para filtros
        $cargos = Cargo::forSelect();

        return view('usuarios.index', compact(
            'users',
            'totalUsers',
            'newUsersThisMonth',
            'cargos'
        ));
    }
    */

    public function create()
    {
        // Llamada al SP, puedes filtrar por sucursal si quieres
        /*
        $personal = DB::connection('sqlsrv')
            ->select('EXEC RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL');

        // Opcional: obtener lista de sucursales para filtrar en el formulario
        $sucursales = DB::connection('si_solmar')
            ->table('SISO_SUCURSAL')
            ->where('SUCU_VIGENCIA', 'SI')
            ->orderBy('SUCU_DESCRIPCION')
            ->get();

        $cargos = DB::connection('si_solmar')
            ->table('TIPO_CARGO')
            ->select(['CODI_TIPO_CARG', 'DESC_TIPO_CARG'])
            ->get()
            ->keyBy('CODI_TIPO_CARG');
        */

        $sucursales = Sucursal::forSelect();
        $cargos = Cargo::forSelect();

        // Pasa el personal y sucursales a la vista
        return view('usuarios.create', compact('sucursales', 'cargos'));
    }

    public function buscarDniSimple(string $dni)
    {
        $dni = preg_replace('/\D/', '', $dni);
        if (strlen($dni) !== 8) {
            return response()->json(['ok' => false, 'message' => 'DNI inválido'], 422);
        }

        $data = Cache::remember("perudevs:dni:$dni", now()->addHours(12), function () use ($dni) {
            $resp = Http::timeout(10)->acceptJson()->get(
                config('services.perudevs.dni_simple_url'), // de config/services.php
                [
                    'document' => $dni,
                    'key'      => config('services.perudevs.key'), // de .env
                ]
            );

            if (!$resp->ok()) return null;
            $j = $resp->json();
            if (!($j['estado'] ?? false)) return null;

            $r = $j['resultado'] ?? [];
            return [
                'nombres'   => $r['nombres'] ?? '',
                'apellidos' => trim(($r['apellido_paterno'] ?? '') . ' ' . ($r['apellido_materno'] ?? '')),
                'completo'  => $r['nombre_completo'] ?? '',
            ];
        });

        if (!$data) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['ok' => true, 'data' => $data]);
    }

    //Decolecta API
    public function buscarDniDecolecta(string $dni)
    {
        $dni = preg_replace('/\D/', '', $dni);
        if (strlen($dni) !== 8) {
            return response()->json(['ok' => false, 'message' => 'DNI inválido'], 422);
        }

        $data = Cache::remember("decolecta:dni:$dni", now()->addHours(12), function () use ($dni) {

            $resp = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.decolecta.api_key'),
                'Accept'        => 'application/json'
            ])->timeout(10)->get(config('services.decolecta.api_url'), [
                'numero' => $dni
            ]);

            if (!$resp->ok()) {
                return null;
            }

            $j = $resp->json();

            return [
                'nombres'   => $j['first_name'] ?? '',
                'apellidos' => trim(($j['first_last_name'] ?? '') . ' ' . ($j['second_last_name'] ?? '')),
                'completo'  => $j['full_name'] ?? '',
            ];
        });

        if (!$data) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['ok' => true, 'data' => $data]);
    }

    /*
    public function buscarPersonal(Request $request)
    {
        Log::info('Buscar personal', [
            'sucursal' => $request->sucursal,
            'q' => $request->q
        ]);
        $sucursal = $request->sucursal;
        $search = $request->q;

        $personal = collect(DB::connection('sqlsrv')
            ->select('EXEC RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL ?', [$sucursal]));

        if ($search) {
            $personal = $personal->filter(function ($item) use ($search) {
                return stripos($item->NOMBRE_COMPLETO, $search) !== false;
            });
        }

        $result = $personal->map(function ($item) {
            return [
                'id'    => $item->NOMBRE_COMPLETO, // o usa un ID único si tienes
                'text'  => $item->NOMBRE_COMPLETO,
                'cargo' => $item->DESC_CARGO ?? '',
            ];
        })->values();

        return response()->json(['results' => $result]);
    }
    */

    /*
    public function personalPorSucursal($codigo)
    {
        // Log para depurar
        Log::info('Sucursal seleccionada:', ['codigo' => $codigo]);

        $personal = DB::connection('sqlsrv')
            ->select('EXEC RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL @SucursalCodigo = ?', [$codigo]);

        // Log para depurar
        Log::info('Personal encontrado:', ['personal' => $personal]);

        return response()->json($personal);
    }
    */

    public function habilitarUsuario(User $user)
    {
        // Cambia el estado: si está habilitado lo deshabilita, y viceversa
        $user->habilitado = !$user->habilitado;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->habilitado ? 'Usuario habilitado' : 'Usuario deshabilitado',
            'status'  => $user->habilitado ? 'habilitado' : 'inhabilitado'
        ]);
    }


    public function store(Request $request)
    {
        Log::info('Inicio de registro de usuario', ['request' => $request->all()]);

        // "name" en el form es el USERNAME; nombre completo viene de nombres+apellidos
        $request->validate([
            'dni'        => 'nullable|digits:8',
            'nombres'    => 'required|string|max:120',
            'apellidos'  => 'required|string|max:120',
            'name'       => 'required|string|max:60|unique:users,usuario', // unique sobre columna usuario
            'contrasena' => 'required|string|min:8',
            'rol'        => 'required|exists:roles,name',
            // 'email'    => 'nullable|email|max:255|unique:users,email', // si usarás email
        ]);

        $fullName = trim($request->nombres . ' ' . $request->apellidos);
        $dni      = $request->filled('dni') ? (int)preg_replace('/\D/', '', $request->dni) : null;
        $hash     = bcrypt($request->contrasena);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'        => $fullName,          // nombre completo
                'usuario'     => $request->name,     // username
                'dni'         => $dni,               // numeric(18,0)
                'cargo'       => null,               // aquí no lo tienes
                'sucursal'    => null,
                'rol'         => $request->rol,      // además de Spatie si lo guardas como texto
                'contrasena'  => $hash,              // tu columna usada
                'password'    => $hash,              // mantén ambas sincronizadas por si usas Auth de Laravel
                'email'       => null,
                'habilitado'  => 1,                  // NOT NULL en tu tabla
            ]);

            // Spatie
            $user->assignRole($request->rol);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Usuario creado con éxito']);
            }
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear usuario', ['error' => $e->getMessage()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al guardar el usuario'], 500);
            }
            return back()->withErrors(['general' => 'Ocurrió un error al guardar el usuario'])->withInput();
        }
    }



    /*
    public function store(Request $request)
    {
        Log::info('Inicio de registro de usuario', [
            'request' => $request->all()
        ]);

        // --- VALIDACIÓN ---
        $request->validate([
            'sucursal'    => 'required|string|max:50',
            'personal_id' => 'required',
            'name'        => 'required|string|unique:users,name',
            'contrasena'  => 'required|string|min:8',
            'rol'         => 'required|exists:roles,name',
        ]);

        try {
            Log::info('Llamando SP RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL', [
                'sucursal' => $request->sucursal
            ]);

            $personal = collect(
                DB::connection('sqlsrv')
                    ->select('EXEC RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL ?', [$request->sucursal])
            );

            Log::info('Resultado SP', [
                'total_resultados' => $personal->count(),
                'primer_resultado' => $personal->first()
            ]);

            $persona = $personal->firstWhere('NOMBRE_COMPLETO', $request->personal_id);

            Log::info('Persona seleccionada', [
                'buscando_nombre' => $request->personal_id,
                'persona' => $persona
            ]);

            if (!$persona) {
                Log::warning('No se encontró persona para el nombre y sucursal seleccionados', [
                    'nombre' => $request->personal_id,
                    'sucursal' => $request->sucursal
                ]);
                // --- RESPONDE JSON SI AJAX ---
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No se encontró la persona seleccionada para esta sucursal.',
                    ], 422);
                }
                return back()->withErrors(['personal_id' => 'No se encontró la persona seleccionada para esta sucursal.'])->withInput();
            }

            $user = User::create([
                'sucursal'   => $persona->SUCURSAL ?? $request->sucursal,
                'name'       => $persona->NOMBRE_COMPLETO,
                'usuario'    => $request->name,
                'contrasena' => bcrypt($request->contrasena),
                'cargo'      => $persona->CODI_CARG ?? null,
                'rol'        => $request->rol,
            ]);

            Log::info('Usuario creado', [
                'user_id' => $user->id,
                'name'    => $user->name
            ]);

            $user->assignRole($request->rol);

            Log::info('Rol asignado', [
                'user_id' => $user->id,
                'rol' => $request->rol
            ]);

            // --- RESPONDE JSON SI AJAX ---
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario creado con éxito'
                ]);
            }
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito');
        } catch (\Throwable $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // --- RESPONDE JSON SI AJAX ---
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ocurrió un error al guardar el usuario',
                    'errors'  => $e->getMessage(),
                ], 500);
            }
            return back()->withErrors(['general' => 'Ocurrió un error al guardar el usuario'])->withInput();
        }
    }
    */


    public function show(User $user)
    {
        $user->load('cargoInfo');
        return view('usuarios.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'dni'        => 'nullable|digits:8|unique:users,dni,' . $user->id,
            'nombres'    => 'required|string|max:120',
            'apellidos'  => 'required|string|max:120',
            'name'       => 'required|string|max:60|unique:users,usuario,' . $user->id . ',id',
            'contrasena' => 'nullable|string|min:8',
            'rol'        => 'required|exists:roles,name',
        ]);

        $data = [
            'name'    => trim($request->nombres . ' ' . $request->apellidos),
            'usuario' => $request->name,
            'dni'     => $request->filled('dni') ? (int)preg_replace('/\D/', '', $request->dni) : null,
            'rol'     => $request->rol,
        ];
        if ($request->filled('contrasena')) {
            $hash = bcrypt($request->contrasena);
            $data['contrasena'] = $hash;
            $data['password']   = $hash;
        }
        $user->update($data);
        $user->syncRoles([$request->rol]);

        return $request->ajax()
            ? response()->json(['success' => true, 'message' => 'Usuario editado con éxito'])
            : redirect()->route('usuarios.index')->with('success', 'Usuario editado con éxito');
    }

    public function edit(User $user)
    {
        if (request()->ajax()) {
            return view('usuarios.partials.form-edit', compact('user'));
        }
        return view('usuarios.form-edit', compact('user')); // página completa sólo si la navegas directa
    }

    public function destroy(User $user)
    {
        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }

    public function toggleStatus(User $user)
    {
        if ($user->email_verified_at) {
            $user->email_verified_at = null;
            $message = 'Usuario desactivado';
        } else {
            $user->email_verified_at = now();
            $message = 'Usuario activado';
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => $message,
            'status' => $user->email_verified_at ? 'activo' : 'inactivo'
        ]);
    }
}
