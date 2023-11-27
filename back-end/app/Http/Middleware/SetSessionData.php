<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use function App\CPU\translate;

class SetSessionData extends BaseMiddleware
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
                return responseApiFalse(403, translate('Unauthenticated user'));

            } catch (JWTException $e) {
                return responseApiFalse(403, translate('token_invalid').' ' . $e->getMessage());
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
        }
        return $next($request);
    }
}
