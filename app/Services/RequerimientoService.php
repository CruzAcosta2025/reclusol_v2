<?php

namespace App\Services;

use App\Repositories\Interfaces\RequerimientosRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Requerimiento;
use App\Notifications\NuevoRequerimientoCreado;

class RequerimientoService
{
    protected $repo;

    public function __construct(RequerimientosRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAll(): Collection{
        return $this->repo->getAll();
    }

    public function getById(mixed $id): Requerimiento{
        return $this->repo->getById($id);
    }

    public function getDetalleCompleto(mixed $id): ?array
    {
        return $this->repo->getByIdWithRelations($id);
    }

    /* public function store(array $validated, $request)
    {
        
        $validated['user_id'] = Auth::id();
        $validated['fecha_solicitud'] = now();
        $validated['cargo_usuario'] = Auth::user()->cargo ?? null;

        
        $validated['requiere_sucamec'] = $request->boolean('requiere_sucamec');
        $validated['validado_rrhh']   = $request->boolean('validado_rrhh');

        try {

            $requerimiento = DB::transaction(function () use ($validated) {
                return $this->repo->create($validated);
            });

            $usuarios = User::all();
            foreach ($usuarios as $usuario) {
                $usuario->notify(new NuevoRequerimientoCreado($requerimiento));
            }

            Log::info('Requerimiento guardado', $requerimiento->toArray());

            return [
                'status' => true,
                'requerimiento' => $requerimiento
            ];

        } catch (\Throwable $e) {

            Log::error('Error al guardar requerimiento', [
                'message' => $e->getMessage()
            ]);

            return [
                'status' => false,
                'error'  => $e->getMessage()
            ];
        }
    }
 */
    public function getFormData(): array
    {
        return [
            'estados'      => $this->repo->getEstados(),
            'prioridades'  => $this->repo->getPrioridades(),
            'sucursales'   => $this->repo->getSucursales(),
            'tipoCargos'   => $this->repo->getTipoCargos(),
            'cargos'       => $this->repo->getCargos(),
            'tipoPersonal' => $this->repo->getTipoPersonal(),
            'clientes'     => $this->repo->getClientes(),
        ];
    }

    public function clientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        return $this->repo->getClientesPorSucursal($sucursal, $buscar);
    }

    public function sedesPorCliente(string $codigo): array
    {
        return $this->repo->getSedesPorCliente($codigo);
    }

    public function tiposPorTipoPersonal(string $tipoPersonal): array
    {
        return $this->repo->getTiposPorTipoPersonal($tipoPersonal);
    }

    public function cargosPorTipo(string $tipoPersonal, string $tipoCargo): array
    {
        return $this->repo->getCargosPorTipo($tipoPersonal, $tipoCargo);
    }

    public function cargoTipo(string $codiCarg): ?string
    {
        $tipo = $this->repo->getTipoCargo($codiCarg);
        return $tipo ? str_pad($tipo, 2, '0', STR_PAD_LEFT) : null;
    }
}
