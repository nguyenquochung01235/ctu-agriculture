<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\XaVienService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class XaVienController extends Controller
{
    protected $xaVienService;

    public function __construct(XaVienService $xaVienService)
    {
        $this->xaVienService = $xaVienService;
    }


    public function getListXaVienOfHTX(Request $request){
        try {

           

            $result = $this->xaVienService->getListXaVienOfHTX($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách xã viên của hợp tác xã",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin !",
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

    public function getRoleXaVien(){
        try {
            $result = $this->xaVienService->getRoleXaVien();
            if($result != null){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin phân quyền xã viên",
                    "errorList" => [],
                    "data" => [
                        "id_hoptacxa"=> $result->id_hoptacxa,
                        "role" => $result->role[0]->role,
                        "name_hoptacxa" => $result->hop_tac_xa->name_hoptacxa
                    ]
                ],200);
            }else{
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin phân quyền xã viên",
                    "errorList" => [],
                    "data" => null
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin phân quyền !",
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

    public function searchXaVienByPhoneNumber(Request $request){
        try {
            $result = $this->xaVienService->searchXaVienByPhoneNumber($request->phone_number);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Thông tin xã viên",
                    "errorList" => [],
                    "data" => [
                        "id_user"=> $result->id_user,
                        "id_xavien"=> $result->xavien->id_xavien,
                        "fullname" => $result->fullname,
                        "phone_number" => $result->phone_number,
                        "dob" => $result->dob,
                        "address" => $result->address,
                        "thumbnail" => $result->xavien->thumbnail,
                        "active" => $result->active,
                        "id_hoptacxa" => $result->xavien->id_hoptacxa
                    ]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Không lấy được thông tin xã viên !",
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
