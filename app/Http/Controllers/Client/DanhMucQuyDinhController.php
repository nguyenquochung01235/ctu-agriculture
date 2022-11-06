<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\DanhMucQuyDinhService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DanhMucQuyDinhController extends Controller
{
    protected $danhMucQuyDinhService;

    public function __construct(DanhMucQuyDinhService $danhMucQuyDinhService)
    {
        $this->danhMucQuyDinhService = $danhMucQuyDinhService;
    }

    public function getDetailDanhMucQuyDinh($id_danhmucquydinh){
        try {
            $result = $this->danhMucQuyDinhService->getDetailDanhMucQuyDinh($id_danhmucquydinh);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy chi tiết danh mục thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy chi tiết danh mục không thành công !",
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

    public function getListDanhMucQuyDinh(Request $request){
        try {
            $result = $this->danhMucQuyDinhService->getListDanhMucQuyDinh($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách danh mục quy đinh",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách danh mục không thành công !",
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


    public function createDanhMucQuyDinh(Request $request){
        try {
            $result = $this->danhMucQuyDinhService->createDanhMucQuyDinh($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo danh mục quy định thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo danh mục quy định không thành công !",
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
    public function updateDanhMucQuyDinh(Request $request){
        try {
            $result = $this->danhMucQuyDinhService->updateDanhMucQuyDinh($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Cập nhật danh mục quy định thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật danh mục quy định không thành công !",
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

    public function deleteDanhMucQuyDinh($id_danhmucquydinh){
        try {
            $result = $this->danhMucQuyDinhService->deleteDanhMucQuyDinh($id_danhmucquydinh);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa danh mục quy đinh thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa danh danh mục không thành công !",
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
