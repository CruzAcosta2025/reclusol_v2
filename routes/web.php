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

Route::get('/prueba', function () {
  return view('prueba');
})->name('prueba');

Route::get('/', function () {
  return view('welcome');
})->name('welcome');

Route::get('/dashboard', [HomeController::class, 'index'])
  ->middleware(['auth', 'verified'])
  ->name('dashboard');

// Registro externo (publico)
Route::get('/postulantes/registro', [PostulanteController::class, 'formExterno'])->name('postulantes.formExterno');
Route::get('/public/dni-decolecta/{dni}', [PostulanteController::class, 'buscarDniDecolecta'])->middleware(['throttle:20,1'])->name('public.dni.decolecta');
Route::post('/postulantes/registro', [PostulanteController::class, 'storeExterno'])->name('postulantes.storeExterno');
Route::get('/api-publico/cargos-por-tipo/{tipo}', [PostulanteController::class, 'getCargosPorTipo']);
Route::get('/api-publico/provincias/{depa}', [PostulanteController::class, 'getProvincias']);
Route::get('/api-publico/distritos/{prov}',  [PostulanteController::class, 'getDistritos']);
Route::get('/api-publico/cargo-tipo/{codiCarg}', [PostulanteController::class, 'cargoTipo']);

Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
  Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
  // Registro interno (solo usuarios logueados)
  Route::middleware(['auth', 'role:ADMINISTRADOR|USUARIO OPERATIVO'])->group(function () {
    Route::get('/postulantes/crear', [PostulanteController::class, 'formInterno'])->name('postulantes.formInterno');
    Route::get('/postulantes/dni-decolecta/{dni}', [PostulanteController::class, 'buscarDniDecolecta'])->name('postulantes.dni.decolecta');
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
    Route::get('/postulantes/{postulante}/ver/{tipo}', [PostulanteController::class, 'verPdfEnvuelto'])->whereIn('tipo', ['cv', 'cul'])->name('postulantes.ver-envuelto');
    Route::get('/postulantes/{postulante}/archivo/{tipo}', [PostulanteController::class, 'verArchivoPostulante'])->whereIn('tipo', ['cv', 'cul'])->name('postulantes.ver-archivo');
    Route::get('/api/verificar-lista-negra/{dni}', [PostulanteController::class, 'verificarListaNegra']);
    Route::get('/postulantes/{id}/descargar/{tipo}', [PostulanteController::class, 'descargarArchivo'])->name('postulantes.descargarArchivo');
    Route::patch('/postulantes/{postulante}/estado', [PostulanteController::class, 'updateEstado'])->name('postulantes.estado');
    Route::post('/postulantes/{postulante}/validar', [PostulanteController::class, 'validarPostulante'])->name('postulantes.validar');
  });

  // RUTAS PARA REQUERIMIENTOS
  Route::middleware(['auth', 'role:ADMINISTRADOR|USUARIO OPERATIVO'])->group(function () {
    Route::get('/requerimientos/registro', [RequerimientoController::class, 'mostrar'])->name('requerimientos.requerimiento');
    Route::post('/requerimientos', [RequerimientoController::class, 'store'])->name('requerimientos.store');
    Route::get('/requerimientos/filtrar', [RequerimientoController::class, 'filtrar'])->name('requerimientos.filtrar');
    Route::delete('/requerimientos/{requerimiento}', [RequerimientoController::class, 'destroy'])->name('requerimientos.destroy');
    Route::get('/requerimientos/{requerimiento}/edit', [RequerimientoController::class, 'edit'])->name('requerimientos.edit');
    Route::put('/requerimientos/{requerimiento}', [RequerimientoController::class, 'update'])->name('requerimientos.update');
    Route::get('/api/cargos', [CargoController::class, 'cargosPorTipo'])->name('api.cargos');
    Route::get('/requerimientos/clientes-por-sucursal', [RequerimientoController::class, 'clientesPorSucursalSP'])->name('requerimientos.clientes_por_sucursal');
    Route::get('/cargos', [CargoController::class, 'index']);
    Route::get('/cargos/{id}', [CargoController::class, 'show']);
    Route::get('/api/tipos-cargo', [RequerimientoController::class, 'tiposPorTipoPersonal'])->name('api.tipos_cargo');
    Route::get('/api/cargos', [RequerimientoController::class, 'cargosPorTipo'])->name('api.cargos');
  });

  // RUTAS PARA AFICHES 
  Route::middleware('role:ADMINISTRADOR|USUARIO OPERATIVO')->group(function () {
    Route::get('/afiches', [PosterController::class, 'index'])->name('afiches.index');
    //Route::get('/afiches/agregarRecursos', [PosterController::class, 'mostrarFormularioRecursos'])->name('afiches.recursos');
    // Formulario para cargar recursos (la vista que ya hiciste)
    Route::get('/afiches/recursos', [PosterController::class, 'assetsForm'])->name('afiches.assets.form');
    // Procesar el formulario (SUBIR archivo)
    Route::post('/afiches/recursos', [PosterController::class, 'assetsUpload'])->name('afiches.assets.upload');
    Route::post('/afiches/recursos/eliminar', [PosterController::class, 'assetsDelete'])->name('afiches.assets.delete');
    Route::get('/poster/{req}/{template}', [PosterController::class, 'show'])->name('poster.show');
    Route::get('/historial-afiches', [HistorialController::class, 'index'])->name('afiches.historial');
  });


  Route::middleware('role:ADMINISTRADOR|USUARIO OPERATIVO')->group(function () {
    // RUTAS PARA ENTREVISTAS
    Route::get('/entrevistas', [EntrevistaController::class, 'listadoInicial'])->name('entrevistas.index');
    Route::get('/entrevistas/evaluar/{postulante}', [EntrevistaController::class, 'evaluar'])->name('entrevistas.evaluar');
    Route::get('/entrevistas-virtuales/aptos', [EntrevistaController::class, 'aptos'])->name('entrevistas-virtuales.aptos');
    Route::post('/entrevistas/{postulante}/guardar', [EntrevistaController::class, 'guardarEvaluacion'])->name('entrevistas.guardar-evaluacion');
    Route::get('/entrevistas/{postulante}/archivo/{tipo}', [EntrevistaController::class, 'verArchivo'])->whereIn('tipo', ['cv', 'cul'])->name('entrevistas.ver-archivo');
    Route::get('/entrevistas/{postulante}/descargar/{tipo}', [EntrevistaController::class, 'descargarArchivo'])
      ->whereIn('tipo', ['cv', 'cul'])
      ->name('entrevistas.descargar-archivo');
  });

  // RUTAS PARA GESTIÓN DE USUARIOS
  Route::middleware('role:ADMINISTRADOR')->group(function () {
    Route::get('/usuarios/buscar-personal', [UserController::class, 'buscarPersonal'])->name('usuarios.buscarPersonal');
    Route::get('usuarios/personal-por-sucursal/{codigo}', [UserController::class, 'personalPorSucursal']);
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
    Route::post('/usuarios/{user}/habilitar', [UserController::class, 'habilitarUsuario'])->name('usuarios.habilitarUsuario');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/usuarios/{user}',      [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::get('/usuarios/dni-decolecta/{dni}', [UserController::class, 'buscarDniDecolecta'])->middleware(['auth', 'throttle:30,1'])->name('usuarios.dni.decolecta');
    Route::post('/usuarios/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggleStatus');
  });
});

require __DIR__ . '/auth.php';
