<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\GiaoDichMuaBanLuaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanLuaController extends Controller
{
    protected $giaoDichMuaBanLuaService;

    public function __construct(GiaoDichMuaBanLuaService $giaoDichMuaBanLuaService)
    {
        $this->giaoDichMuaBanLuaService = $giaoDichMuaBanLuaService;
    }

    public function getDetailGiaoDichMuaBanLua($id_giaodichmuabanlua)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->getDetailGiaoDichMuaBanLua($id_giaodichmuabanlua);

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

    public function getListGiaoDichMuaBanLua(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->getListGiaoDichMuaBanLua($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch lúa",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao dịch lúa",
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
    
    public function getListGiaoDichMuaBanLuaForHTX(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->getListGiaoDichMuaBanLuaForHTX($request);

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

    
    public function updateGiaoDichMuaBanLua(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->updateGiaoDichMuaBanLua($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật giao dịch mua bán lúa thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật giao dịch mua bán lúa không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc cập nhật giao dịch",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }

    public function confirmGiaoDichMuaBanLua(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->confirmGiaoDichMuaBanLua($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật trạng thái giao dịch mua bán lúa thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật trạng thái giao dịch mua bán lúa không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc cập nhật trạng thái giao dịch",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }

    public function approveGiaoDichMuaBanLua(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanLuaService->approveGiaoDichMuaBanLua($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Duyệt trạng thái giao dịch mua bán lúa thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Duyệt trạng thái giao dịch mua bán lúa không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc duyệt trạng thái giao dịch",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }    
}
