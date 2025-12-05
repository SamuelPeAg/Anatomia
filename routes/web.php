<?php


use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Inicio de sesion
Route::get('/', function () {
    return view('login');
})->name("login.form");

Route::post("/login",[UserController::class,"login"])->name("login");


//Pagina principal
Route::get('/welcome', function () {
    return view('welcome');
})->name("home");


//Registro de usuarios 
Route::post('/users', [UserController::class, 'store'])->name('users.store');

Route::get('/register', function () {
    return view('register');
})->name("register");
