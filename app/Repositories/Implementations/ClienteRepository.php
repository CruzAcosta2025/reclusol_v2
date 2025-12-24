<?php

namespace App\Repositories\Implementations;

use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Repositories\Interfaces\ClienteRepositoryInterface;

class ClienteRepository implements ClienteRepositoryInterface
{
    public function getPorSucursal(string $sucursal, string|null $buscar = null): array
    {
        if ($sucursal === '') {
            return [];
        }

        $rows = DB::connection('sqlsrv')->select(
            'EXEC dbo.USP_RECLUSOL_CLIENTES_POR_SUCURSAL ?, ?',
            [$sucursal, $buscar]
        );

        return array_map(function ($row) {
            $codigo = $row->CODIGO_CLIENTE ?? $row->codigo_cliente ?? null;
            $nombre = $row->NOMBRE_CLIENTE ?? $row->nombre_cliente ?? null;

            return [
                'CODIGO_CLIENTE' => is_string($codigo) ? trim($codigo) : $codigo,
                'NOMBRE_CLIENTE' => $nombre,
            ];
        }, $rows);
    }

    public function getSedesPorCliente(string $codigo): array
    {
        if ($codigo === '') {
            return [];
        }

        $rows = DB::connection('sqlsrv')->select(
            'EXEC USP_SICOS_2024_LISTAR_SEDES_X_CLIENTE ?, ?, ?',
            [$codigo, '', 0]
        );

        return array_map(fn ($row) => (array) $row, $rows);
    }
}