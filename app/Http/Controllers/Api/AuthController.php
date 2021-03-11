<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request) {
        $input = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($input)) {
            return response([
               'message' => 'Invalid Entry'
            ]);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response([
           'user' => auth()->user(),
            'api_token' => $accessToken
        ]);

    }
}
