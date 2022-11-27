<?php

namespace App\Http\Controllers\BlockChainController;

use App\Http\Controllers\Controller;
use App\Http\Services\BlockChainService\TruyXuatNguonGocService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TruyXuatNguonGocController extends Controller
{

    protected $truyXuatNguonGocService;

    public function __construct(
        TruyXuatNguonGocService $truyXuatNguonGocService
    )
    {
        $this->truyXuatNguonGocService = $truyXuatNguonGocService;
    }

    public function autoCompleteSearchHopTacXa(Request $request){
        try {
            $result = $this->truyXuatNguonGocService->autoCompleteSearchHopTacXa($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy thông tin thành công",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
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

    public function autoCompleteSearchLichMuaVu(Request $request){
        try {
            $result = $this->truyXuatNguonGocService->autoCompleteSearchLichMuaVu($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy thông tin thành công",
                    "errorList" => [],
                    "data" => $result,
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

    public function getListLoHangLua(Request $request)
    {
        try {
            $result = $this->truyXuatNguonGocService->getListLoHangLua($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }


    public function truyXuatLoHangLua(Request $request)
    {
        try {
            $result = $this->truyXuatNguonGocService->truyXuatLoHangLua($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết giao dịch",
                    "errorList" => [],
                    "data" => $result,
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được chi tiết giao dịch",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thực hiện",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }
}
