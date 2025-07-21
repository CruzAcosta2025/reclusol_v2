<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\RequerimientoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\PosterController;
use App\Http\Controllers\EntrevistaController;
use App\Models\Requerimiento;

Route::get('/', function () {
  return view('welcome');
})->name('welcome');


Route::get('/dashboard', [HomeController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');
// Registro externo (publico)
Route::get('/postulantes/registro', [PostulanteController::class, 'formExterno'])->name('postulantes.formExterno');
Route::post('/postulantes/registro', [PostulanteController::class, 'storeExterno'])->name('postulantes.storeExterno');

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  // Registro interno (solo usuarios logueados)
  Route::middleware('auth')->group(function () {
    Route::get('/postulantes/crear', [PostulanteController::class, 'formInterno'])->name('postulantes.formInterno');
    Route::post('/postulantes', [PostulanteController::class, 'storeInterno'])->name('postulantes.storeInterno');
    Route::get('/postulantes/filtrar', [PostulanteController::class, 'filtrar'])->name('postulantes.filtrar');
    Route::get('/postulantes/ver', [PostulanteController::class, 'ver'])->name('postulantes.ver');
    Route::delete('/postulantes/{postulante}', [PostulanteController::class, 'destroy'])->name('postulantes.destroy');
    Route::get('/postulantes/{postulante}/edit', [PostulanteController::class, 'edit'])->name('postulantes.edit');
    Route::put('/postulantes/{postulante}', [PostulanteController::class, 'update'])->name('postulantes.update');
    Route::get('/postulantes/{id}/descargar/{tipo}', [PostulanteController::class, 'descargarArchivo'])
      ->name('postulantes.descargarArchivo');
  });

  Route::get('/entrevistas', [EntrevistaController::class, 'listadoInicial'])->name('entrevistas.index');

  // RUTAS PARA REQUERIMIENTOS
  Route::get('/requerimientos/registro', [RequerimientoController::class, 'mostrar'])->name('requerimientos.requerimiento');
  Route::post('/requerimientos', [RequerimientoController::class, 'store'])->name('requerimientos.store');
  Route::get('/requerimientos/filtrar', [RequerimientoController::class, 'filtrar'])->name('requerimientos.filtrar');
  Route::delete('/requerimientos/{requerimiento}', [RequerimientoController::class, 'destroy'])->name('requerimientos.destroy');
  Route::get('/requerimientos/{requerimiento}/edit', [RequerimientoController::class, 'edit'])->name('requerimientos.edit');
  Route::put('/requerimientos/{requerimiento}', [RequerimientoController::class, 'update'])->name('requerimientos.update');
  Route::get('requerimientos/sedes-por-cliente', [RequerimientoController::class, 'sedesPorCliente']);


  Route::get('/cargos', [CargoController::class, 'index']);
  Route::get('/cargos/{id}', [CargoController::class, 'show']);

  // RUTAS PARA AFICHES 
  Route::get('/afiches', [PosterController::class, 'index'])->name('afiches.index');

  Route::get('/poster/{req}/{template}', [PosterController::class, 'show'])->name('poster.show');
  Route::get('/historial-afiches', [HistorialController::class, 'index'])->name('afiches.historial');
});

require __DIR__ . '/auth.php';
