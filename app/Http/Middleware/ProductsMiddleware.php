<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;

class ProductsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin_status = Session::get('admin_status');
        if ($admin_status === false) {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Bu işlem için yetkiniz bulunmamaktadır'
            ], 500);
        }
        return $next($request);
    }
}
