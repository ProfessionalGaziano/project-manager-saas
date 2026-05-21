<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         // Se l'utente è loggato e ha un team attivo
        if (backpack_auth()->check()) {
            $user = backpack_user();
            
            // Prendi il primo team dell'utente come team attivo
            $team = $user->teams()->first() ?? $user->ownedTeams()->first();
            
            if ($team) {
                // Salva il team attivo nella sessione
                session(['active_team_id' => $team->id]);
            }
        }

        return $next($request);
    }
}
