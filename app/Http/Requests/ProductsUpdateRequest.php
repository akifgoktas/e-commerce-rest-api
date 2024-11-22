<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Kullanıcı yetkilendirmesi eklemek istiyorsanız burayı düzenleyin
    }

    public function rules()
    {
        return [
            'name'      => 'required|string|max:255',
            'price'     => 'required|numeric|min:1',
            'stock'     => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'name.required'     => 'Ürün adı gereklidir.',
            'name.string'       => 'Ürün adı yalnızca metin içermelidir.',
            'name.max'          => 'Ürün adı en fazla 255 karakter olabilir.',
            'price.required'    => 'Fiyat alanı gereklidir.',
            'price.numeric'     => 'Fiyat sayısal bir değer olmalıdır.',
            'price.min'         => 'Fiyat en az 1 olmalıdır.',
            'stock.numeric'     => 'Stok sayısal bir değer olmalıdır.'
        ];
    }
}
