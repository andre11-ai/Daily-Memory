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


Route::get('/TiposMemoria/Mmuscular', function () {
    return view('/TiposMemoria/Mmuscular');
})->middleware('auth');

Route::get('/TiposMemoria/Mecoica', function () {
    return view('/TiposMemoria/Mecoica');
})->middleware('auth');

Route::get('/TiposMemoria/Miconica', function () {
    return view('/TiposMemoria/Miconica');
})->middleware('auth');

//Rutas para niveles de memoria muscular

Route::get('/Juegos/Muscular/scary/scary', function () {
    return view('/Juegos/Muscular/scary/scary');
})->middleware('auth');

Route::get('/Juegos/Muscular/scary/scaryM', function () {
    return view('/Juegos/Muscular/scary/scaryM');
})->middleware('auth');

Route::get('/Juegos/Muscular/scary/scaryD', function () {
    return view('/Juegos/Muscular/scary/scaryD');
})->middleware('auth');
