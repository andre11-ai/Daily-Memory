<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;

class SonidoParejaGameController extends Controller
{
    public function storeScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'difficulty' => 'required|string',
        ]);

        GameScore::create([
            'user_id' => auth()->id(),
            'game' => 'sonido-pareja',
            'score' => $request->score,
            'difficulty' => $request->difficulty
        ]);

        return response()->json(['message' => 'Score de Sonido Pareja guardado correctamente']);
    }
}
