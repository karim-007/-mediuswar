<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class LoginController extends Controller
{

    public function authentication(Request $request)
    {
        $data = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|min:6'
        ])->validate();

        if (!auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return response()->json(['status' => 'fail', 'message' => 'Incorrect Email or Password!'], 403);
        }

        $user = auth()->user();

        $accessToken = $user->createToken('authToken')->accessToken;
        $refreshToken = $user->createToken('refreshToken')->accessToken;
        return response()->json(['status' => 'success', 'user' => $user, 'access_token' => $accessToken, 'refresh_token' => $refreshToken], 200);

    }

}
