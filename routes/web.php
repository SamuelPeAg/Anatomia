<?php

use App\Http\Controllers\TipoMuestraController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InformeController;
use Illuminate\Support\Facades\Route;


// Página Principal
Route::get('/', function () {
    return view('inicio');
})->name("inicio");

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
    
    Route::get('/home', function () {
        return view('inicio');
    })->name("home");

    Route::get("/nuevo_informe", function () {
        return view("nuevoinforme");
    })->name("nuevo informe");

    Route::post("/guardar_informe", [InformeController::class, "store"])->name("guardar_informe");

    Route::get("/revision", [InformeController::class, "index"])->name("revision");
    Route::get("/revision/{informe}/editar", [InformeController::class, "edit"])->name("informes.edit");
    Route::put("/informe/{informe}/actualizar", [InformeController::class, "update"])->name("informes.update");

    Route::get('/tipos/{tipo}/siguiente-codigo', [TipoMuestraController::class, 'siguienteCodigo'])
        ->name('tipos.siguienteCodigo');
});

// Portal de Pacientes (Acceso Público con Email)
use App\Http\Controllers\ExpedienteController;
Route::get('/paciente/acceso', [ExpedienteController::class, 'showAcceso'])->name('paciente.acceso');
Route::post('/paciente/acceso', [ExpedienteController::class, 'acceder'])->name('paciente.login');
Route::get('/paciente/mis-informes', [ExpedienteController::class, 'misInformes'])->name('paciente.informes');
