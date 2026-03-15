<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            // Ne pas rediriger si on est déjà sur la page de changement de mot de passe ou si on se déconnecte
            if (!$request->is('profile/change-password*') && !$request->is('logout')) {
                return redirect()->route('profile.password.force');
            }
        }

        return $next($request);
    }
}
