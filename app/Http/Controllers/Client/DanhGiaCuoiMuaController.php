<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\DanhGiaCuoiMuaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DanhGiaCuoiMuaController extends Controller
{

    protected $danhGiaCuoiMuaService;

    public function __construct(DanhGiaCuoiMuaService $danhGiaCuoiMuaService)
    {
        $this->danhGiaCuoiMuaService = $danhGiaCuoiMuaService;
    }

    public function getDetailDanhGiaCuoiMua(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->getDetailDanhGiaCuoiMua($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết đánh giá cuối mùa",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lây được chi tiết đánh giá cuối mùa",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xử lý",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }
    
    public function getListDanhGiaCuoiMua(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->getListDanhGiaCuoiMua($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách đánh giá cuối mùa",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lây được danh sách đánh giá cuối mùa",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xử lý",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }
    public function getListDanhGiaCuoiMuaHTX(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->getListDanhGiaCuoiMuaHTX($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách đánh giá cuối mùa",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lây được danh sách đánh giá cuối mùa",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xử lý",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }

    public function createDanhGiaCuoiMua(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->createDanhGiaCuoiMua($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Tạo đánh giá cuối mùa thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo đánh giá cuối mùa không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo đánh giá cuối mùa",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }
    public function updateDanhGiaCuoiMua(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->updateDanhGiaCuoiMua($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật đánh giá cuối mùa thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật đánh giá cuối mùa không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc cập nhật đánh giá cuối mùa",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }

    public function deleteDanhGiaCuoiMua(Request $request){
        try {
            $result = $this->danhGiaCuoiMuaService->deleteDanhGiaCuoiMua($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa đánh giá cuối mùa thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa đánh giá cuối mùa không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
            } catch (\Exception $error) {
            
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xóa đánh giá cuối mùa",
                "errorList" => [$error],
                "data" => null
            ],400);
            }
    }
    
}
