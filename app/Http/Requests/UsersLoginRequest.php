<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsersLoginRequest extends FormRequest
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
            'email'         => 'required|email',
            'password'      => 'required|min:8|max:16',
        ];
    }

    public function messages()
    {
        return [
            'email.required'        => 'E-posta alanı gereklidir.',
            'email.email'           => 'Lütfen geçerli bir e-posta adresi girin.',
            'password.required'     => 'Parola gereklidir.',
            'password.min'          => 'Parola en az 8 karakter uzunluğunda olmalıdır.',
        ];
    }
}
