<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;

class VelocimetroGameController extends Controller
{
    public function storeScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'difficulty' => 'required|string',
        ]);

        GameScore::create([
            'user_id' => auth()->id(),
            'game' => 'velocimetro',
            'score' => $request->score,
            'difficulty' => $request->difficulty
        ]);

        return response()->json(['message' => 'Score de Velocímetro guardado correctamente']);
    }
}
