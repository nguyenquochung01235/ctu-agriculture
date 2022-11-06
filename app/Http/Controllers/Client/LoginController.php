<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\UserService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller {
    
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    

    function login(Request $request){
        $request->validate([
            'phone_number' => 'required',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('phone_number', 'password');
        
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json([
                "statusCode" => 401,
                "message" => "Unauthorized",
                "errorList" => [],
                "data" => null
            ],401);
        }

        if (Auth::user()->active == 0) {
            return response()->json([
                "statusCode" => 403,
                "message" => "Tài khoản đã bị khóa",
                "errorList" => [],
                "data" => null
            ],403);
        }
        
        $user = Auth::user();
        return response()->json([
            "statusCode" => 200,
            "message" => "Đăng nhập thành công",
            "errorList" => [],
            "data" => $this->userService->getUserWithAccountType($user->id_user),
            "access_token" =>$token
        ],200);    
       
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function me()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
    
}