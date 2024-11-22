<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CartsItemRequest extends FormRequest
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
            'product_id'        => 'required|numeric',
            'quantity'          => 'numeric'
        ];
    }

    public function messages()
    {
        return [
            'product_id.required' => 'Ürün id alanı doldurulmalıdır.',
            'product_id.regex'    => 'Ürün id sadece rakamlardan oluşmalıdır.',
            'quantity.required' => 'Ürün miktar alanı doldurulmalıdır.',
            'quantity.regex'    => 'Ürün miktar sadece rakamlardan oluşmalıdır.'
        ];
    }
}
