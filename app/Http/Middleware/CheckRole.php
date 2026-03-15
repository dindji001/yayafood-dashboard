<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Accès interdit. Rôle requis : ' . $role], 403);
            }
            return redirect('/dashboard')->with('error', 'Vous n\'avez pas les permissions nécessaires.');
        }

        return $next($request);
    }
}
