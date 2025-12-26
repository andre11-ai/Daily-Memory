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
        // Protege por autenticación y por middleware admin (usa FQCN si prefieres)
        $this->middleware(['auth', \App\Http\Middleware\AdminMiddleware::class]);
    }

    // vista del panel
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

        // Contadores para las tarjetas
        $usersCount = User::count();
        $gamesCount = 9; // actualiza si lo necesitas (número de juegos disponibles)

        // --- NUEVO: partidas totales y por dificultad ---
        // total de partidas (cada registro en GameScore se considera una partida)
        $playsTotal = 0;
        $playsPerDifficulty = [
            'facil' => 0,
            'medio' => 0,
            'dificil' => 0,
        ];

        // comprobar si la tabla de GameScore existe antes de consultar
        $gameScoreTable = (new GameScore)->getTable();
        if (Schema::hasTable($gameScoreTable)) {
            $playsTotal = GameScore::count();

            // agrupamos por difficulty
            $difficultyCounts = GameScore::whereNotNull('difficulty')
                ->select('difficulty', DB::raw('COUNT(*) as plays'))
                ->groupBy('difficulty')
                ->pluck('plays', 'difficulty')
                ->toArray();

            // normalizamos y mapeamos a nuestras 3 categorías
            foreach ($difficultyCounts as $diffLabel => $count) {
                // normalizar: quitar acentos y pasar a ascii, luego minúsculas y quitar espacios
                $key = Str::ascii((string)$diffLabel);
                $key = strtolower(preg_replace('/\s+/', '', $key)); // ej: "Fácil" -> "facil"

                // heurística: identificar por fragmentos
                if (strpos($key, 'fac') !== false || $key === 'b' || strpos($key, 'easy') !== false) {
                    $playsPerDifficulty['facil'] += (int) $count;
                } elseif (strpos($key, 'med') !== false || $key === 'm' || strpos($key, 'medium') !== false) {
                    $playsPerDifficulty['medio'] += (int) $count;
                } elseif (strpos($key, 'dif') !== false || $key === 'd' || strpos($key, 'hard') !== false) {
                    $playsPerDifficulty['dificil'] += (int) $count;
                } else {
                    // si no encaja en las 3 categorías, podríamos ignorarlo o añadir una categoría "otros"
                    // por ahora lo dejamos fuera de las 3 contadas específicamente
                }
            }
        }

        // antes teníamos visits/reports; las eliminamos en favor de las métricas de partidas
        return view('admin', compact(
            'users','usersCount','gamesCount',
            'playsTotal','playsPerDifficulty','q'
        ));
    }

    // listado paginado y con busqueda (q)
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

    // ver un usuario
    public function show($id)
    {
        $user = User::select('id','name','email','is_admin','created_at')->find($id);

        if (! $user) {
            return response()->json(['message' => 'Usuario no encontrado.'], 404);
        }

        return response()->json($user);
    }

    // actualizar usuario
public function update(Request $request, $id)
{
    $user = User::find($id);

    if (! $user) {
        return response()->json(['message' => 'Usuario no encontrado.'], 404);
    }

    $data = $request->validate([
        'name' => ['required','string','max:255'],
        'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
        'is_admin' => ['sometimes','boolean'],
        'password' => ['nullable','string','min:6'],
    ]);

    // ¿La tabla users tiene las columnas is_admin y role?
    $hasIsAdminColumn = Schema::hasColumn((new User)->getTable(), 'is_admin');
    $hasRoleColumn = Schema::hasColumn((new User)->getTable(), 'role');

    // Antes de quitar admin a un administrador, comprobar que exista otro admin
    if (array_key_exists('is_admin', $data)) {
        $wantsToRemoveAdmin = ! (bool) $data['is_admin'];

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
    }

    // Actualizar campos básicos
    $user->name = $data['name'];
    $user->email = $data['email'];

    // Manejar admin: actualizar BOTH is_admin y role (si existen) para mantener sincronía
    if (array_key_exists('is_admin', $data)) {
        $isAdminValue = (bool) $data['is_admin'];

        if ($hasIsAdminColumn) {
            $user->is_admin = $isAdminValue;
        }

        if ($hasRoleColumn) {
            $user->role = $isAdminValue ? 'admin' : 'user';
        }
    }

    // Actualizar contraseña solo si se envía
    if (! empty($data['password'])) {
        $user->password = Hash::make($data['password']);
    }

    $user->save();

    // Asegurar que la respuesta JSON incluya is_admin y role para que el frontend lo use
    $userArray = $user->toArray();
    $userArray['is_admin'] = $hasIsAdminColumn ? (bool)$user->is_admin : ($hasRoleColumn ? ($user->role === 'admin') : false);
    if (! $hasRoleColumn && $hasIsAdminColumn) {
        // Si no hay role pero sí is_admin, devolver role calculado para compatibilidad frontend
        $userArray['role'] = $userArray['is_admin'] ? 'admin' : 'user';
    }

    return response()->json([
        'message' => 'Usuario actualizado correctamente.',
        'user' => $userArray
    ]);
}
    // borrar usuario
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

    // ---------------- Estadísticas / Métodos nuevos ----------------

    // GET /admin/api/stats/meta
    // Devuelve juegos, dificultades y memory_types disponibles (para llenar selects)
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

    // GET /admin/api/stats/scores
    // Retorna avg score y plays por juego aplicando filtros opcionales
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

    // GET /admin/api/stats/top-games
    // Top 5 juegos más jugados (por plays), admite mismos filtros
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

    // GET /admin/api/stats/top-difficulty
    // Recuento por difficulty (y devuelve la más jugada)
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

    // ---------------- fin estadísticas ----------------
    // el resto de métodos users show/update/destroy siguen aquí...


}
