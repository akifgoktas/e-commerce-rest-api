<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Http\Requests\UsersRegisterRequest;
use App\Http\Requests\UsersLoginRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    private function mailControl($user_email)
    {
        $exists = UsersModel::where('email', $user_email)->exists();
        return $exists;
    }

    private function phoneNumberControl($user_phone_number)
    {
        $exists = UsersModel::where('phone_number', $user_phone_number)->exists();
        return $exists;
    }

    //Kullanıcıya onay kodu gönderilecek
    private function mailSend($email)
    {
        $code = rand(100000, 999999);
        $form_content = [
            'code'          => $code,
        ];
        $result = Mail::send('mail.mailpage', $form_content, function ($msg) use ($email) {
            $msg->to($email)
                ->subject('Turk Ticaret - Onay Kodu');
        });
        if ($result) {
            $response = response()->json([
                'status'    => 'success',
                'message'   => 'Onay kodu iletildi'
            ], 201);
        } else {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Onay kodu iletilirken bir problem oluştu'
            ], 500);
        }
        return $response;
    }

    public function register(UsersRegisterRequest $request)
    {
        $user_email         = $this->mailControl($request->email);
        $user_phone_number  = $this->phoneNumberControl($request->phone_number);

        if ($user_email == true || $user_phone_number == true) {
            if ($user_email == true) {
                $message = 'Bu mail ile daha önce kayıt yaptırılmış';
            } elseif ($user_phone_number == true) {
                $message = 'Bu telefon numarası ile daha önce kayıt yaptırılmış';
            }
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Kullanıcı Eklenemedi: ' . $message
            ], 500);
        } else {
            try {
                $validated_data = $request->validated();
                $user_save = UsersModel::create($validated_data);
                //$this->mailSend($request->email); // burada mail doğrulaması yapılacak
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Kullanıcı başarılı bir şekilde eklendi mailinize gelen onay kodunu giriniz.'
                ], 201);
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Kullanıcı Eklenemedi: ' . $th->getMessage()
                ], 500);
            }
        }

        return $response;
    }

    public function login(UsersLoginRequest $request)
    {
        try {
            $user_login = UsersModel::where('email', $request->email)->where('password', $request->password)->first();
            if ($user_login) {
                Session::put([
                    'user_status'   => true,
                    'user_id'       => $user_login->id
                ]);

                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Giriş yapıldı',
                ], 201);
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Mail veya şifreniz hatalı: ' . $th->getMessage()
            ], 500);
        }
        return $response;
    }

    public function logout()
    {
        try {
            Session::forget(['user_id']);
            Session::put(['user_status' => false]);
            if (Session::get('user_status') ===  false) {
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Oturum kapatıldı'
                ], 201);
            } else {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Oturum kapatılmadı'
                ], 500);
            }
        } catch (\Throwable $th) {
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Hata meydana geldi: ' . $th->getMessage()
            ], 500);
        }
        return $response;
    }
}
