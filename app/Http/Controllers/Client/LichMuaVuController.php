<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\LichMuaVuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LichMuaVuController extends Controller
{

    protected $lichMuaVuService;

    public function __construct(LichMuaVuService $lichMuaVuService)
    {
        $this->lichMuaVuService = $lichMuaVuService;
    }


    public function getListLichMuaVuForHopDongMuaBan($id_hoptacxa){
        try {
            $result = $this->lichMuaVuService->getListLichMuaVuForHopDongMuaBan($id_hoptacxa);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách mùa vụ",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách mùa vụ",
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

    public function getDetailLichMuaVu(Request $request){
        try {
            $result = $this->lichMuaVuService->getDetailLichMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết lịch mùa vụ",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy chi tiết lịch mùa vụ không thành công",
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

    public function getListLichMuaVu(Request $request){
        try {
            $result = $this->lichMuaVuService->getListLichMuaVu($request);
            if($result != null){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách lịch mùa vụ",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách lịch mùa vụ không thành công !",
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

    public function createLichMuaVu(Request $request){
        try {
            $result = $this->lichMuaVuService->createLichMuaVu($request);
            if($result != null){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo lịch mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo lịch mùa vụ không thành công !",
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
    public function updateLichMuaVu(Request $request){
        try {
            $result = $this->lichMuaVuService->updateLichMuaVu($request);
            if($result != null){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật lịch mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập lịch mùa vụ không thành công !",
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

    public function deleteLichMuaVu(Request $request){
        try {
            $result = $this->lichMuaVuService->deleteLichMuaVu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa lịch mùa vụ thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa lịch mùa vụ không thành công !",
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
