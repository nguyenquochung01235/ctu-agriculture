<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Http\Services\ClientService\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function createNewUser(RegisterFormRequest $request){
     try {
        $result = $this->userService->createNewUser($request);
        if($result){
            return response()->json([
                "statusCode" => 200,
                "message" => "Tạo tài khoản thành công !",
                "errorList" => [],
                "data" => null
            ],200);
        }

        return response()->json([
            "statusCode" => 400,
            "message" => "Tạo tài khoản không thành công !",
            "errorList" => [Session::get('error')],
            "data" => null
        ],400);
     } catch (\Exception $error) {
       
        return response()->json([
            "statusCode" => 400,
            "message" => "Có lỗi trong lúc tạo tài khoản",
            "errorList" => [ $error],
            "data" => null
        ],400);
     }
    }

    public function getDetailUser(){
        try {
            $result = $this->userService->getDetail();
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy thông tin thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không có thông tin !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc lấy thông tin",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
    public function updateUser(Request $request){
        try {
            $result = $this->userService->updateUser($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy thông tin thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không có thông tin !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc lấy thông tin",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
    public function updatePassword(Request $request){
        try {
            $result = $this->userService->updatePassword($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật mật khẩu thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật mật khẩu không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

}
