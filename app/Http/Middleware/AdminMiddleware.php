<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No autenticado.'], 401);
            }
            return redirect()->guest(route('login'));
        }

        if (! (bool) ($user->is_admin ?? false)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acceso denegado. Se requieren privilegios de administrador.'], 403);
            }
            abort(403, 'Acceso denegado: se requieren privilegios de administrador.');
        }

        return $next($request);
    }
}
