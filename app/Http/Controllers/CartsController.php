<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Requests\CartsItemRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use App\Models\ProductsModel;
use App\Models\CartItemModel;
use App\Models\CartsModel;
use App\Models\UsersModel;
use Illuminate\Support\Facades\Session;

class CartsController extends Controller
{
    public function list()
    {
        $user_status    = Session::get('user_status');
        $user_id        = Session::get('user_id');
        $user           = UsersModel::where('id', $user_id)->first();
        if ($user_status === false) {
            try {
                $cart_items = Cookie::get('cart_items');
                $cart_items = $cart_items ? json_decode($cart_items, true) : [];

                if (!is_array($cart_items)) {
                    $cart_items = [];
                }
                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Ürün sepete eklendi',
                    'products'  => $cart_items,
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Sepet çağırılırken bir hata meydana geldi'
                ], 400);
            }
        } else {
            try {

                $cart               = CartsModel::where('user_id', $user_id)->first();
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Ürün sepete eklendi',
                    'products'  => $cart->items,
                ], 201);
            } catch (\Throwable $th) {
                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Sepet çağırılırken bir hata meydana geldi'
                ], 400);
            }
        }
        $response = response()->json([
            'status'    => 'success',
            'message'   => 'Sepet',
            'products'  => $user->cart,
        ], 201);

        return $response;
    }

    public function add(CartsItemRequest $request)
    {
        $product = ProductsModel::find($request->product_id);
        if ($product) {
            $user_status = Session::get('user_status');
            if ($user_status === false) {
                try {
                    $cart_items = Cookie::get('cart_items');
                    $cart_items = $cart_items ? json_decode($cart_items, true) : [];

                    if (!is_array($cart_items)) {
                        $cart_items = [];
                    }
                    if (isset($cart_items[$request->product_id]) && $cart_items[$request->product_id]['product_id'] == $request->product_id) {
                        return response()->json([
                            'status'    => 'error',
                            'message'   => 'Bu ürün sepete daha önce eklenmiş'
                        ], 400);
                    } else {
                        $product_data = [
                            'product_id'    => $product->id,
                            'name'          => $product->name,
                            'quantity'      => 1,
                        ];
                        $cart_items[$product->id] = $product_data;
                        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 7);
                        return response()->json([
                            'status'    => 'success',
                            'message'   => 'Ürün sepete eklendi',
                            'cart_items'  => $cart_items,
                        ], 201);
                    }
                } catch (\Throwable $th) {
                    $response = response()->json([
                        'status'    => 'error',
                        'message'   => 'Ürün sepete eklenirken hata meydana geldi: ' . $th->getMessage()
                    ], 400);
                }
            } else {
                try {
                    $user_id            = Session::get('user_id');
                    $cart               = CartsModel::where('user_id', $user_id)->first();
                    $cart_items_product = CartItemModel::where('product_id', $request->product_id)->where('cart_id', $cart->id)->first();
                    if ($cart_items_product) {
                        $response = response()->json([
                            'status'    => 'error',
                            'message'   => 'Bu ürünü daha önce sepete eklediniz'
                        ], 500);
                    } else {
                        if (isset($request->quantity) && $request->quantity >= 1) {
                            if (!$cart) {
                                $cart_save      = CartsModel::create([
                                    'user_id'   => $user_id,
                                    'status'    => false
                                ]);
                            }
                        }
                        $product            = ProductsModel::where('id', $request->product_id)->first();
                        $cart_item_save     = CartItemModel::create([
                            'cart_id'       => $cart->id,
                            'product_id'    => $product->id,
                            'quantity'      => 1,
                            'price'         => $product->price
                        ]);
                        if ($cart_item_save) {
                            $response = response()->json([
                                'status'    => 'success',
                                'message'   => 'Ürün sepete eklendi',
                                'cart_items'  => $cart->items,
                            ], 201);
                        }
                    }
                } catch (\Throwable $th) {
                    $response = response()->json([
                        'status'    => 'error',
                        'message'   => 'Ürün sepete eklenirken hata meydana geldi: ' . $th->getMessage()
                    ], 400);
                }
            }
        } else {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Sistemde böyle bir ürün bulunamadı'
            ], 400);
        }
        return $response;
    }

    public function update(Request $request, $id = null)
    {
        $product = ProductsModel::find($id);
        if ($product) {
            $user_status = Session::get('user_status');
            if ($user_status === false) {
                try {
                    $cart_items = Cookie::get('cart_items');
                    $cart_items = $cart_items ? json_decode($cart_items, true) : [];

                    if (!is_array($cart_items)) {
                        $cart_items = [];
                    }
                    if (isset($cart_items[$id]) && $cart_items[$id]['product_id'] == $id) {

                        $product_data = [
                            'product_id'    => $product->id,
                            'name'          => $product->name,
                            'quantity'      => $request->quantity,
                        ];
                        $cart_items[$product->id] = $product_data;
                        Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 7);
                        return response()->json([
                            'status'    => 'success',
                            'message'   => 'Sepet başarılı bir şekilde güncellendi',
                            'cart_items'  => $cart_items,
                        ], 201);
                    } else {
                        return response()->json([
                            'status'    => 'error',
                            'message'   => 'Sepette böyle bir ürün yok'
                        ], 400);
                    }
                } catch (\Throwable $th) {
                    $response = response()->json([
                        'status'    => 'error',
                        'message'   => 'Sepet ürün güncellenirken hata meydana geldi: ' . $th->getMessage()
                    ], 400);
                }
            } else {
                try {
                    $user_id            = Session::get('user_id');
                    $cart               = CartsModel::where('user_id', $user_id)->first();
                    $cart_items_product = CartItemModel::where('cart_id', $cart->id)->where('product_id', $id)->first();
                    if ($cart_items_product) {
                        if (isset($request->quantity) && $request->quantity >= 1) {
                            if (!$cart) {
                                $cart_save      = CartsModel::create([
                                    'user_id'   => $user_id,
                                    'status'    => false
                                ]);
                            }
                            $product            = ProductsModel::where('id', $id)->first();
                            $cart_item_update   = CartItemModel::where('product_id', $id)->where('cart_id', $cart->id)->update([
                                'quantity'      => $request->quantity,
                                'price'         => $product->price
                            ]);
                            if ($cart_item_update) {
                                $response = response()->json([
                                    'status'    => 'success',
                                    'message'   => 'Ürün adedi güncellendi',
                                    'cart_items'  => $cart->items,
                                ], 201);
                            }
                        }
                    } else {
                        $response = response()->json([
                            'status'    => 'error',
                            'message'   => 'Sepette böyle bir ürün yok'
                        ], 400);
                    }
                } catch (\Throwable $th) {
                    $response = response()->json([
                        'status'    => 'error',
                        'message'   => 'Ürün güncellenirken hata meydana geldi: ' . $th->getMessage()
                    ], 400);
                }
            }
        } else {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Sistemde böyle bir ürün bulunamadı'
            ], 400);
        }
        return $response;
    }

    public function delete($id)
    {
        $user_status = Session::get('user_status');
        if ($user_status === false) {
            try {
                $cart_items = Cookie::get('cart_items');
                $cart_items = $cart_items ? json_decode($cart_items, true) : [];
                if (!is_array($cart_items)) {
                    $cart_items = [];
                }
                if (isset($cart_items[$id])) {
                    unset($cart_items[$id]);
                    Cookie::queue('cart_items', json_encode($cart_items), 60 * 24 * 7);
                    return response()->json([
                        'status'    => 'success',
                        'message'   => 'Ürün sepetten kaldırıldı',
                        'products'  => $cart_items,
                    ], 200);
                } else {
                    return response()->json([
                        'status'    => 'error',
                        'message'   => 'Ürün zaten sepette değil',
                    ], 400);
                }
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Ürün sepetten kaldırılırken bir hata meydana geldi: ' . $th->getMessage()
                ], 400);
            }
        } else {
            try {
                $user_id            = Session::get('user_id');
                $cart               = CartsModel::where('user_id', $user_id)->first();
                $cart_items_product = CartItemModel::where('cart_id', $cart->id)->where('product_id', $id)->first();
                if ($cart_items_product) {
                    $deleted    = CartItemModel::where('cart_id', $cart->id)->where('product_id', $id)->delete();
                    if ($deleted) {
                        $cart_items     = CartItemModel::where('cart_id', $cart->id)->get();
                        $response = response()->json([
                            'status'    => 'success',
                            'message'   => 'Ürün sepetten kaldırıldı',
                            'products'  => $cart_items,
                        ], 201);
                    } else {
                        $response = response()->json([
                            'status'    => 'error',
                            'message'   => 'Ürün sepetten kaldırılırken bir hata meydana geldi'
                        ], 400);
                    }
                } else {
                    $response = response()->json([
                        'status'    => 'error',
                        'message'   => 'Sepette böyle bir ürün yok'
                    ], 400);
                }
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Ürün sepetten kaldırılırken bir hata meydana geldi: ' . $th->getMessage()
                ], 400);
            }
        }

        return $response;
    }
}
