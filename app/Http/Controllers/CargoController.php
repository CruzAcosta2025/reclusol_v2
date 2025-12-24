<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\CargoService;

class CargoController extends Controller
{

    protected CargoService $service;

    public function __construct(CargoService $service)
    {
        $this->service = $service;
    }

     public function index()
    {
        return $this->service->getAll();
    }

    public function show(int $id)
    {
        return $this->service->find($id);
    }
    
     public function forSelect()
    {
        return $this->service->forSelect();
    }

    public function forSelectByTipo(Request $request)
    {
        $tipoCodigo = $request->query('tipo_codigo');
        return $this->service->forSelectByTipo($tipoCodigo);
    }
}
