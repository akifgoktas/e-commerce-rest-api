<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class UsersMiddleware
{
    protected $validator;



    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}
