<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductsAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules()
    {
        return [
            'name'          => 'required|max:255',
            'price'         => 'required|numeric|min:1',
            'stock'         => 'required|numeric|min:1',
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => 'Ürün ismi alanı doldurulmalıdır.',
            'price_number.required' => 'Fiyat alanı doldurulmalıdır.',
            'price_number.regex'    => 'Fiyat sadece rakamlardan oluşmalıdır.',
            'price_number.min'      => 'Fiyat en az 1 olmalıdır.',
            'stock_number.required' => 'Stok alanı doldurulmalıdır.',
            'stock_number.regex'    => 'Stok sadece rakamlardan oluşmalıdır.',
            'stock_number.min'      => 'Stok en az 1 olmalıdır.',
        ];
    }
}
