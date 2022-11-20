<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\XaVienService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class XaVienController extends Controller
{
    protected $xaVienService;

    public function __construct(XaVienService $xaVienService)
    {
        $this->xaVienService = $xaVienService;
    }


    public function getDetail(){
        $request = null;
        try {
            $result = $this->xaVienService->getDetail($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin chi tiết xã viên",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin !",
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
    public function getDetailByHTX($id_user){
        try {
            $result = $this->xaVienService->getDetail($id_user);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin chi tiết xã viên",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin !",
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

    public function getListXaVienOfHTX(Request $request){
        try {
            $result = $this->xaVienService->getListXaVienOfHTX($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách xã viên của hợp tác xã",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin !",
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

    public function getRoleXaVien(Request $request){
        try {
            $result = $this->xaVienService->getRoleXaVien($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin phân quyền xã viên",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }else{
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin phân quyền xã viên",
                    "errorList" => [],
                    "data" => null
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin phân quyền !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [ $error],
                "data" => null
            ],400);
         }
    }

    public function searchXaVienByPhoneNumber(Request $request){
        try {
            $result = $this->xaVienService->searchXaVienByPhoneNumber($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin xã viên",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin xã viên !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [ $error],
                "data" => null
            ],400);
         }
    }
    public function updateXaVien(Request $request){
        try {
            $result = $this->xaVienService->updateXaVien($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật thông tin xã viên thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không cập nhật được thông tin xã viên !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [ $error],
                "data" => null
            ],400);
         }
    }



}
