<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\UserService;
use App\Services\CargoService;
use App\Services\CatalogoService;

class UserController1 extends Controller
{

    protected UserService $userService;
    protected CargoService $cargoService;
    protected CatalogoService $catalogoService;

    public function __construct(UserService $userService, CargoService $cargoService, CatalogoService $catalogoService)
    {
        $this->userService = $userService;
        $this->cargoService = $cargoService;
        $this->catalogoService = $catalogoService;
    }

    public function index()
    {
        $buscar = request('buscar');
        $estado = request('estado');
        $cargo = request('cargo');

        $users = $this->userService->listar(
            filters: [
                'buscar' => $buscar,
                'estado' => $estado,
                'cargo' => $cargo,
            ],
            perPage: 15
        );

        $estadisticas = $this->userService->obtenerEstadisticas();
        $cargos = $this->cargoService->forSelect();

        return view('usuarios.index', compact(
            'users',
            'estadisticas',
            'cargos'
        ));
    }

    public function create()
    {
        $sucursales = $this->catalogoService->obtenerSucursales();
        $cargos = $this->cargoService->forSelect();

        return view('usuarios.create', compact('sucursales', 'cargos'));
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
                'Accept' => 'application/json'
            ])->timeout(10)->get(config('services.decolecta.api_url'), [
                        'numero' => $dni
                    ]);

            if (!$resp->ok()) {
                return null;
            }

            $j = $resp->json();

            return [
                'nombres' => $j['first_name'] ?? '',
                'apellidos' => trim(($j['first_last_name'] ?? '') . ' ' . ($j['second_last_name'] ?? '')),
                'completo' => $j['full_name'] ?? '',
            ];
        });

        if (!$data) {
            return response()->json(['ok' => false, 'message' => 'No encontrado'], 404);
        }

        return response()->json(['ok' => true, 'data' => $data]);
    }

    public function alternarHabilitado($id)
    {
        try {
            $habilitado = $this->userService->alternarHabilitado($id);
            $message = $habilitado ? 'Usuario habilitado' : 'Usuario deshabilitado';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $habilitado ? 'habilitado' : 'inhabilitado'
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado habilitado', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al cambiar estado'], 500);
        }
    }

    public function store(Request $request)
    {
        Log::info('Inicio de registro de usuario', ['request' => $request->all()]);

        $request->validate([
            'dni' => 'nullable|digits:8',
            'nombres' => 'required|string|max:120',
            'apellidos' => 'required|string|max:120',
            'name' => 'required|string|max:60|unique:users,usuario',
            'contrasena' => 'required|string|min:8',
            'rol' => 'required|exists:roles,name',
        ]);

        try {
            $fullName = trim($request->nombres . ' ' . $request->apellidos);
            $dni = $request->filled('dni') ? (int) preg_replace('/\D/', '', $request->dni) : null;
            $hash = Hash::make($request->contrasena);

            $data = [
                'name' => $fullName,
                'usuario' => $request->name,
                'dni' => $dni,
                'cargo' => null,
                'sucursal' => null,
                'rol' => $request->rol,
                'contrasena' => $hash,
                'password' => $hash,
                'email' => null,
                'habilitado' => 1,
            ];

            $user = $this->userService->crear($data, $request->rol);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Usuario creado con éxito']);
            }
            return redirect()->route('usuarios.index')->with('success', 'Usuario creado con éxito');
        } catch (\Throwable $e) {
            Log::error('Error al crear usuario', ['error' => $e->getMessage()]);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Ocurrió un error al guardar el usuario'], 500);
            }
            return back()->withErrors(['general' => 'Ocurrió un error al guardar el usuario'])->withInput();
        }
    }

    public function show($id)
    {
        $user = $this->userService->obtenerPorId($id);
        return view('usuarios.show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'dni' => 'nullable|digits:8|unique:users,dni,' . $id,
            'nombres' => 'required|string|max:120',
            'apellidos' => 'required|string|max:120',
            'name' => 'required|string|max:60|unique:users,usuario,' . $id . ',id',
            'contrasena' => 'nullable|string|min:8',
            'rol' => 'required|exists:roles,name',
        ]);

        try {
            $data = [
                'name' => trim($request->nombres . ' ' . $request->apellidos),
                'usuario' => $request->name,
                'dni' => $request->filled('dni') ? (int) preg_replace('/\D/', '', $request->dni) : null,
                'rol' => $request->rol,
            ];

            if ($request->filled('contrasena')) {
                $hash = Hash::make($request->contrasena);
                $data['contrasena'] = $hash;
                $data['password'] = $hash;
            }

            $this->userService->actualizar($id, $data, $request->rol);

            return $request->ajax()
                ? response()->json(['success' => true, 'message' => 'Usuario editado con éxito'])
                : redirect()->route('usuarios.index')->with('success', 'Usuario editado con éxito');
        } catch (\Throwable $e) {
            Log::error('Error al actualizar usuario', ['error' => $e->getMessage()]);
            return $request->ajax()
                ? response()->json(['success' => false, 'message' => 'Error al actualizar usuario'], 500)
                : back()->withErrors(['general' => 'Error al actualizar usuario'])->withInput();
        }
    }

    public function edit($id)
    {
        $user = $this->userService->obtenerPorId($id);

        if (request()->ajax()) {
            return view('usuarios.partials.form-edit', compact('user'));
        }
        return view('usuarios.form-edit', compact('user'));
    }

    public function destroy($id)
    {
        try {
            $this->userService->delete($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Usuario eliminado exitosamente'
                ]);
            }

            return redirect()->route('usuarios.index')
                ->with('success', 'Usuario eliminado exitosamente');
        } catch (\Throwable $e) {
            Log::error('Error al eliminar usuario', ['error' => $e->getMessage()]);
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Error al eliminar usuario'], 500);
            }
            return back()->withErrors(['general' => 'Error al eliminar usuario']);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $status = $this->userService->alternarVerificacionEmail($id);
            $message = $status === 'activo' ? 'Usuario activado' : 'Usuario desactivado';

            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $status
            ]);
        } catch (\Throwable $e) {
            Log::error('Error al cambiar estado de usuario', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error al cambiar estado'], 500);
        }
    }
}