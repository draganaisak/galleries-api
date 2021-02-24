<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request) {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        $token = auth('api')->login($user);
        return [
            'token' => $token,
            'user' => $user
        ];
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
          ];
          $token = auth('api')->attempt($credentials);
          if (!$token) {
            return response()->json(['message' => 'Unauthenticated'], 401);
          }
          return [
            'token' => $token,
            'user' => auth('api')->user()
          ];
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function me()
    {
        return auth('api')->user();
    }

    public function refreshToken()
    {
        $token = auth('api')->refresh();
        return [
            'token' => $token
        ];
    }
}
