<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApiController extends Controller
{
    //Register API
    public function register(Request $request) {
        $request -> validate ([
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|confirmed"
        ]);

        User::create ([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password)
        ]);

        //Response
        return response() -> json ([
            "status" => true,
            "message" => "User created successfully."
        ]);
    }

    //Login API 
    public function login(Request $request) {

        //data validation
        $request -> validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        //JWtAuth and attempt
        $token = JWTAuth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!empty($token)) {
            //Response
            return response() -> json ([
                "status" => true,
                "message" => "User logged in successfully.",
                "token" => $token
            ]);

            return response() -> json ([
                "status" => false,
                "message" => "Invalid login."
            ]);

        }

        
    }

    //Profiloe API
    public function profile() {

        $userData = auth() -> user ([]);
        return response()->json ([
            "status" => true,
            "message" => "Profile data",
            "user" => $userData
        ]);
        
    }

    //Refresh Token API
    public function refreshToken() {
        $newToken = auth() -> refresh();

        return response() -> json ([
            "status" => true,
            "message" => "New access token generated.",
            "token" => $newToken
        ]);
    }

    //Loginout API
    public function logout() {
        auth()  -> logout();

        return response() -> json ([
            "status" => true,
            "message" => "User logged out successfully.",
        ]);
    }
}
