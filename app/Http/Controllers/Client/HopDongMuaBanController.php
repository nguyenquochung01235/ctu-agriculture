<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\HopDongMuaBanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HopDongMuaBanController extends Controller
{
    protected $hopDongMuaBanService;

    public function __construct(HopDongMuaBanService $hopDongMuaBanService)
    {
        $this->hopDongMuaBanService = $hopDongMuaBanService;
    }

    public function confirmHopDong($id_hopdongmuaban){
        try {
            $result = $this->hopDongMuaBanService->confirmHopDong($id_hopdongmuaban);

            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xác nhận hợp đồng thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xác nhận hợp đồng không thành công",
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

    public function getDetailHopDong($id_hopdongmuaban){
        try {
            $result = $this->hopDongMuaBanService->getDetailHopDong($id_hopdongmuaban);

            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết hợp đồng",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được chi tiết hợp đồng",
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
    public function getListHopDong(Request $request){
        try {
            $result = $this->hopDongMuaBanService->getListHopDong($request);

            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách hợp đồng",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách hợp đồng",
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

    public function updateHopDong(Request $request){
        try {
            $result = $this->hopDongMuaBanService->updateHopDong($request);

            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật hợp đồng thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không thể cập nhật hợp đồng",
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
    public function deleteHopDong($id_hopdongmuaban){
        try {
            $result = $this->hopDongMuaBanService->deleteHopDong($id_hopdongmuaban);

            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa hợp đồng thành công",
                    "errorList" => [],
                    "data" => $result
                    
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không xóa được hợp đồng",
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
