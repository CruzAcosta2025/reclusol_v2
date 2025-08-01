<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->when(request('buscar'), function ($query, $buscar) {
                $query->where(function ($q) use ($buscar) {
                    $q->where('name', 'like', "%{$buscar}%")
                        ->orWhere('email', 'like', "%{$buscar}%");
                });
            })
            ->when(request('cargo'), function ($query, $cargo) {
                $query->where('cargo', $cargo);
            })
            ->when(request('estado'), function ($query, $estado) {
                if ($estado === 'activo') {
                    $query->whereNotNull('email_verified_at');
                } elseif ($estado === 'inactivo') {
                    $query->whereNull('email_verified_at');
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

        $cargos = DB::connection('si_solmar')->table('TIPO_CARGO')
            ->select(['CODI_TIPO_CARG', 'DESC_TIPO_CARG'])
            ->get()
            ->keyBy('CODI_TIPO_CARG');

        return view('usuarios.index', compact(
            'users',
            'totalUsers',
            // 'activeUsers',
            // 'inactiveUsers',
            'newUsersThisMonth',
            'cargos'
        ));
    }

    public function create()
    {
        // Llamada al SP, puedes filtrar por sucursal si quieres
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


        // Pasa el personal y sucursales a la vista
        return view('usuarios.create', compact('personal', 'sucursales', 'cargos'));
    }

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


    public function store(Request $request)
    {
        Log::info('Inicio de registro de usuario', [
            'request' => $request->all()
        ]);

        $request->validate([
            'sucursal'    => 'required|string|max:50',
            'personal_id' => 'required',
            'name'        => 'required|string|unique:users,name', // o 'usuario', revisa tu tabla
            'contrasena'  => 'required|string|min:8',
            'rol'         => 'required|exists:roles,name',
        ]);

        try {
            Log::info('Llamando SP RECLUSOL_2025_LISTAR_PERSONALXSUCURSAL', [
                'sucursal' => $request->sucursal
            ]);

            // Ahora el SP debe devolver CODI_CARG también
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
                return back()->withErrors(['personal_id' => 'No se encontró la persona seleccionada para esta sucursal.'])->withInput();
            }

            $user = User::create([
                'sucursal'   => $persona->SUCURSAL ?? $request->sucursal,
                'name'       => $persona->NOMBRE_COMPLETO,
                'usuario'    => $request->name, // o $request->usuario según corresponda
                'contrasena' => bcrypt($request->contrasena),
                'cargo'      => $persona->CODI_CARG ?? null, // **AHORA el código del cargo**
                'rol'        => $request->rol,               // Guarda el rol textual si quieres verlo en la tabla
            ]);

            Log::info('Usuario creado', [
                'user_id' => $user->id,
                'name'    => $user->name
            ]);

            // Asignar el rol (Spatie)
            $user->assignRole($request->rol);

            Log::info('Rol asignado', [
                'user_id' => $user->id,
                'rol' => $request->rol
            ]);

            return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito');
        } catch (\Throwable $e) {
            Log::error('Error al crear usuario', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['general' => 'Ocurrió un error al guardar el usuario'])->withInput();
        }
    }

    public function show(User $user)
    {
        $user->load('cargoInfo');
        return view('usuarios.show', compact('user'));
    }

    public function edit(User $user)
    {
        $cargos = DB::connection('si_solmar')->table('TIPO_CARGO')
            ->where('CODI_TIPO_CARG')
            ->select(['CODI_TIPO_CARG', 'DESC_TIPO_CARG'])
            ->get()
            ->keyBy('CODI_TIPO_CARG');


        if (request()->ajax()) {
            return view('usuarios.partials.form-edit', compact('user', 'cargos'));
        }

        return view('usuarios.edit', compact('user', 'cargos'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'cargo'    => 'required|string|max:10',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'cargo' => $request->cargo,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        }

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
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
