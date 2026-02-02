<?php

use App\Http\Controllers\TipoMuestraController;
use App\Http\Controllers\UserController;
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
    
    Route::get('/welcome', function () {
        return view('welcome');
    })->name("home");

    Route::get("/nuevo_informe", function () {
        return view("nuevoinforme");
    })->name("nuevo informe");

    Route::post("/guardar_informe", [TipoMuestraController::class, "store"])->name("guardar_informe");

    Route::get("/revision", function () {
        return view("revision");
    })->name("revision");

    Route::get('/tipos/{tipo}/siguiente-codigo', [TipoMuestraController::class, 'siguienteCodigo'])
        ->name('tipos.siguienteCodigo');
});
