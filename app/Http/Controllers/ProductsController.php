<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductsAddRequest;
use App\Http\Requests\ProductsUpdateRequest;
use App\Models\ProductsModel;

class ProductsController extends Controller
{
    public function list()
    {
        try {
            $products = ProductsModel::get();
            $response = response()->json([
                'status'    => 'success',
                'message'   => 'Ürünler başarılı bir şekilde çekildi',
                'products'  => $products,
            ], 201);
        } catch (\Throwable $th) {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Ürünler çekilirken bir hata meydana geldi: ' . $th->getMessage()
            ], 500);
        }
        return $response;
    }
    public function listOne($id)
    {
        $product = ProductsModel::find($id);
        if ($product) {
            try {
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Ürün başarılı bir şekilde çekildi',
                    'product'  => $product,
                ], 201);
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Ürün çekilirken bir hata meydana geldi: ' . $th->getMessage()
                ], 500);
            }
        } else {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Sistemde böyle bir ürün yok'
            ], 500);
        }
        return $response;
    }
    public function add(ProductsAddRequest $request)
    {
        try {
            $validated_data = $request->validated();
            $product_add = ProductsModel::create($validated_data);
            $response = response()->json([
                'status'    => 'success',
                'message'   => 'Ürün başarılı bir şekilde eklendi'
            ], 201);
        } catch (\Throwable $th) {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Ürün eklenirken bir hata meydana geldi: ' . $th->getMessage()
            ], 500);
        }
        return $response;
    }

    public function update(ProductsUpdateRequest $request, $id)
    {
        $product = ProductsModel::find($id);
        if ($product) {
            try {
                $validated_data = $request->validated();
                $product->update($validated_data);
                $response = response()->json([
                    'status'  => 'success',
                    'message' => 'Ürün başarılı bir şekilde güncellendi'
                ], 200);
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'  => 'error',
                    'message' => 'Ürün güncellenirken bir hata meydana geldi: ' . $th->getMessage()
                ], 500);
            }
        } else {
            $response = response()->json([
                'status'  => 'error',
                'message' => 'Sistemde böyle bir ürün bulunmamaktadır'
            ], 500);
        }

        return $response;
    }

    public function delete($id)
    {
        $product = ProductsModel::find($id);
        if ($product) {
            try {
                $product->delete();
                $response = response()->json([
                    'status'  => 'success',
                    'message' => 'Ürün başarılı bir şekilde silindi'
                ], 200);
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'  => 'error',
                    'message' => 'Ürün sliinirken bir hata meydana geldi: ' . $th->getMessage()
                ], 500);
            }
        } else {
            $response = response()->json([
                'status'  => 'error',
                'message' => 'Sistemde böyle bir ürün bulunmamaktadır'
            ], 500);
        }

        return $response;
    }
}
