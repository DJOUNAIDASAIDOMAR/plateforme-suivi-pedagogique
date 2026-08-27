<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Vérifie que l'utilisateur connecté
     * possède l'un des rôles autorisés.
     */
    public function handle(
        Request $request,
        Closure $next,
        string ...$roles
    ): Response {

        $user = $request->user();

        /*
        |--------------------------------------------------------------------------
        | UTILISATEUR NON CONNECTÉ
        |--------------------------------------------------------------------------
        */

        if (! $user) {
            abort(
                403,
                'Vous devez être connecté pour accéder à cette page.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | RÔLE NON AUTORISÉ
        |--------------------------------------------------------------------------
        */

        if (! in_array(
            $user->role,
            $roles,
            true
        )) {
            abort(
                403,
                'Vous n’avez pas l’autorisation d’accéder à cette page.'
            );
        }


        return $next($request);
    }
}