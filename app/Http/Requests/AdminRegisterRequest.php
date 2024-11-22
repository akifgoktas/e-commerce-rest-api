<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminRegisterRequest extends FormRequest
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
            'full_name'     => 'required|max:255',
            'email'         => 'required|email',
            'phone_number'  => 'required|regex:/^\d{10,11}$/',
            'password'      => 'required|min:8|max:16',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required'            => 'Ad ve soyad alanı gereklidir.',
            'email.required'                => 'E-posta alanı gereklidir.',
            'email.email'                   => 'Lütfen geçerli bir e-posta adresi girin.',
            'phone_number.required'         => 'Telefon numarası gereklidir.',
            'phone_number.regex'            => 'Telefon numarası sadece rakamlardan oluşmalıdır.',
            'phone_number.digits_between'   => 'Telefon numarası 10 ile 11 basamak arasında olmalıdır.',
            'password.required'             => 'Parola gereklidir.',
            'password.min'                  => 'Parola en az 8 karakter uzunluğunda olmalıdır.',
        ];
    }
}
