<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Index');
});

Route::post("/login",[LoginController::class,"store"])->name("store");
