<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GameScore;

class SecuenciaColorGameController extends Controller
{
    public function storeScore(Request $request)
    {
        $request->validate([
            'score' => 'required|integer',
            'difficulty' => 'required|string',
        ]);
        GameScore::create([
            'user_id' => auth()->id(),
            'game' => 'secuencia-color',
            'score' => $request->score,
            'difficulty' => $request->difficulty
        ]);
        return response()->json(['message' => 'Score de Secuencia Color guardado correctamente']);
    }
}