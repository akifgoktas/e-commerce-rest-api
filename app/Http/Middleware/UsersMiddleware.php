<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UsersMiddleware
{
    protected $validator;



    public function handle(Request $request, Closure $next)
    {
        //Şimdilik user kullanıcı işlemleri için burayı oturum kontrolü için kullanacağım
        return $next($request);
    }
}
