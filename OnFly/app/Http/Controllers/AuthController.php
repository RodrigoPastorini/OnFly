<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function me()
    {
        return response()->json(auth()->user());
    }

    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Successfully logged out']);
    }
}
