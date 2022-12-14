<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\DanhMucQuyDinhService;
use App\Http\Services\ClientService\HopDongMuaBanService;
use App\Http\Services\ClientService\ThuongLaiService;
use App\Models\DanhMucQuyDinh;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThuongLaiController extends Controller
{
   protected $hopDongMuaBanService;
   protected $thuongLaiService;

   public function __construct(HopDongMuaBanService $hopDongMuaBanService, ThuongLaiService $thuongLaiService)
   {
        $this->thuongLaiService = $thuongLaiService;
        $this->hopDongMuaBanService = $hopDongMuaBanService;
   }

   public function infoDashBoard(){
    try {
        $result = $this->thuongLaiService->getInfoDashBoard();
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
   

   public function getDetailThuongLai(Request $request){
    try {
        $result = $this->thuongLaiService->getDetailThuongLai($request);
        if($result != false){
            return response()->json([
                "statusCode" => 200,
                "message" => "Thông tin thương lái",
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

   public function thuongLaiCreateHopDongMuaBan(Request $request){
    try {
        $result = $this->hopDongMuaBanService->createHopDongMuaBan($request);
        if($result != false){
            return response()->json([
                "statusCode" => 201,
                "message" => "Tạo hợp đồng thành công",
                "errorList" => [],
                "data" => $result
            ],201);
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

   public function updateThuongLai(Request $request){
    try {
        $result = $this->thuongLaiService->updateThuongLai($request);
        if($result){
            return response()->json([
                "statusCode" => 200,
                "message" => "Cập nhật thông tin thương lái thành công",
                "errorList" => [],
                "data" => $result
            ],200);
        }

        return response()->json([
            "statusCode" => 400,
            "message" => "Không cập nhật được thông tin thương lái !",
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
