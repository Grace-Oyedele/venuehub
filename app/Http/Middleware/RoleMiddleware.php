<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponser;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    use ApiResponser;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth("api")->check()) {
            return $this->error("Unauthenticated", 400);
        }

        $user = $request->user();
        if ($user->role != "admin") {
            return $this->error("User role not allowed", 400);
        }
        return $next($request);
    }
}
