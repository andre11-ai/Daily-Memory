<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\GameScore;


class ProfileController extends Controller
{
    // Mostrar el formulario de edición
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Actualizar el perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required','string','max:30','regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
            'appe' => ['required','string','max:40','regex:/^[a-zA-ZáéíóúÁÉÍÓÚ\s]+$/'],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'username' => [
                'required',
                'string',
                'min:4',
                'max:16',
                'regex:/^[a-zA-Z0-9_]+$/',
                Rule::unique('users')->ignore($user->id)
            ],
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ],[
            'name.regex' => 'Solo letras y espacios.',
            'appe.regex' => 'Solo letras y espacios.',
            'username.regex' => 'Solo letras, números y guion bajo.',
            'profile_image.max' => 'La imagen debe pesar menos de 2MB.'
        ]);

        if ($request->hasFile('profile_image')) {
            // Elimina imagen anterior si existe
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            // Guarda la nueva imagen
            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->appe = $request->appe;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->save();

        return redirect()->route('profile.edit')->with('success','Perfil actualizado correctamente');
    }

     public function show()
    {
        $user = Auth::user();

        // Scores máximos por juego
        $scoresPorJuego = GameScore::where('user_id', $user->id)
            ->select('game')
            ->selectRaw('MAX(score) as max_score')
            ->groupBy('game')
            ->pluck('max_score', 'game')
            ->toArray();

        // Score general (total)
        $scoreGeneral = GameScore::where('user_id', $user->id)->sum('score');

        // Partidas (juegos) jugados
        $juegosJugados = GameScore::where('user_id', $user->id)->count();

        // Score por memoria (juegos de memoria)
        $juegosMemoria = ['lluvia-letras', 'repetir-palabra', 'simondice', 'sonido-pareja', 'velocimetro'];
        $scoreMemoria = GameScore::where('user_id', $user->id)
            ->whereIn('game', $juegosMemoria)
            ->sum('score');

        // Promedio de score por partida
        $promedioScore = $juegosJugados > 0 ? round($scoreGeneral / $juegosJugados, 2) : 0;

        // Mejor score en una partida
        $mejorScore = GameScore::where('user_id', $user->id)->max('score');

        // Ejemplo de cómo podrías manejar el tiempo total jugado
        // (debes tener un campo duration en tu tabla game_scores, aquí es ejemplo)
        // $tiempoTotal = GameScore::where('user_id', $user->id)->sum('duration');
        // $tiempoTotalFormato = gmdate('H:i:s', $tiempoTotal);

        // Nivel (ejemplo: cada 1000 puntos = 1 nivel)
        $nivel = intval($scoreGeneral / 1000) + 1;

        return view('profile.show', compact(
            'user',
            'scoresPorJuego',
            'scoreGeneral',
            'scoreMemoria',
            'juegosJugados',
            'promedioScore',
            'mejorScore',
            'nivel'
            // , 'tiempoTotalFormato'
        ));
    }
}
