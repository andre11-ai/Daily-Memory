<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;


//Pagina principal
Route::get('/', function () {
    return view('index');
});

//Inicio de sesion y registro
Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/signin', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//Admin
Route::get('/admin', function () {
    return view('admin');
})->middleware('auth');

//Menu principal
Route::get('/menu', function () {
    return view('menu');
})->middleware('auth');

//Perfil
Route::get('/perfil', function () {
    return view('perfil');
})->name('perfil');

//Cerrar sesion
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/menu');
})->name('logout');

//Tipos de memoria
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

//Rutas para niveles de memoria Iconica
Route::get('/Juegos/Iconica/Sodoku/Sodoku', function () {
    return view('/Juegos/Iconica/Sodoku/Sodoku');
})->middleware('auth');

//Chat
Route::get('/chat', function () {
    return view('/chat');
})->middleware('auth');

//Chat Mundial
Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/chat/mundial/messages', [ChatController::class, 'fetchMessages'])->middleware('auth');
Route::post('/chat/mundial/send', [ChatController::class, 'sendMessage'])->middleware('auth');

//Chat por Grupos
Route::get('/chat/grupos', [ChatController::class, 'fetchGroups'])->middleware('auth');
Route::get('/chat/grupo/{id}/messages', [ChatController::class, 'fetchGroupMessages'])->middleware('auth');
Route::post('/chat/grupo/{id}/send', [ChatController::class, 'sendGroupMessage'])->middleware('auth');
Route::post('/chat/grupos/create', [ChatController::class, 'createGroup'])->middleware('auth');
Route::get('/chat/grupos', [ChatController::class, 'fetchGroups'])->middleware('auth');
Route::get('/chat/grupo/{id}/info', [ChatController::class, 'groupInfo'])->middleware('auth');
Route::post('/chat/grupo/{id}/invite', [ChatController::class, 'inviteUser'])->middleware('auth');
Route::post('/chat/grupo/{id}/leave', [ChatController::class, 'leaveGroup'])->middleware('auth');
Route::post('/chat/grupo/{id}/delete', [ChatController::class, 'deleteGroup'])->middleware('auth');
Route::get('/chat/grupo/{id}/messages', [ChatController::class, 'fetchGroupMessages'])->middleware('auth');
Route::post('/chat/grupo/{id}/send', [ChatController::class, 'sendGroupMessage'])->middleware('auth');
Route::get('/chat/grupo/{id}/search-user', [ChatController::class, 'searchUserForGroup'])->middleware('auth');

//Chat Personal
Route::get('/chat/personal/search', [ChatController::class, 'personalSearch'])->middleware('auth');
Route::get('/chat/personal/{id}/messages', [ChatController::class, 'personalMessages'])->middleware('auth');
Route::post('/chat/personal/{id}/send', [ChatController::class, 'sendPersonalMessage'])->middleware('auth');
Route::get('/chat/personal/last-chats', [ChatController::class, 'lastChats']);
Route::post('/chat/personal/{userId}/delete', [ChatController::class, 'deletePersonalChat'])->middleware('auth');

