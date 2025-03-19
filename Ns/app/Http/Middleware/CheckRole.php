<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        // Verifica si el usuario está autenticado y si tiene el rol correcto
        if (!Auth::check() || Auth::user()->role !== $role) {
            // Redirige al home si no cumple con los requisitos
            return redirect()->route('/');
        }

        // Si todo está bien, continúa con la solicitud
        return $next($request);
    }
}