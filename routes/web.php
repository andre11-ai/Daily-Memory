<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ScaryGameController;
use App\Http\Controllers\VelocimetroGameController;
use App\Http\Controllers\LluviaLetrasGameController;
use App\Http\Controllers\SonidoParejaGameController;
use App\Http\Controllers\SimondiceGameController;
use App\Http\Controllers\RepetirPalabraGameController;
use App\Http\Controllers\ColorGameController;
use App\Http\Controllers\SecuenciaColorGameController;
use App\Http\Controllers\MemoramaGameController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoryController;


//Pagina principal
Route::get('/', function () {
    return view('index');
});

//Inicio de sesion y registro
Route::get('/login', function () { return view('login'); })->name('login');
Route::post('/signin', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

//Admin
Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])
    ->name('admin.dashboard');
Route::get('/admin/api/users', [AdminController::class, 'usersList'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/users/{id}', [AdminController::class, 'show'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::match(['put','post'], '/admin/api/users/{id}', [AdminController::class, 'update'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::delete('/admin/api/users/{id}', [AdminController::class, 'destroy'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/meta', [AdminController::class, 'statsMeta'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/scores', [AdminController::class, 'statsScores'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/top-games', [AdminController::class, 'statsTopGames'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/top-difficulty', [AdminController::class, 'statsTopDifficulty'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/memory-types', [AdminController::class, 'statsMemoryTypes'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/difficulty-counts', [AdminController::class, 'statsDifficultyCounts'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
Route::get('/admin/api/stats/scatter-plays', [AdminController::class, 'statsScatterPlays'])
    ->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);


//Menu principal
Route::get('/menu', function () {
    return view('menu');
})->middleware('auth');

//Perfil
Route::group([], function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show')->middleware('auth');
    Route::get('/profile', [ProfileController::class, 'show'])->middleware('auth'); // alias opcional
});
Route::group(['middleware' => 'auth'], function () {
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
});
Route::get('/user-avatar/{path}', [ProfileController::class, 'avatar'])
    ->where('path', '.*')
    ->name('user.avatar');
Route::get('/user-avatar/{path}', [ProfileController::class, 'avatar'])
    ->where('path', '.*')
    ->name('user.avatar');
Route::get('/profile/stats', [\App\Http\Controllers\ProfileController::class, 'statsJson'])
    ->name('profile.stats')
    ->middleware('auth');


//Cerrar sesion
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/menu');
})->name('logout');

//Desestresar
Route::get('/Desestresar', function () {
    return view('Desestresar');
})->middleware('auth');

//Juegos para Desestresar
Route::get('/Desestresar/Galaxy-attack', function () {
    return view('/Desestresar/Galaxy-attack');
})->middleware('auth');

Route::get('/Desestresar/Tetris', function () {
    return view('/Desestresar/Tetris');
})->middleware('auth');



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

//Scary
Route::get('/Juegos/Muscular/scary/scary', function () {
    return view('/Juegos/Muscular/scary/scary');
})->middleware('auth');
Route::get('/Juegos/Muscular/scary/scaryM', function () {
    return view('/Juegos/Muscular/scary/scaryM');
})->middleware('auth');
Route::get('/Juegos/Muscular/scary/scaryD', function () {
    return view('/Juegos/Muscular/scary/scaryD');
})->middleware('auth');
Route::post('/scary-game/score', [ScaryGameController::class, 'storeScore'])->middleware('auth');

//Velocimetro
Route::get('/Juegos/Muscular/Velocimetro/velocimetroB', function () {
    return view('/Juegos/Muscular/Velocimetro/velocimetroB');
})->middleware('auth');
Route::get('/Juegos/Muscular/Velocimetro/velocimetroM', function () {
    return view('/Juegos/Muscular/Velocimetro/velocimetroM');
})->middleware('auth');
Route::get('/Juegos/Muscular/Velocimetro/velocimetroD', function () {
    return view('/Juegos/Muscular/Velocimetro/velocimetroD');
})->middleware('auth');
Route::post('/velocimetro-game/score', [VelocimetroGameController::class, 'storeScore'])->middleware('auth');

//Lluvia de letras
Route::get('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasB', function () {
    return view('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasB');
})->middleware('auth');
Route::get('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM', function () {
    return view('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasM');
})->middleware('auth');
Route::get('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasD', function () {
    return view('/Juegos/Muscular/Lluvia-Letras/lluvia-LetrasD');
})->middleware('auth');
Route::post('/lluvia-letras-game/score', [LluviaLetrasGameController::class, 'storeScore'])->middleware('auth');

//Rutas para niveles de memoria Iconica

//Memorizar Color
Route::get('/Juegos/Iconica/Color/colorB', function () {
    return view('/Juegos/Iconica/Color/colorB');
});
Route::get('/Juegos/Iconica/Color/colorM', function () {
    return view('/Juegos/Iconica/Color/colorM');
});
Route::get('/Juegos/Iconica/Color/colorD', function () {
    return view('/Juegos/Iconica/Color/colorD');
});
Route::post('/color-game/score', [ColorGameController::class, 'storeScore'])->middleware('auth');

//Memorama
Route::get('/Juegos/Iconica/Memorama/memoramaB', function () {
    return view('/Juegos/Iconica/Memorama/memoramaB');
})->middleware('auth');
Route::get('/Juegos/Iconica/Memorama/memoramaM', function () {
    return view('/Juegos/Iconica/Memorama/memoramaM');
})->middleware('auth');
Route::get('/Juegos/Iconica/Memorama/memoramaD', function () {
    return view('/Juegos/Iconica/Memorama/memoramaD');
})->middleware('auth');
Route::post('/memorama-game/score', [MemoramaGameController::class, 'storeScore'])->middleware('auth');

//Secuencia
Route::get('/Juegos/Iconica/Secuencia/secuenciaB', function () {
    return view('/Juegos/Iconica/Secuencia/secuenciaB');
})->middleware('auth');
Route::get('/Juegos/Iconica/Secuencia/secuenciaM', function () {
    return view('/Juegos/Iconica/Secuencia/secuenciaM');
})->middleware('auth');
Route::get('/Juegos/Iconica/Secuencia/secuenciaD', function () {
    return view('/Juegos/Iconica/Secuencia/secuenciaD');
})->middleware('auth');
Route::post('/secuencia-color-game/score', [SecuenciaColorGameController::class, 'storeScore'])->middleware('auth');

//Rutas para niveles de memoria Ecoica

//Simon
Route::get('/Juegos/Ecoica/Simon/simonB', function () {
    return view('/Juegos/Ecoica/Simon/simonB');
})->middleware('auth');
Route::get('/Juegos/Ecoica/Simon/simonM', function () {
    return view('/Juegos/Ecoica/Simon/simonM');
})->middleware('auth');
Route::get('/Juegos/Ecoica/Simon/simonD', function () {
    return view('/Juegos/Ecoica/Simon/simonD');
})->middleware('auth');
Route::post('/simondice-game/score', [SimondiceGameController::class, 'storeScore'])->middleware('auth');

//Repetir la Palabra
Route::get('/Juegos/Ecoica/repetirPalabra/repetirPalabraB', function () {
    return view('/Juegos/Ecoica/repetirPalabra/repetirPalabraB');
})->middleware('auth');
Route::get('/Juegos/Ecoica/repetirPalabra/repetirPalabraM', function () {
    return view('/Juegos/Ecoica/repetirPalabra/repetirPalabraM');
})->middleware('auth');
Route::get('/Juegos/Ecoica/repetirPalabra/repetirPalabraD', function () {
    return view('/Juegos/Ecoica/repetirPalabra/repetirPalabraD');
})->middleware('auth');
Route::post('/repetir-palabra-game/score', [RepetirPalabraGameController::class, 'storeScore'])->middleware('auth');

//Encuentra  el Sonido Pareja
Route::get('/Juegos/Ecoica/sonidoPareja/sonidoParejaB', function () {
    return view('/Juegos/Ecoica/sonidoPareja/sonidoParejaB');
})->middleware('auth');
Route::get('/Juegos/Ecoica/sonidoPareja/sonidoParejaM', function () {
    return view('/Juegos/Ecoica/sonidoPareja/sonidoParejaM');
})->middleware('auth');
Route::get('/Juegos/Ecoica/sonidoPareja/sonidoParejaD', function () {
    return view('/Juegos/Ecoica/sonidoPareja/sonidoParejaD');
})->middleware('auth');
Route::post('/sonido-pareja-game/score', [SonidoParejaGameController::class, 'storeScore'])->middleware('auth');


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


//Historia
Route::middleware(['auth'])->group(function () {
    Route::get('/story', [StoryController::class, 'index'])->name('story.index');
    Route::post('/story/advance', [StoryController::class, 'advanceLevel'])->name('story.advance');
    Route::post('/story/complete-level', [StoryController::class, 'completeLevel'])->name('story.complete');
    Route::get('/niveles/{level}', function ($level) {
        $progress = \App\Models\Progress::firstOrCreate(
            ['user_id' => \Illuminate\Support\Facades\Auth::id()],
            ['level' => 1]
        );
        if ((int)$level !== (int)$progress->level) {
            return redirect()->route('story.index');
        }

        $viewName = "Niveles.nivel-{$level}";

        if (!view()->exists($viewName)) {
            $altView = "Niveles.Nivel-{$level}";
            if (view()->exists($altView)) {
                return view($altView);
            }
            abort(404, "Vista de nivel no encontrada: {$viewName}");
        }

        return view($viewName);
    })->where('level', '[0-9]+')->name('niveles.show');
});
