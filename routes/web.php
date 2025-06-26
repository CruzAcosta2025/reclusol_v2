<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\PostulanteSecundarioController;
use App\Http\Controllers\RequerimientoController;
use App\Http\Controllers\CargoController;
use App\Http\Controllers\HomeController;


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
    Route::get('/postulantesInterno/registro', [PostulanteSecundarioController::class, 'mostrar'])->name('postulantes.registroSecundario');
    Route::post('/postulantesInterno', [PostulanteSecundarioController::class, 'store'])->name('internos.postulantes.store');
    Route::get('/requerimientos/registro', [RequerimientoController::class, 'mostrar'])->name('requerimientos.requerimiento');
    Route::post('/requerimientos', [RequerimientoController::class, 'store'])->name('requerimientos.store');
    Route::get('/cargos', [CargoController::class, 'index']);
    Route::get('/cargos/{id}', [CargoController::class, 'show']);
});

Route::get('/postulantes/registro', [PostulanteController::class, 'mostrar'])->name('postulantes.registroPrimario');
Route::post('/postulantes', [PostulanteController::class, 'store'])->name('postulantes.store');


require __DIR__.'/auth.php';
