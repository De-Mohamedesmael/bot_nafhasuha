<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class AssignGuard extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if ($guard != null) {
            auth()->shouldUse($guard); //shoud you user guard / table
            try {
                $user = JWTAuth::parseToken()->authenticate();
            } catch (TokenExpiredException $e) {
                return responseApi('false', 'Unauthenticated user');

            } catch (JWTException $e) {
                return responseApi('false', 'token_invalid' . $e->getMessage());
            }

        } else {
            auth()->shouldUse($guard); //shoud you user guard / table
                try {
                    JWTAuth::parseToken()->authenticate();
                } catch (TokenExpiredException $e) {
                    return $next($request);

                } catch (JWTException $e) {
                    return $next($request);
                }
//            }
        }
        return $next($request);
    }
}
