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

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/menu');
})->name('logout');

Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

Route::get('/tipomemoria', function () {
    return view('tipomemoria');
})->middleware('auth');


Route::get('Mmuscular', function () {
    return view('Mmuscular');
})->middleware('auth');

Route::get('Mecoica', function () {
    return view('Mecoica');
})->middleware('auth');

Route::get('Miconica', function () {
    return view('Miconica');
})->middleware('auth');


