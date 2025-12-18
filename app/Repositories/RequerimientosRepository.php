<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Requerimiento;
use App\Models\EstadoRequerimiento;
use App\Models\PrioridadRequerimiento;
use App\Models\Sucursal;
use App\Models\TipoCargo;
use App\Models\Cargo;
use App\Models\TipoPersonal;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Distrito;
use App\Repositories\Interfaces\RequerimientosRepositoryInterface;

class RequerimientosRepository implements RequerimientosRepositoryInterface
{
    protected Requerimiento $modelo;

    public function __construct(Requerimiento $modelo)
    {
        $this->modelo = $modelo;
    }

    public function getAll(): Collection
    {
        return Requerimiento::all();
    }

    public function getById(mixed $id): ?Requerimiento
    {
        return $this->modelo->find($id);
    }

    public function getByIdWithRelations(mixed $id): ?array
    {
        $requerimiento = $this->getById($id);

        if (!$requerimiento) {
            return null;
        }

        $data = $requerimiento->toArray();

        // Cargar usuario
        if (!empty($requerimiento->user_id)) {
            $usuario = DB::table('users')->where('id', $requerimiento->user_id)->first();
            $data['usuario_nombre'] = $usuario ? $usuario->name : 'Desconocido';
        }

        // Cargar estado
        if (!empty($requerimiento->estado)) {
            $estado = EstadoRequerimiento::find($requerimiento->estado);
            $data['estado_nombre'] = $estado ? $estado->nombre : null;
        }

        // Cargar cargo desde SQL Server
        if (!empty($requerimiento->cargo_solicitado)) {
            $cargo = DB::connection('sqlsrv')
                ->table('CARGOS')
                ->where('CODI_CARG', $requerimiento->cargo_solicitado)
                ->value('DESC_CARGO');
            $data['cargo_nombre'] = $cargo ?? $requerimiento->cargo_solicitado;
        }

        // Cargar sucursal desde SQL Server
        if (!empty($requerimiento->sucursal)) {
            $sucursal = DB::connection('sqlsrv')
                ->table('SISO_SUCURSAL')
                ->where('SUCU_CODIGO', $requerimiento->sucursal)
                ->value('SUCU_DESCRIPCION');
            $data['sucursal_nombre'] = $sucursal ?? $requerimiento->sucursal;
        }

        // Cargar cliente desde SQL Server
        if (!empty($requerimiento->cliente)) {
            $cliente = collect(DB::connection('sqlsrv')->select('EXEC dbo.SP_LISTAR_CLIENTES'))
                ->firstWhere('CODIGO_CLIENTE', $requerimiento->cliente);
            $data['cliente_nombre'] = $cliente ? $cliente->NOMBRE_CLIENTE : $requerimiento->cliente;
        }
        return $data;
    }

    public function store(array $data)
    { 
        return Requerimiento::create($data);
    }

    public function update($id, $data)
    {
        return Requerimiento::where('id', $id)->update($data);
    }

    public function destroy($id)
    {
        return Requerimiento::where('id', $id)->delete();
    }

    public function getEstados(): array
    {
        return EstadoRequerimiento::all()->toArray();
    }

    public function getPrioridades(): array
    {
        return PrioridadRequerimiento::all()->toArray();
    }

    public function getSucursales(): array
    {
        return Sucursal::vigentes()
            ->sinEspeciales()
            ->get(['SUCU_CODIGO', 'SUCU_DESCRIPCION'])
            ->map(function ($item) {
                return (object) [
                    'SUCU_CODIGO' => $item->SUCU_CODIGO,
                    'SUCU_DESCRIPCION' => $item->SUCU_DESCRIPCION,
                ];
            })
            ->toArray();
    }

    public function getTipoPersonal(): array
    {
        return TipoPersonal::forSelect()->toArray();
    }

    public function getTipoCargos(): array
    {
        // Devolver objetos con llaves CODI_TIPO_CARG y DESC_TIPO_CARG
        return TipoCargo::query()
            ->get(['CODI_TIPO_CARG', 'DESC_TIPO_CARG'])
            ->map(function ($item) {
                return (object) [
                    'CODI_TIPO_CARG' => $item->CODI_TIPO_CARG,
                    'DESC_TIPO_CARG' => $item->DESC_TIPO_CARG,
                ];
            })
            ->toArray();
    }

    public function getCargos(): array
    {
        return Cargo::all()->toArray();
    }

    public function getClientes(): array
    {
        return collect(DB::connection('sqlsrv')->select('EXEC dbo.SP_LISTAR_CLIENTES'))->toArray();
    }

    public function getClientesPorSucursal(string $sucursal, ?string $buscar = null): array
    {
        if ($sucursal === '') return [];

        $rows = DB::connection('sqlsrv')->select(
            'EXEC dbo.USP_RECLUSOL_CLIENTES_POR_SUCURSAL ?, ?',
            [$sucursal, $buscar]
        );

        // Normalizar llaves
        return array_map(function ($x) {
            $cod = $x->CODIGO_CLIENTE ?? $x->codigo_cliente ?? null;
            $nom = $x->NOMBRE_CLIENTE ?? $x->nombre_cliente ?? null;
            return [
                'CODIGO_CLIENTE' => is_string($cod) ? trim($cod) : $cod,
                'NOMBRE_CLIENTE' => $nom,
            ];
        }, $rows);
    }

    public function getSedesPorCliente(string $codigo): array
    {
        return collect(DB::select(
            'EXEC USP_SICOS_2024_LISTAR_SEDES_X_CLIENTE ?, ?, ?',
            [$codigo, '', 0]
        ))->toArray();
    }

    public function getTiposPorTipoPersonal(string $tipoPersonal): array
    {
        if (!$tipoPersonal) return [];
        return DB::connection('sqlsrv')->select(
            'EXEC dbo.REC_TIPOS_CARGO_POR_TIPO_PERSONAL ?',
            [$tipoPersonal]
        );
    }

    public function getCargosPorTipo(string $tipoPersonal, string $tipoCargo): array
    {
        if (!$tipoPersonal || !$tipoCargo) return [];
        return DB::connection('sqlsrv')->select(
            'EXEC dbo.REC_CARGOS_POR_TIPO ?, ?',
            [$tipoPersonal, $tipoCargo]
        );
    }

    public function getTipoCargo(string $codiCarg): ?string
    {
        return DB::connection('sqlsrv')->table('CARGOS')
            ->where('CODI_CARG', $codiCarg)
            ->value('CA RGO_TIPO');
    }

    public function getDepartamentos(): array
    {
        return Departamento::vigentes()->get()->map(function($item) {
            return (object)$item->toArray();
        })->toArray();
    }

    public function getProvincias(): array
    {
        return Provincia::vigentes()->get()->map(function($item) {
            return (object)$item->toArray();
        })->toArray();
    }

    public function getDistritos(): array
    {
        return Distrito::vigentes()->get()->map(function($item) {
            return (object)$item->toArray();
        })->toArray();
    }

    public function getNivelesEducativos(): array
    {
        return DB::connection('sqlsrv')
            ->table('SUNAT_NIVEL_EDUCATIVO')
            ->select('NIED_CODIGO', 'NIED_DESCRIPCION')
            ->get()
            ->map(function($item) {
                return (object)$item;
            })
            ->toArray();
    }
}
