<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\NhatKyDongRuongService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class NhatKyDongRuongController extends Controller
{
    
    protected $nhatKyDongRuongService;

    public function __construct(NhatKyDongRuongService $nhatKyDongRuongService)
    {
        $this->nhatKyDongRuongService = $nhatKyDongRuongService;
    }

    public function getDetailNhatKyDongRuong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->getDetailNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy chi tiết nhật ký hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy chi tiết nhật ký hoạt động không thành công !",
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

    public function getListNhatKyDongRuong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->getListNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách hoạt động mùa vụ",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách hoạt động mùa vụ không thành công !",
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

    public function getListNhatKyDongRuongForHTX(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->getListNhatKyDongRuongForHTX($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách hoạt động mùa vụ",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách hoạt động mùa vụ không thành công !",
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

    public function toggleActiveNhatKyDongRuong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->toggleActiveNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật trạng thái hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật trạng thái hoạt động không thành công !",
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
    public function approveNhatKyDongRuong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->approveNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật trạng thái hoạt động nhật ký thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật trạng thái hoạt động nhật ký không thành công !",
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

    public function attachHoatDongIntoNhatKyFromHopTacXaToXaVien(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->attachHoatDongIntoNhatKy($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Áp hoạt động mùa vụ cho toàn bộ xã viên thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Áp hoạt động mùa vụ cho toàn bộ xã viên không thành công !",
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
    public function addNewNhatKyHoatDong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->addNewNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo hoạt động không thành công !",
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

    public function updateNhatKyHoatDong(Request $request){
        try {
            $result = $this->nhatKyDongRuongService->updateNhatKyDongRuong($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Cập nhật hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật hoạt động không thành công !",
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

    public function deleteNhatKyHoatDong($id_nhatkydongruong){
        try {
            $result = $this->nhatKyDongRuongService->deleteNhatKyHoatDong($id_nhatkydongruong);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Xóa hoạt động thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa hoạt động không thành công !",
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
