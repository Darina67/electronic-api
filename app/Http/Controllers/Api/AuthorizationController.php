<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\PersonalAccessTokenResult;
use Carbon\Carbon;

class AuthorizationController extends Controller
{
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $expiresAt = Carbon::now()->addMinutes(15);
            $token = $user->createToken('TechnoHub', ['role'])->accessToken;

            return response()->json([
                'status' => 200,
                'message' => 'Авторизация успешна!',
                'token' => [
                    'accessToken' => $token,
                    'expires_at' => $expiresAt,
                ],
            ], 200);

        } else {
            $data = [
                'status' => 404,
                'message' => 'Неправильный логин или пароль. Проверьте данные и повторите попытку!',
            ];
            return response()->json($data, 404);
        }
    }
}
