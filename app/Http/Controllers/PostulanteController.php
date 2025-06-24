<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostulanteController extends Controller
{
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'nombres' => 'required|string|max:50',
            'apellidos' => 'required|string|max:50',
            'dni' => 'required|string|max:10|unique:postulantes,dni',
            'edad' => 'required|numeric|min:0|max:120',
            'ciudad' => 'required|string|max:50',
            'distrito' => 'required|string|max:50',
            'celular' => 'required|string|max:10',
            'celular_referencia' => 'nullable|string|max:10',
            'estado_civil' => 'nullable|string|max:50',
            'nacionalidad' => 'nullable|string|max:50',
            'cargo' => 'required|string|max:50',
            'fecha_postula' => 'required|date',
            'experiencia_rubro' => 'nullable|string|max:50',
            'sucamec' => 'required|boolean',
            'grado_instruccion' => 'nullable|string|max:50',
            'servicio_militar' => 'nullable|string|max:10',
            'licencia_arma' => 'nullable|string|max:10',
            'licencia_conducir' => 'nullable|string|max:10',
            'cv' => 'nullable|string|max:255',
            'cul' => 'nullable|string|max:255',
        ]);

        // Guardar en la base de datos
        $postulante = Postulante::create($validated);

        // Retornar respuesta (puedes cambiarlo según tu vista o API)
        return redirect()->back()->with('success', 'Postulante registrado correctamente.');
    }

    public function mostrar()
    {
    return view('postulantes.registroPrimario'); // ← cambia si usas otro nombre de vista
     }

}
