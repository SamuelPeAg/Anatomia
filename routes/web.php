<?php

use App\Http\Controllers\TipoMuestraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InformeController;
use App\Http\Controllers\ExpedienteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Página Principal
Route::get('/', [DashboardController::class, 'index'])->name("inicio");

// Autenticación
Route::get('/login', function () {
    return view('sesion.login');
})->name("login");

Route::post("/login", [UserController::class, "login"]);

Route::get('/register', function () {
    return view('sesion.register');
})->name("register");

Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::post('/logout', [UserController::class, 'logout'])->name('logout');

// Rutas Protegidas
Route::middleware(['auth'])->group(function () {
    
    Route::get('/home', [DashboardController::class, 'index'])->name("home");

    Route::get("/nuevo_informe", [InformeController::class, "create"])->name("nuevo informe");

    Route::post("/guardar_informe", [InformeController::class, "store"])->name("guardar_informe");

    Route::post("/informe/sin-fase", [InformeController::class, "errorSinFase"])->name("informes.sin-fase");

    Route::get("/revision", [InformeController::class, "index"])->name("revision");
    Route::get("/revision/{informe}/editar", [InformeController::class, "edit"])->name("informes.edit");
    Route::put("/informe/{informe}/actualizar", [InformeController::class, "update"])->name("informes.update");
    Route::patch("/informe/{informe}/revisar", [InformeController::class, "revisar"])->name("informes.revisar");
    Route::delete("/informe/{informe}", [InformeController::class, "destroy"])->name("informes.destroy");
    Route::delete("/informes/imagen/{imagen}", [InformeController::class, "destroyImagen"])->name("imagen.destroy");

    Route::get('/tipos/{tipo}/siguiente-codigo', [TipoMuestraController::class, 'siguienteCodigo'])
        ->name('tipos.siguienteCodigo');

});

// Portal de Pacientes (Acceso Público con Email)

Route::get('/paciente/acceso', [ExpedienteController::class, 'showAcceso'])->name('paciente.acceso');
Route::post('/paciente/acceso', [ExpedienteController::class, 'acceder'])->name('paciente.login');
Route::get('/paciente/mis-informes', [ExpedienteController::class, 'misInformes'])->name('paciente.informes');

// Páginas Estáticas del Footer
Route::get('/ayuda', function () { return view('paginas.ayuda'); })->name('ayuda');
Route::get('/privacidad', function () { return view('paginas.privacidad'); })->name('privacidad');
Route::get('/terminos', function () { return view('paginas.terminos'); })->name('terminos');
Route::get('/configuracion', function () { return view('paginas.configuracion'); })->name('configuracion');
