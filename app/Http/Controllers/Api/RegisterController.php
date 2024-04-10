<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Hash;


class RegisterController extends Controller
{
    public function registration(Request $request){
        $input  = $request->all();

        User::create ([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
        ]);
        $data = [
            'status' => 200,
            'message' => 'Регистрация успешна!',
        ];
        return response()->json($data, 200);
    }
}
