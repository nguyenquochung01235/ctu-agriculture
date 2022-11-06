<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\HopTacXaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HopTacXaController extends Controller
{

    protected $hopTacXaService;

    public function __construct(HopTacXaService $hopTacXaService)
    {
        $this->hopTacXaService = $hopTacXaService;
    }

    public function searchHopTacXaByPhoneNumber(Request $request){
        try {
            $result = $this->hopTacXaService->searchHopTacXaByPhoneNumber($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin hợp tác xã",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không tìm thấy !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo hợp tác xã",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function infoDashBoard(){
        try {
            $result = $this->hopTacXaService->getInfoDashBoard();
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

    public function getChuNhiemHTX($id_hoptacxa){
        try {
            $result = $this->hopTacXaService->getChuNhiemHTX($id_hoptacxa);
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

    public function getDetail(){
        try {
            $result = $this->hopTacXaService->getDetail();
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


    

    public function createNewHTX(Request $request){
        try {
            $result = $this->hopTacXaService->createNewHTX($request);
            if($result){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo hợp tác xã thành công ! Vui lòng chờ duyệt hồ sơ",
                    "errorList" => [],
                    "data" => null
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo hợp tác xã không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo hợp tác xã",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function updateHTX(Request $request){
        try {
            $result = $this->hopTacXaService->updateHTX($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật thông tin hợp tác xã thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật thông tin hợp tác xã không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo hợp tác xã",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
    
    public function addNewMemberToHTX(Request $request){
        try {
            $result = $this->hopTacXaService->addNewMemberToHTX($request);
            if($result){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Thêm mới xã viên vào hợp tác xã thành công !",
                    "errorList" => [],
                    "data" => null
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Thêm mới xã viên vào hợp tác xã không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc thêm mới xã viên vào hợp tác xã",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function deleteMemberToHTX($id_user){
        try {
            $result = $this->hopTacXaService->deleteMemberToHTX($id_user);
            if($result){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Xóa xã viên khỏi hợp tác xã thành công !",
                    "errorList" => [],
                    "data" => null
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa xã viên khỏi hợp tác xã không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xóa xã viên khỏi hợp tác xã",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function toggleActiveMemberHTX(Request $request){
        try {
            $result = $this->hopTacXaService->toggleActiveMemberHTX($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Đổi trạng thái hoạt động của xã viên thành công !",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Đổi trạng thái hoạt động của xã viên không thành công !",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc đổi trạng thái hoạt động của xã viên",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
}
