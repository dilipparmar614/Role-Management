<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use \Illuminate\Session\TokenMismatchException;
use Illuminate\Http\Request;

class VerifyJWTToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // public function handle(Request $request, Closure $next)
    // {
    //     try {
    //         $token = $request->bearerToken();
    //         $authUser = auth()->user();
    //         $user = JWTAuth::user($token);

    //     } catch (JWTException $e) {
    //         if ($e instanceof TokenExpiredException) {
    //             return response()->json([
    //                 'error' => 'token_expired',
    //                 'code' => 401
    //             ], 401);
    //         } 
    //         else if($e instanceof TokenInvalidException){
    //             return response()->json([
    //                 'error' => "token_invalid",
    //                 'code' => 401
    //             ], 401);
    //         } 
    //         else {
    //             return response()->json([
    //                 'error' => 'Token is required',
    //                 'code' => 401,

    //             ], 401);
    //         }
    //     }
    //     return $next($request);
    // }
    
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        try {
            $user = JWTAuth::parseToken($token)->toUser();
            // if (!$user) {
            //     return response()->json(['message' => 'User not found'], 401);
            // }
        } catch (TokenExpiredException $e) {
            return response()->json(['message' => 'Token Expired'], 401);
        } catch (TokenInvalidException $e) {
            return response()->json(['message' => 'Invalid Token'], 401);
        } catch (TokenMismatchException $e) {
            return response()->json(['message' => 'Token Expired'], 401);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        return $next($request)->header('Access-Control-Allow-Origin', '*');
    }
}
