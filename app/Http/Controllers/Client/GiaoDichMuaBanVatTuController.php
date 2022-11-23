<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\GiaoDichMuaBanVatTuService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GiaoDichMuaBanVatTuController extends Controller
{
    protected $giaoDichMuaBanVatTuService;

    public function __construct(GiaoDichMuaBanVatTuService $giaoDichMuaBanVatTuService)
    {
        $this->giaoDichMuaBanVatTuService = $giaoDichMuaBanVatTuService;
    }

    public function getDetailGiaoDichMuaBanVatTu($id_giaodichmuabanvattu)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->getDetailGiaoDichMuaBanVatTu($id_giaodichmuabanvattu);

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

    public function getListGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->getListGiaoDichMuaBanVatTu($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch vật tư",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao dịch vật tư",
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


    public function getListGiaoDichMuaBanVatTuForHTX(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->getListGiaoDichMuaBanVatTuForHTX($request);

            if ($result != false) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giao dịch vật tư",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được danh sách giao dịch vật tư",
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


    public function createGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->createGiaoDichMuaBanVatTu($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Tạo giao dịch mua bán vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo giao dịch mua bán vật tư không thành công !",
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

    public function updateGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->updateGiaoDichMuaBanVatTu($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật giao dịch mua bán vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật giao dịch mua bán vật tư không thành công !",
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

    public function deleteGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->deleteGiaoDichMuaBanVatTu($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa giao dịch mua bán vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa giao dịch mua bán vật tư không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ], 400);
        } catch (\Exception $error) {

            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xóa giao dịch",
                "errorList" => [$error],
                "data" => null
            ], 400);
        }
    }

    public function confirmGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->confirmGiaoDichMuaBanVatTu($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật trạng thái giao dịch mua bán vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật trạng thái giao dịch mua bán vật tư không thành công !",
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


    public function approveGiaoDichMuaBanVatTu(Request $request)
    {
        try {
            $result = $this->giaoDichMuaBanVatTuService->approveGiaoDichMuaBanVatTu($request);
            if ($result) {
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Duyệt trạng thái giao dịch mua bán vật tư thành công",
                    "errorList" => [],
                    "data" => $result
                ], 200);
            }

            return response()->json([
                "statusCode" => 400,
                "message" => "Duyệt trạng thái giao dịch mua bán vật tư không thành công !",
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
