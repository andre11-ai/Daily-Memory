<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\GameScore;
use App\Models\Progress;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('perfil_editar', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => ['required','string','max:255'],
            'appe' => ['nullable','string','max:255'],
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
            'username' => ['required','string','max:255', Rule::unique('users')->ignore($user->id)],
            'profile_image' => ['nullable','image','max:2048'],
            'password' => ['nullable','string','min:8','confirmed'],
        ];

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        $user->appe = $validated['appe'] ?? $user->appe;
        $user->email = $validated['email'];
        $user->username = $validated['username'];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('avatars','public');
            $user->profile_image = $path;
        }

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado correctamente.');
    }

    private function computeLevelInfo(int $totalPoints)
    {
        $base = 800;
        $exponent = 1.45;

        $remaining = $totalPoints;
        $level = 1;

        while (true) {
            $needed = (int) floor($base * pow($level, $exponent));
            if ($needed < 1) $needed = 1;
            if ($remaining < $needed) {
                $xp_into_level = $remaining;
                $xp_for_next = $needed;
                $progress = $xp_for_next > 0 ? round(($xp_into_level / $xp_for_next) * 100, 2) : 100.0;

                return [
                    'level' => $level,
                    'xp_into_level' => $xp_into_level,
                    'xp_for_next' => $xp_for_next,
                    'progress' => $progress,
                    'total_points' => $totalPoints
                ];
            }

            $remaining -= $needed;
            $level++;

        }
    }

    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $scoresPorJuego = GameScore::where('user_id', $user->id)
            ->select('game')
            ->selectRaw('MAX(score) as max_score')
            ->groupBy('game')
            ->pluck('max_score', 'game')
            ->toArray();

        $scoreGeneral = GameScore::where('user_id', $user->id)->sum('score');
        $juegosJugados = GameScore::where('user_id', $user->id)->count();
        $juegosMemoria = ['lluvia-letras', 'repetir-palabra', 'simondice', 'sonido-pareja', 'velocimetro'];
        $scoreMemoria = GameScore::where('user_id', $user->id)
            ->whereIn('game', $juegosMemoria)
            ->sum('score');
        $subtotalesMemoria = GameScore::where('user_id', $user->id)
            ->whereIn('game', $juegosMemoria)
            ->select('game')
            ->selectRaw('SUM(score) as total')
            ->groupBy('game')
            ->pluck('total', 'game')
            ->toArray();
        $promedioScore = $juegosJugados > 0 ? round($scoreGeneral / $juegosJugados, 2) : 0;
        $mejorScore = GameScore::where('user_id', $user->id)->max('score');
        $levelInfo = $this->computeLevelInfo((int)$scoreGeneral);
        $nivel = $levelInfo['level'];
        $memoryCounts = [];
        $memoryTotals = [];
        $memoryTypes = ['muscular','iconica','ecoica'];
        $table = (new GameScore)->getTable();
        $difficulties = [];

        if (Schema::hasColumn($table, 'memory_type') && Schema::hasColumn($table, 'difficulty')) {
            $rows = GameScore::where('user_id', $user->id)
                ->select('memory_type', 'difficulty', DB::raw('COUNT(*) as plays'))
                ->groupBy('memory_type', 'difficulty')
                ->get();

            $difficulties = GameScore::where('user_id', $user->id)
                ->whereNotNull('difficulty')
                ->groupBy('difficulty')
                ->pluck('difficulty')
                ->map(function($v){ return (string)$v; })
                ->toArray();

            foreach ($rows as $r) {
                $mt = strtolower($r->memory_type ?? 'otros');
                $diff = strtolower($r->difficulty ?? 'sin-dificultad');
                $memoryCounts[$mt][$diff] = ($memoryCounts[$mt][$diff] ?? 0) + (int)$r->plays;
                $memoryTotals[$mt] = ($memoryTotals[$mt] ?? 0) + (int)$r->plays;
                if (!in_array($mt, $memoryTypes)) $memoryTypes[] = $mt;
            }
        } else {
            $mapping = [
                'muscular' => ['velocimetro', 'simondice'],
                'iconica'  => ['lluvia-letras', 'repetir-palabra'],
                'ecoica'   => ['sonido-pareja'],
            ];

            $difficulties = GameScore::where('user_id', $user->id)
                ->whereNotNull('difficulty')
                ->groupBy('difficulty')
                ->pluck('difficulty')
                ->map(function($v){ return (string)$v; })
                ->toArray();

            foreach ($mapping as $mem => $gamesList) {
                $memoryCounts[$mem] = [];
                $memoryTotals[$mem] = 0;
                $rows = GameScore::where('user_id', $user->id)
                    ->whereIn('game', $gamesList)
                    ->select('difficulty', DB::raw('COUNT(*) as plays'))
                    ->groupBy('difficulty')
                    ->get();

                foreach ($rows as $r) {
                    $diff = strtolower($r->difficulty ?? 'sin-dificultad');
                    $memoryCounts[$mem][$diff] = ($memoryCounts[$mem][$diff] ?? 0) + (int)$r->plays;
                    $memoryTotals[$mem] = ($memoryTotals[$mem] ?? 0) + (int)$r->plays;
                }
            }

            $memoryTypes = array_keys($mapping);
        }

        if (empty($difficulties)) {
            $difficulties = ['facil','media','dificil'];
        }

        $difficulties = array_values(array_unique(array_map('strtolower', $difficulties)));

        foreach ($memoryTypes as $mt) {
            foreach ($difficulties as $d) {
                $memoryCounts[$mt][$d] = $memoryCounts[$mt][$d] ?? 0;
            }
            $memoryTotals[$mt] = $memoryTotals[$mt] ?? array_sum($memoryCounts[$mt] ?? []);
        }


        $allDates = [];
        $perGameByDate = [];

        $games = GameScore::where('user_id', $user->id)
            ->select('game')
            ->groupBy('game')
            ->pluck('game')
            ->toArray();

        foreach ($games as $game) {
            $rows = GameScore::where('user_id', $user->id)
                ->where('game', $game)
                ->orderBy('created_at')
                ->get(['score', 'created_at']);

            $best = null;
            $byDate = [];
            foreach ($rows as $r) {
                $s = intval($r->score);
                if ($best === null || $s > $best) $best = $s;
                $date = $r->created_at ? $r->created_at->format('Y-m-d') : null;
                if ($date === null) continue;
                $byDate[$date] = $best;
                $allDates[$date] = true;
            }

            if (!empty($byDate)) {
                ksort($byDate);
                $perGameByDate[$game] = $byDate;
            }
        }

        $labels = array_keys($allDates);
        sort($labels);

        $chartDatasets = [];
        foreach ($perGameByDate as $game => $map) {
            $data = [];
            $currentBest = null;
            foreach ($labels as $date) {
                if (isset($map[$date])) {
                    $currentBest = $map[$date];
                    $data[] = $currentBest;
                } else {
                    $data[] = $currentBest !== null ? $currentBest : null;
                }
            }
            $chartDatasets[] = [
                'label' => $game,
                'data'  => $data,
            ];
        }


        $sourceRows = [];
        $memoryScoreTotals = [];
        $difficultyTotals = [];

        if (Schema::hasColumn($table, 'memory_type') && Schema::hasColumn($table, 'difficulty')) {
            $rowsScore = GameScore::where('user_id', $user->id)
                ->select('memory_type','difficulty','score','created_at')
                ->orderBy('created_at')
                ->get();

            foreach ($rowsScore as $r) {
                $mem = strtolower($r->memory_type ?? 'otros');
                $diff = strtolower($r->difficulty ?? 'sin-dificultad');
                $date = $r->created_at ? $r->created_at->format('Y-m-d') : null;
                $val = (int)$r->score;

                $sourceRows[] = [
                    'memory' => $mem,
                    'difficulty' => $diff,
                    'score' => $val,
                    'date' => $date
                ];

                $memoryScoreTotals[$mem] = ($memoryScoreTotals[$mem] ?? 0) + $val;
                $difficultyTotals[$diff] = ($difficultyTotals[$diff] ?? 0) + $val;
                if (!in_array($mem, $memoryTypes)) $memoryTypes[] = $mem;
            }
        } else {
            $mapping = [
                'muscular' => ['velocimetro','simondice'],
                'iconica'  => ['lluvia-letras','repetir-palabra'],
                'ecoica'   => ['sonido-pareja'],
            ];

            $rowsScore = GameScore::where('user_id', $user->id)
                ->select('game','difficulty','score','created_at')
                ->orderBy('created_at')
                ->get();

            foreach ($rowsScore as $r) {
                $game = strtolower($r->game);
                $memType = 'otros';
                foreach ($mapping as $mem => $gamesList) {
                    if (in_array($game, $gamesList)) {
                        $memType = $mem;
                        break;
                    }
                }
                $diff = strtolower($r->difficulty ?? 'sin-dificultad');
                $date = $r->created_at ? $r->created_at->format('Y-m-d') : null;
                $val = (int)$r->score;

                $sourceRows[] = [
                    'memory' => $memType,
                    'difficulty' => $diff,
                    'score' => $val,
                    'date' => $date
                ];

                $memoryScoreTotals[$memType] = ($memoryScoreTotals[$memType] ?? 0) + $val;
                $difficultyTotals[$diff] = ($difficultyTotals[$diff] ?? 0) + $val;
            }

            $memoryTypes = array_unique(array_merge($memoryTypes, array_keys($mapping)));
        }

        $allDatesScore = [];
        $dailyMem = [];
        $dailyDiff = [];

        foreach ($sourceRows as $r) {
            if (empty($r['date'])) continue;
            $d = $r['date'];
            $mem = $r['memory'];
            $diff = $r['difficulty'];
            $val = $r['score'];

            $dailyMem[$mem][$d] = ($dailyMem[$mem][$d] ?? 0) + $val;
            $dailyDiff[$diff][$d] = ($dailyDiff[$diff][$d] ?? 0) + $val;

            $allDatesScore[$d] = true;
        }

        $allDatesUnion = array_unique(array_merge($labels, array_keys($allDatesScore)));
        sort($allDatesUnion);
        $chartLabels = $allDatesUnion;

        $chartMemoryDatasets = [];
        foreach ($memoryTypes as $mem) {
            $series = [];
            $cum = 0;
            foreach ($chartLabels as $date) {
                $cum += ($dailyMem[$mem][$date] ?? 0);
                $series[] = $cum;
            }
            $chartMemoryDatasets[] = [
                'label' => $mem,
                'data' => $series
            ];
        }

        $difficultiesList = array_values(array_unique(array_keys($dailyDiff)));
        if (empty($difficultiesList)) {
            $difficultiesList = $difficulties;
        }
        $chartDifficultyDatasets = [];
        foreach ($difficultiesList as $diff) {
            $series = [];
            $cum = 0;
            foreach ($chartLabels as $date) {
                $cum += ($dailyDiff[$diff][$date] ?? 0);
                $series[] = $cum;
            }
            $chartDifficultyDatasets[] = [
                'label' => $diff,
                'data' => $series
            ];
        }

        $difficultyTotals = $difficultyTotals ?? [];

        $storyLevel = Progress::where('user_id', $user->id)->value('level') ?? 0;

        return view('perfil', compact(
            'user',
            'scoresPorJuego',
            'scoreGeneral',
            'scoreMemoria',
            'juegosJugados',
            'promedioScore',
            'mejorScore',
            'nivel',
            'subtotalesMemoria',
            'memoryCounts',
            'memoryTotals',
            'memoryTypes',
            'difficulties',
            'chartLabels',
            'chartDatasets',
            'chartMemoryDatasets',
            'chartDifficultyDatasets',
            'memoryScoreTotals',
            'difficultyTotals',
            'levelInfo',
            'storyLevel'
        ));
    }

    public function statsJson()
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'unauthorized'], 401);

        $scoreGeneral = GameScore::where('user_id', $user->id)->sum('score');
        $levelInfo = $this->computeLevelInfo((int)$scoreGeneral);

        return response()->json([
            'scoreGeneral' => (int)$scoreGeneral,
            'levelInfo' => $levelInfo,
        ]);
    }


    public function avatar($path)
    {
        $path = ltrim($path, '/');

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $stream = Storage::disk('public')->readStream($path);
        $mime = Storage::disk('public')->mimeType($path);
        $size = Storage::disk('public')->size($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Length' => $size,
            'Cache-Control' => 'public, max-age=31536000',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ]);
    }
}
