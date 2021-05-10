<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Models\User;

class AuthController extends ApiController
{
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
            return self::error_response([],"login failed");
        }
        return $this->respondWithToken($token);
    }

    public function me()
    {
        return self::success_response(auth()->user());
    }

    public function logout()
    {
        auth()->logout();
        return self::success_response();
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return self::success_response([
            'access_token' => $token,
            'user' => auth()->user(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}