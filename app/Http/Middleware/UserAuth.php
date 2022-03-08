<?php

namespace App\Http\Middleware;

use Closure;
//use Illuminate\Contracts\Auth\Factory as Auth;

use \Firebase\JWT\ExpiredException;
use \Firebase\JWT\JWT;
use \Firebase\JWT\SignatureInvalidException;
use App\Models\User;
use Auth;


class UserAuth
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
//    public function __construct(Auth $auth)
//    {
//        $this->auth = $auth;
//    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */

    public function handle($request, Closure $next)
    {

        if($request->user()->user_type != '2'){
            return response()->json(['status' => false, 'message' => 'Permission denied Now'], 401);
        }
        return $next($request);
    }
}
