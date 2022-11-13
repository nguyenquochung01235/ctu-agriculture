<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\GiaoDichMuaBanLuaGiongService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanLuaGiongController extends Controller
{
    protected $giaoDichMuaBanLuaGiongService;

    public function __construct(GiaoDichMuaBanLuaGiongService $giaoDichMuaBanLuaGiongService)
    {
        $this->giaoDichMuaBanLuaGiongService = $giaoDichMuaBanLuaGiongService;
    }

    public function getDetailGiaoDichMuaBanLuaGiong($id_giaodichmuabanluagiong)
    {
        try {
            $result = $this->giaoDichMuaBanLuaGiongService->getDetailGiaoDichMuaBanLuaGiong($id_giaodichmuabanluagiong);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết giao dịch",
                    "errorList" => [],
                    "data" => $result
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

    public function getListGiaoDichMuaBanLuaGiong(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaGiongService->getListGiaoDichMuaBanLuaGiong($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch lúa giống",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao dịch lúa giống",
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
    public function getListGiaoDichMuaBanLuaGiongForHTX(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaGiongService->getListGiaoDichMuaBanLuaGiongForHTX($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch lúa giống",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao dịch lúa giống",
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

    public function createGiaoDichMuaBanLuaGiong(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaGiongService->createGiaoDichMuaBanLuaGiong($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Tạo giao dịch mua bán lúa giống thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo giao dịch mua bán lúa giống không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo giao dịch",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }
    // public function updateGiaoDichMuaBanLuaGiong(Request $request)
    // {
    //     try {
    //         $result = $this->giaoDichMuaBanLuaGiongService->updateGiaoDichMuaBanLuaGiong($request);
    //         if ($result) {
    //             return response()->json([
    //                 "statusCode" => 200,
    //                 "message" => "Tạo giao dịch mua bán lúa giống thành công",
    //                 "errorList" => [],
    //                 "data" => $result
    //             ], 200);
    //         }

    //         return response()->json([
    //             "statusCode" => 400,
    //             "message" => "Tạo giao dịch mua bán lúa giống không thành công !",
    //             "errorList" => [Session::get('error')],
    //             "data" => null
    //         ], 400);
    //     } catch (\Exception $error) {

    //         return response()->json([
    //             "statusCode" => 400,
    //             "message" => "Có lỗi trong lúc tạo giao dịch",
    //             "errorList" => [$error],
    //             "data" => null
    //         ], 400);
    //     }
    // }
}
