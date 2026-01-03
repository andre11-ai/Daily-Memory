<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GameScore;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
    }

    public function index(Request $request)
    {
        $q = $request->query('q', null);
        $perPage = (int) $request->query('per_page', 10);

        $usersQuery = User::query()
            ->select('id','name','email','is_admin','created_at')
            ->orderBy('created_at', 'desc');

        if ($q) {
            $usersQuery->where(function($sub){
                $sub->where('name', 'like', '%' . request('q') . '%')
                    ->orWhere('email', 'like', '%' . request('q') . '%');
            });
        }

        $users = $usersQuery->paginate($perPage)->withQueryString();
        $usersCount = User::count();
        $gamesCount = 9;
        $playsTotal = 0;
        $playsPerDifficulty = [
            'facil' => 0,
            'medio' => 0,
            'dificil' => 0,
        ];

        $gameScoreTable = (new GameScore)->getTable();
        if (Schema::hasTable($gameScoreTable)) {
            $playsTotal = GameScore::count();

            $difficultyCounts = GameScore::whereNotNull('difficulty')
                ->select('difficulty', DB::raw('COUNT(*) as plays'))
                ->groupBy('difficulty')
                ->pluck('plays', 'difficulty')
                ->toArray();

            foreach ($difficultyCounts as $diffLabel => $count) {
                $key = Str::ascii((string)$diffLabel);
                $key = strtolower(preg_replace('/\s+/', '', $key));

                if (strpos($key, 'fac') !== false || $key === 'b' || strpos($key, 'easy') !== false) {
                    $playsPerDifficulty['facil'] += (int) $count;
                } elseif (strpos($key, 'med') !== false || $key === 'm' || strpos($key, 'medium') !== false) {
                    $playsPerDifficulty['medio'] += (int) $count;
                } elseif (strpos($key, 'dif') !== false || $key === 'd' || strpos($key, 'hard') !== false) {
                    $playsPerDifficulty['dificil'] += (int) $count;
                } else {
                    //
                }
            }
        }

    $scatterData = DB::table('progress')
        ->select('user_id', DB::raw('ROUND(AVG(level), 1) as average_level'))
        ->groupBy('user_id')
        ->get()
        ->map(function ($entry) {
            $user = User::find($entry->user_id);
            return [
                'label' => $user ? $user->name : 'Usuario desconocido',
                'x' => $entry->average_level ?? 0,
                'y' => $user ? $user->id : null,
            ];
        })->toArray();

    return view('admin', compact(
        'users', 'usersCount', 'gamesCount',
        'playsTotal', 'playsPerDifficulty', 'q', 'scatterData'
    ));

    }

    public function usersList(Request $request)
    {
        $q = $request->query('q');
        $perPage = (int) $request->query('per_page', 20);

        $users = User::query()
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"))
            ->select('id','name','email','is_admin','created_at')
            ->orderBy('created_at','desc')
            ->paginate($perPage);

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $userArray = $user->only(['id','name','email','username','is_admin','created_at','profile_image']);
        $userArray['avatar_url'] = $user->profile_image
            ? asset('storage/' . ltrim($user->profile_image, '/'))
            : asset('img/default-user.png');

        return response()->json($userArray);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'password' => 'nullable|string|min:6',
            'is_admin' => 'sometimes|boolean',
        ]);

        $user->username = $data['username'];
        $user->name     = $data['name'];
        $user->email    = $data['email'];

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $user->profile_image = $path;
        }

        $hasIsAdminColumn = Schema::hasColumn((new User)->getTable(), 'is_admin');
        $hasRoleColumn    = Schema::hasColumn((new User)->getTable(), 'role');

        if (array_key_exists('is_admin', $data)) {
            $wantsToRemoveAdmin = !(bool)$data['is_admin'];
            $currentIsAdmin = $hasIsAdminColumn ? (bool)$user->is_admin : ($hasRoleColumn ? ($user->role === 'admin') : false);

            if ($currentIsAdmin && $wantsToRemoveAdmin) {
                $otherAdminsCount = $hasIsAdminColumn
                    ? User::where('is_admin', true)->where('id', '!=', $user->id)->count()
                    : ($hasRoleColumn ? User::where('role', 'admin')->where('id', '!=', $user->id)->count() : 0);

                if ($otherAdminsCount === 0) {
                    return response()->json([
                        'message' => 'No se puede quitar el rol de admin: debe existir al menos un administrador.'
                    ], 422);
                }
            }

            if ($hasIsAdminColumn) $user->is_admin = (bool)$data['is_admin'];
            if ($hasRoleColumn)    $user->role    = $data['is_admin'] ? 'admin' : 'user';
        }

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $userArray = $user->toArray();
        $userArray['is_admin'] = $hasIsAdminColumn ? (bool)$user->is_admin : ($hasRoleColumn ? ($user->role === 'admin') : false);
        if (!$hasRoleColumn && $hasIsAdminColumn) {
            $userArray['role'] = $userArray['is_admin'] ? 'admin' : 'user';
        }
        $userArray['avatar_url'] = $user->profile_image
            ? asset('storage/' . ltrim($user->profile_image, '/'))
            : asset('img/default-user.png');

        return response()->json([
            'message' => 'Usuario actualizado correctamente.',
            'user' => $userArray,
        ]);
    }



    public function destroy(Request $request, $id)
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'No puedes borrar tu propia cuenta desde el panel.'], 403);
        }

        if ($user->is_admin) {
            $otherAdminsCount = User::where('is_admin', true)->where('id','!=',$user->id)->count();
            if ($otherAdminsCount === 0) {
                return response()->json(['message' => 'No se puede eliminar este administrador: debe existir al menos un admin.' ], 422);
            }
        }

        DB::transaction(function() use ($user) {
            $user->delete();
        });

        return response()->json(['message' => 'Usuario eliminado correctamente.']);
    }

    public function statsMeta()
    {
        $games = GameScore::select('game')
            ->distinct()
            ->orderBy('game')
            ->pluck('game');

        $difficulties = GameScore::whereNotNull('difficulty')
            ->select('difficulty')
            ->distinct()
            ->orderBy('difficulty')
            ->pluck('difficulty');

        $memoryTypes = [];
        $table = (new GameScore)->getTable();
        if (Schema::hasColumn($table, 'memory_type')) {
            $memoryTypes = GameScore::whereNotNull('memory_type')
                ->select('memory_type')
                ->distinct()
                ->orderBy('memory_type')
                ->pluck('memory_type');
        }

        return response()->json([
            'games' => $games,
            'difficulties' => $difficulties,
            'memory_types' => $memoryTypes,
        ]);
    }


    public function statsScores(Request $request)
    {
        $game = $request->query('game');
        $difficulty = $request->query('difficulty');
        $memoryType = $request->query('memory_type');

        $q = GameScore::query();

        if ($game) {
            $q->where('game', $game);
        }
        if ($difficulty) {
            $q->where('difficulty', $difficulty);
        }
        if ($memoryType && Schema::hasColumn((new GameScore)->getTable(), 'memory_type')) {
            $q->where('memory_type', $memoryType);
        }

        $rows = $q->select('game', DB::raw('AVG(score) as avg_score'), DB::raw('COUNT(*) as plays'))
            ->groupBy('game')
            ->orderBy('avg_score', 'desc')
            ->get();

        return response()->json($rows);
    }


    public function statsTopGames(Request $request)
    {
        $game = $request->query('game');
        $difficulty = $request->query('difficulty');
        $memoryType = $request->query('memory_type');

        $q = GameScore::query();

        if ($game) {
            $q->where('game', $game);
        }
        if ($difficulty) {
            $q->where('difficulty', $difficulty);
        }
        if ($memoryType && Schema::hasColumn((new GameScore)->getTable(), 'memory_type')) {
            $q->where('memory_type', $memoryType);
        }

        $rows = $q->select('game', DB::raw('COUNT(*) as plays'))
            ->groupBy('game')
            ->orderBy('plays', 'desc')
            ->limit(5)
            ->get();

        return response()->json($rows);
    }

    public function statsTopDifficulty(Request $request)
    {
        $game = $request->query('game');
        $memoryType = $request->query('memory_type');

        $q = GameScore::whereNotNull('difficulty');

        if ($game) {
            $q->where('game', $game);
        }
        if ($memoryType && Schema::hasColumn((new GameScore)->getTable(), 'memory_type')) {
            $q->where('memory_type', $memoryType);
        }

        $rows = $q->select('difficulty', DB::raw('COUNT(*) as plays'))
            ->groupBy('difficulty')
            ->orderBy('plays', 'desc')
            ->get();

        $mostPlayed = $rows->first() ? $rows->first()->difficulty : null;

        return response()->json([
            'data' => $rows,
            'most_played' => $mostPlayed,
        ]);
    }

    public function statsMemoryTypes(Request $request)
    {
        if (!Schema::hasColumn((new GameScore)->getTable(), 'memory_type')) {
            return response()->json([]);
        }

        $rows = GameScore::whereNotNull('memory_type')
            ->select('memory_type', DB::raw('COUNT(*) as plays'))
            ->groupBy('memory_type')
            ->orderBy('plays', 'desc')
            ->get();

        return response()->json($rows);
    }

    public function statsDifficultyCounts(Request $request)
    {
        $rows = GameScore::whereNotNull('difficulty')
            ->select('difficulty', DB::raw('COUNT(*) as plays'))
            ->groupBy('difficulty')
            ->orderBy('plays', 'desc')
            ->get();

        return response()->json($rows);
    }

    public function statsScatterPlays(Request $request)
    {
        $memoryType = $request->query('memory_type');
        $difficulty = $request->query('difficulty');

        $q = GameScore::query();

        if ($memoryType && Schema::hasColumn((new GameScore)->getTable(), 'memory_type')) {
            $q->where('memory_type', $memoryType);
        }
        if ($difficulty) {
            $q->where('difficulty', $difficulty);
        }

        $rows = $q->select('game', DB::raw('COUNT(*) as plays'))
            ->groupBy('game')
            ->orderBy('plays', 'desc')
            ->get();

        return response()->json($rows);
    }

}
