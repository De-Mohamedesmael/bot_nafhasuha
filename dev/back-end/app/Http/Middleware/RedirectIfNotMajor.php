<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfNotMajor
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $guard
     * @return mixed
     *
     * @throws AuthenticationException
     */
    public function handle($request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->check() && majorAdmin()) {
            return $next($request);
        }
        return redirect()->route('admin.home');
    }

}
