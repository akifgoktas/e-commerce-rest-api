<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AdminModel;
use App\Http\Requests\AdminRegisterRequest;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    private function mailControl($admin_email)
    {
        $exists = AdminModel::where('email', $admin_email)->exists();
        return $exists;
    }

    private function phoneNumberControl($admin_phone_number)
    {
        $exists = AdminModel::where('phone_number', $admin_phone_number)->exists();
        return $exists;
    }

    public function register(AdminRegisterRequest $request)
    {
        $admin_email         = $this->mailControl($request->email);
        $admin_phone_number  = $this->phoneNumberControl($request->phone_number);

        if ($admin_email == true || $admin_phone_number == true) {
            if ($admin_email == true) {
                $message = 'Bu mail ile daha önce kayıt yaptırılmış';
            } elseif ($admin_phone_number == true) {
                $message = 'Bu telefon numarası ile daha önce kayıt yaptırılmış';
            }
            $response = response()->json([
                'status'    => 'error',
                'message'   => 'Admin Eklenemedi: ' . $message
            ], 500);
        } else {
            try {
                $validated_data = $request->validated();
                $admin_save = AdminModel::create($validated_data);
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Admin başarılı bir şekilde eklendi'
                ], 201);
            } catch (\Throwable $th) {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Admin Eklenemedi: ' . $th->getMessage()
                ], 500);
            }
        }

        return $response;
    }
    public function login(AdminLoginRequest $request)
    {
        try {
            $admin_login = AdminModel::where('email', $request->email)->where('password', $request->password)->first();
            if ($admin_login) {
                Session::put([
                    'admin_status'   => true,
                    'admin_id'       => $admin_login->id
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
            Session::forget(['admin_id']);
            Session::put(['admin_status' => false]);
            if (Session::get('admin_status') ===  false) {
                $response = response()->json([
                    'status'    => 'success',
                    'message'   => 'Oturum kapatıldı'
                ], 201);
            } else {
                $response = response()->json([
                    'status'    => 'error',
                    'message'   => 'Oturumdan kapatılmadı'
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
