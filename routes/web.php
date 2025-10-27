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
use App\Http\Controllers\UserController;

Route::get('/', function () {
  return view('welcome');
})->name('welcome');


Route::get('/dashboard', [HomeController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');
// Registro externo (publico)
Route::get('/postulantes/registro', [PostulanteController::class, 'formExterno'])->name('postulantes.formExterno');
Route::post('/postulantes/registro', [PostulanteController::class, 'storeExterno'])->name('postulantes.storeExterno');
Route::get('/api-publico/cargos-por-tipo/{tipo}', [PostulanteController::class, 'getCargosPorTipo']);
Route::get('/api-publico/provincias/{depa}', [PostulanteController::class, 'getProvincias']);
Route::get('/api-publico/distritos/{prov}',  [PostulanteController::class, 'getDistritos']);
// routes/web.php o api.php
Route::get('/api-publico/cargo-tipo/{codiCarg}', [PostulanteController::class, 'cargoTipo']);

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
    Route::get('/api/cargos-por-tipo/{tipo}', [PostulanteController::class, 'getCargosPorTipo']);
    Route::get('/api/cargo-tipo/{codiCarg}', [PostulanteController::class, 'cargoTipo']);
    Route::get('/api/provincias/{depa}', [PostulanteController::class, 'getProvincias']);
    Route::get('/api/distritos/{prov}',  [PostulanteController::class, 'getDistritos']);
    Route::get('/postulantes/{postulante}/ver/{tipo}', [PostulanteController::class, 'verPdfEnvuelto'])
      ->whereIn('tipo', ['cv', 'cul'])
      ->name('postulantes.ver-envuelto');
    Route::get('/postulantes/{postulante}/archivo/{tipo}', [PostulanteController::class, 'verArchivoPostulante'])
      ->whereIn('tipo', ['cv', 'cul'])->name('postulantes.ver-archivo');

    // Puedes proteger la ruta con middleware 'auth' si solo usuarios logueados usan el formulario interno
    Route::get('/api/verificar-lista-negra/{dni}', [PostulanteController::class, 'verificarListaNegra']);
    Route::get('/postulantes/{id}/descargar/{tipo}', [PostulanteController::class, 'descargarArchivo'])
      ->name('postulantes.descargarArchivo');

    Route::patch('/postulantes/{postulante}/estado', [PostulanteController::class, 'updateEstado'])->name('postulantes.estado');

    Route::post('/postulantes/{postulante}/validar', [PostulanteController::class, 'validarPostulante'])->name('postulantes.validar');
  });

  Route::get('/entrevistas', [EntrevistaController::class, 'listadoInicial'])->name('entrevistas.index');
  //Route::get('/entrevistas', [EntrevistaController::class, 'listadoInicial'])->name('entrevistas.listadoInicial');
  Route::get('/entrevistas/evaluar/{id}', [EntrevistaController::class, 'evaluar'])->name('entrevistas.evaluar');


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

  // RUTAS PARA GESTIÓN DE USUARIOS
  // Siempre pon las rutas "específicas" antes de las que usan {user}
  Route::get('/usuarios/buscar-personal', [UserController::class, 'buscarPersonal'])->name('usuarios.buscarPersonal');
  Route::get('usuarios/personal-por-sucursal/{codigo}', [UserController::class, 'personalPorSucursal']);

  // Rutas CRUD
  Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
  Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
  Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
  Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
  Route::post('/usuarios/{user}/habilitar', [UserController::class, 'habilitarUsuario'])->name('usuarios.habilitarUsuario');
  Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
  Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
  Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
  Route::post('/usuarios/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggleStatus');
});

require __DIR__ . '/auth.php';
