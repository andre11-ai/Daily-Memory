<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('index');
});

Route::get('/login', function () { return view('login'); })->name('login');

Route::post('/signin', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/admin', function () {
    return view('admin');
})->middleware('auth');

Route::get('/menu', function () {
    return view('menu');
})->middleware('auth');
