<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductsModel;
use App\Models\CartsModel;
use App\Models\OrdersModel;
use Illuminate\Support\Facades\Session;

class OrdersController extends Controller
{
    public function add(Request $request)
    {
        $user_id = Session::get('user_id');
        try {
            $cart = CartsModel::where('user_id', $user_id)->first();
            if ($cart) {
                $cart_items = $cart->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ];
                });
                $products_id = $cart->items->pluck('product_id');
                $products = ProductsModel::whereIn('id', $products_id)->get();

                $products_with_quantity = $products->map(function ($product) use ($cart_items) {
                    $quantity = $cart_items->firstWhere('product_id', $product->id)['quantity'];

                    if ($quantity > $product->stock) {
                        throw new \Exception("Ürün: {$product->name} için yeterli stok bulunmamaktadır!");
                    }

                    $product->decrement('stock', $quantity);

                    return $product->setAttribute('quantity', $quantity);
                });
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Siparişiniz başarılı bir şekilde oluşturulmuştur.',
                    'orders'    => $products_with_quantity,
                ], 201);
            } else {
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Bu kullanıcıya ait sepet bulunamadı'
                ], 400);
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status'    => 'success',
                'message'   => 'Sipariş verilirken hata medyadan geldi: ' . $th->getMessage(),
            ], 400);
        }
        return $response;
    }
}
