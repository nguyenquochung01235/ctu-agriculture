<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\HoatDongMuaVuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HoatDongMuaVuController extends Controller
{
    protected $hoatDongMuaVuService;

    public function __construct(HoatDongMuaVuService $hoatDongMuaVuService)
    {
        $this->hoatDongMuaVuService = $hoatDongMuaVuService;
    }


    public function getDetailHoatDongMuaVu(Request $request){
        try {
            $result = $this->hoatDongMuaVuService->getDetailHoatDongMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy chi tiết hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy chi tiết hoạt động không thành công !",
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

    public function getListHoatDongMuaVu(Request $request){
        try {
            $result = $this->hoatDongMuaVuService->getListHoatDongMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách hoạt động mùa vụ",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách hoạt động mùa vụ không thành công !",
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

   
    public function createHoatDongMuaVu(Request $request){
        try {
            $result = $this->hoatDongMuaVuService->createHoatDongMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo hoạt động mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo hoạt động mùa vụ không thành công !",
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

    public function updateHoatDongMuaVu(Request $request){
        try {
            $result = $this->hoatDongMuaVuService->updateHoatDongMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật hoạt động mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật hoạt động mùa vụ không thành công !",
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
    public function deleteHoatDongMuaVu($id_hoatdongmuavu){
        try {
            $result = $this->hoatDongMuaVuService->deleteHoatDongMuaVu($id_hoatdongmuavu);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Xóa hoạt động mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa hoạt động mùa vụ không thành công !",
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
