<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function add(Request $request)
    {
        return response()->json([
            'status'    => 'success',
            'message'   => 'sipariş işlemleri',
        ], 201);
        return $response;
    }
}
