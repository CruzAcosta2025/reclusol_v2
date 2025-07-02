<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PostulanteSecundarioController;
use App\Http\Controllers\RequerimientoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\PosterController;

Route::get('/', function () {
  return view('welcome');
})->name('welcome');


Route::get('/dashboard', [HomeController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');


Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

  
  // RUTAS PARA POSTULANTES
  Route::get('/postulantes/crear', [PostulanteController::class, 'crear'])->name('postulantes.crear');
  Route::get('/postulantes/filtrar', [PostulanteController::class, 'filtrar'])->name('postulantes.filtrar');
  Route::post('/postulantesInterno', [PostulanteController::class, 'store'])->name('postulantes.store');
  Route::get('/postulantes/registro', [PostulanteController::class, 'mostrar'])->name('postulantes.registroPrimario');
  Route::delete('/postulantes/{postulante}', [PostulanteController::class, 'destroy'])->name('postulantes.destroy');
  Route::get('/postulantes/{postulante}/edit', [PostulanteController::class, 'edit'])->name('postulantes.edit');
  Route::put('/postulantes/{postulante}', [PostulanteController::class, 'update'])->name('postulantes.update');


 // RUTAS PARA REQUERIMIENTOS
  Route::get('/requerimientos/registro', [RequerimientoController::class, 'mostrar'])->name('requerimientos.requerimiento');
  Route::post('/requerimientos', [RequerimientoController::class, 'store'])->name('requerimientos.store');
  Route::get('/requerimientos/filtrar', [RequerimientoController::class, 'index'])->name('requerimientos.filtrar');
   

  Route::get('/cargos', [CargoController::class, 'index']);
  Route::get('/cargos/{id}', [CargoController::class, 'show']);

  // RUTAS PARA AFICHES 
  Route::get('/afiches', [PosterController::class, 'index'])->name('afiches.index');
  Route::get('/poster/{req}/{template}', [PosterController::class, 'show'])->name('poster.show');
  Route::get('/historial-afiches', [HistorialController::class, 'index'])->name('afiches.historial');

});


Route::get('/postulantes/registro', [PostulanteController::class, 'mostrar'])->name('postulantes.registroPrimario');
Route::post('/postulantes', [PostulanteController::class, 'store'])->name('postulantes.store');


require __DIR__ . '/auth.php';
