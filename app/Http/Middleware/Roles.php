<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Illuminate\Http\Request;

class Roles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Auth::user()
        try {
              
        $token = JWTAuth::parseToken();        
        $user = $token->authenticate();
    } catch (TokenExpiredException $e) {        
        return $this->unauthorized('Your token has expired. Please, login again.');    
    } catch (TokenInvalidException $e) {        
        return $this->unauthorized('Your token is invalid. Please, login again.');    
    }catch (JWTException $e) {        
        return $this->unauthorized('Please, attach a Bearer Token to your request');
    }    
    if ($user->role == 2) {
        return $next($request);
    }

    return $this->unauthorized();
    }

    private function unauthorized($message = null){
    return response()->json([
        'message' => $message ? $message : 'You are unauthorized to access this resource',
        'success' => false
    ], 401);
    }
}
