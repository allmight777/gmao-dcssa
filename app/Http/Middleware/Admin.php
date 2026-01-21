<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Admin
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            // Rediriger ou aborter
            return redirect('/dashboard')->with('error', "Accès refusé : Administrateur seulement.");
            // ou
            // abort(403, "Accès refusé : Administrateur seulement.");
        }

        return $next($request);
    }
}
