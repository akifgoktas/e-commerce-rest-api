<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class OrderMiddleware
{

    public function handle(Request $request, Closure $next): Response
    {
        $user_status = Session::get('user_status');
        if ($user_status === false) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Sipariş verebilmeniz için önce giriş yapmalı veya kayıt olmalısınız'
            ], 400);
        }
        return $next($request);
    }
}
