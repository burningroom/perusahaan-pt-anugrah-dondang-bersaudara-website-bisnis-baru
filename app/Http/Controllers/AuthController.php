<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), User::loginValidator());

        if ($validator->fails())
            return $this->sendError('Input tidak sesuai dengan ketentuan.', $validator->errors()->toArray(), 422);

        $email = $request->input('username_email');
        $password = $request->input('password');
        $fcm_token = $request->input('fcm_token');

        $username_email = Str::lower($email);

        $user = User::where('username', $username_email)
            ->orWhere('email', $username_email)
            ->first();

        if (!$user) return $this->sendError("Data User $this->notfound_msg");

        if (!Hash::check($password, $user->password))
            return $this->sendError("Password Anda Salah!");

        if ($fcm_token) {
            $user->fcm_token = $fcm_token;
            $user->save();
        }

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        $data = [
            'role_id' => $user->roles()->first()->id,
            'role' => $user->roles()->first()->name,
            'access_token' => $token,
            'token_type' => 'Bearer'
        ];

        return $this->sendResponse($data, 'Hi ' . $user->name . ', Selamat Datang di ' . config('app.name') . '!');
    }

    public function logout()
    {
        $user = Auth::user();

        $get_user = User::find($user?->id);

        if (!$get_user) {
            return $this->sendError('Anda belum Login');
        }

        $get_user?->update([
            'fcm_token' => null
        ]);

        $get_user->tokens()->delete();

        return $this->sendSuccess('Anda Berhasil Logout!');
    }

}
