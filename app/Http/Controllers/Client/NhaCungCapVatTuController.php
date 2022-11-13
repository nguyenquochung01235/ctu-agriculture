<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\NhaCungCapVatTuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NhaCungCapVatTuController extends Controller
{
    protected $nhaCungCapVatTuService;

   public function __construct(NhaCungCapVatTuService $nhaCungCapVatTuService)
   {
        $this->nhaCungCapVatTuService = $nhaCungCapVatTuService;
   }

   public function infoDashBoard(){
    try {
        $result = $this->nhaCungCapVatTuService->getInfoDashBoard();
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
   

   public function getDetailNhaCungCapVatTu(Request $request){
    try {
        $result = $this->nhaCungCapVatTuService->getDetailNhaCungCapVatTu($request);
        if($result != false){
            return response()->json([
                "statusCode" => 200,
                "message" => "Thông tin chi tiết nhà cung cấp vật tư",
                "errorList" => [],
                "data" => $result
            ],200);
        }

        return response()->json([
            "statusCode" => 400,
            "message" => "Tạo hợp đồng không thành công",
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

   public function updateNhaCungCapVatTu(Request $request){
    try {
        $result = $this->nhaCungCapVatTuService->updateNhaCungCapVatTu($request);
        if($result){
            return response()->json([
                "statusCode" => 200,
                "message" => "Cập nhật thông tin nhà cung cấp vật tư thành công",
                "errorList" => [],
                "data" => $result
            ],200);
        }

        return response()->json([
            "statusCode" => 400,
            "message" => "Không cập nhật được thông tin nhà cung cấp vật tư !",
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
