<?php


use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Inicio de sesion
Route::get('/', function () {
    return view('sesion.login');
})->name("login.form");

Route::post("/login",[UserController::class,"login"])->name("login");


// nuevo informe
Route::get("/nuevo_informe",function(){
    return view("nuevoinforme");

})->name("nuevo informe");

//revision informes
Route::get("/revision",function(){
    return view("revision");

})->name("revision");


//Pagina principal
Route::get('/welcome', function () {
    return view('welcome');
})->name("home");


//Registro de usuarios 
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/register', function () {
    return view('sesion.register');
})->name("register");
