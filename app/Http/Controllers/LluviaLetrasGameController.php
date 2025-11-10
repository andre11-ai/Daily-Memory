<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;

class LluviaLetrasGameController extends Controller
{
    public function storeScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'difficulty' => 'required|string',
        ]);

        GameScore::create([
            'user_id' => auth()->id(),
            'game' => 'lluvia-letras',
            'score' => $request->score,
            'difficulty' => $request->difficulty
        ]);

        return response()->json(['message' => 'Score de Lluvia de Letras guardado correctamente']);
    }
}
