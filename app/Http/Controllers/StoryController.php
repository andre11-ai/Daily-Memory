<?php

namespace App\Http\Controllers;

use App\Models\Progress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoryController extends Controller
{
    public function index()
    {
        $userProgress = Progress::where('user_id', Auth::id())->first();
        $level = $userProgress ? $userProgress->level : 1;

        return view('story', compact('level'));
    }

    public function advanceLevel(Request $request)
    {
        $userProgress = Progress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['level' => 1]
        );

        $userProgress->level++;
        $userProgress->save();

        return redirect()->route('story.index');
    }

    public function showLevel($level)
    {
        $userProgress = Progress::where('user_id', Auth::id())->first();
        $currentLevel = $userProgress ? $userProgress->level : 1;

        if ((int)$level !== (int)$currentLevel) {
            return redirect()->route('story.index');
        }

        $levelConfig = $this->levelConfig();

        if (!isset($levelConfig[$level])) {
            return redirect()->route('story.index');
        }

        $game = $levelConfig[$level];

        return view('/Niveles/story-level', [
            'level' => $level,
            'game'  => $game,
        ]);
    }

    public function completeLevel(Request $request)
    {
        $data = $request->validate([
            'level' => 'required|integer|min:1',
            'score' => 'required|integer|min:0',
        ]);

        $userProgress = Progress::firstOrCreate(
            ['user_id' => Auth::id()],
            ['level' => 1]
        );

        $currentLevel = $userProgress->level;
        $level = $data['level'];
        $score = $data['score'];

        if ($level !== $currentLevel) {
            return response()->json(['ok' => false, 'message' => 'Nivel no disponible'], 403);
        }

        $config = $this->levelConfig();
        if (!isset($config[$level])) {
            return response()->json(['ok' => false, 'message' => 'Configuración no encontrada'], 404);
        }

        $target = $config[$level]['target_score'] ?? 0;
        if ($score < $target) {
            return response()->json([
                'ok' => false,
                'message' => 'Puntaje insuficiente',
                'target_score' => $target
            ], 422);
        }

        $userProgress->level = min($userProgress->level + 1, 20);
        $userProgress->save();

        $xpReward = $config[$level]['xp_reward'] ?? ($target ?: $score);
        GameScore::create([
            'user_id'   => Auth::id(),
            'game'      => 'historia',        
            'score'     => $xpReward,
            'difficulty'=> 'story',          
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Nivel completado',
            'new_level' => $userProgress->level,
            'xp_gained' => $xpReward, 
        ]);
    }

    protected function levelConfig(): array
    {
        return [
            1 => [
                'name' => 'Color (Fácil)',
                'url' => route('niveles.show', ['level' => 1]),
                'type' => 'Icónica',
                'difficulty' => 'facil',
                'description' => 'Inicia con el reto de colores básicos.',
                'target_score' => 6,
                'xp_reward' => 50,
            ],
            2 => [
                'name' => 'Scary (Fácil)',
                'url' => route('niveles.show', ['level' => 2]),
                'type' => 'Muscular',
                'difficulty' => 'facil',
                'description' => 'Practica tipeo con ritmo suave.',
                'target_score' => 12,
                'xp_reward' => 60,
            ],
            3 => [
                'name' => 'Simón (Fácil)',
                'url' => route('niveles.show', ['level' => 3]),
                'type' => 'Icónica',
                'difficulty' => 'facil',
                'description' => 'Secuencias sencillas para memoria icónica.',
                'target_score' => 7,
                'xp_reward' => 70,
            ],
            4 => [
                'name' => 'Memorama (Fácil)',
                'url' => route('niveles.show', ['level' => 4]),
                'type' => 'Icónica',
                'difficulty' => 'facil',
                'description' => 'Encuentra parejas en el memorama inicial.',
                'target_score' => 8,
                'xp_reward' => 80,
            ],
            5 => [
                'name' => 'Repetir Palabra (Fácil)',
                'url' => route('niveles.show', ['level' => 5]),
                'type' => 'Ecoica',
                'difficulty' => 'facil',
                'description' => 'Refuerza memoria auditiva repitiendo palabras.',
                'target_score' => 8,
                'xp_reward' => 90,
            ],
            6 => [
                'name' => 'Velocímetro (Fácil)',
                'url' => route('niveles.show', ['level' => 6]),
                'type' => 'Muscular',
                'difficulty' => 'facil',
                'description' => 'Reacción y velocidad en nivel inicial.',
                'target_score' => 20,
                'xp_reward' => 100,
            ],
            7 => [
                'name' => 'Secuencia de Imágenes (Fácil)',
                'url' => route('niveles.show', ['level' => 7]),
                'type' => 'Icónica',
                'difficulty' => 'facil',
                'description' => 'Ordena secuencias simples de imágenes.',
                'target_score' => 16,
                'xp_reward' => 110,
            ],
            8 => [
                'name' => 'Lluvia de Letras (Fácil)',
                'url' => route('niveles.show', ['level' => 8]),
                'type' => 'Ecoica',
                'difficulty' => 'facil',
                'description' => 'Escucha y responde letras en caída.',
                'target_score' => 25,
                'xp_reward' => 120,
            ],
            9 => [
                'name' => 'Encuentra el Sonido Pareja (Fácil)',
                'url' => route('niveles.show', ['level' => 9]),
                'type' => 'Ecoica',
                'difficulty' => 'facil',
                'description' => 'Asocia sonidos y forma parejas.',
                'target_score' => 15,
                'xp_reward' => 130,
            ],
            10 => [
                'name' => 'Color (Medio)',
                'url' => route('niveles.show', ['level' => 10]),
                'type' => 'Icónica',
                'difficulty' => 'Medio',
                'description' => 'Inicia con el reto de colores básicos.',
                'target_score' => 15,
                'xp_reward' => 50,
            ],
        ];
    }
}
