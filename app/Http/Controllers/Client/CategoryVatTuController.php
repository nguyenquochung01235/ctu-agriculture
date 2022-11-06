<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\CategoryVatTuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryVatTuController extends Controller
{
 
    protected $categoryVatTuService;

    public function __construct(CategoryVatTuService $categoryVatTuService)
    {
        $this->categoryVatTuService = $categoryVatTuService;
    }

    public function getDetailCategoryVatTu($id_category_vattu){
        try {
            $result = $this->categoryVatTuService->getDetailCategoryVatTu($id_category_vattu);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy chi tiết vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy chi tiết vật tư không thành công !",
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

    public function getListCategoryVatTu(Request $request){
        try {
            $result = $this->categoryVatTuService->getListCategoryVatTu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách vật tư",
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

    public function createCategoryVatTu(Request $request){
        try {
            $result = $this->categoryVatTuService->createCategoryVatTu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Thêm vật tư mới vào danh mục thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Thêm vật tư mới vào danh mục không thành công !",
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
    public function updateCategoryVatTu(Request $request){
        try {
            $result = $this->categoryVatTuService->updateCategoryVatTu($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật thông tin vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật thông tin vật tư không thành công !",
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
    public function deleteCategoryVatTu($id_category_vattu){
        try {
            $result = $this->categoryVatTuService->deleteCategoryVatTu($id_category_vattu);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Xóa vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa vật tư không thành công !",
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
