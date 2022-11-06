<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\ThuaDatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ThuaDatController extends Controller
{
    protected $thuaDatService;

    public function __construct(ThuaDatService $thuaDatService)
    {
        $this->thuaDatService = $thuaDatService;
    }

    public function getListThuaDat(Request $request){
        try {
            $result = $this->thuaDatService->getListThuaDatOfXaVien($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Lấy danh sách thửa đất thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Lấy danh sách thửa đất không thành công !",
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


    public function createThuaDat(Request $request){
        try {
            $result = $this->thuaDatService->createThuaDat($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 201,
                    "message" => "Tạo thửa đất thành công",
                    "errorList" => [],
                    "data" => $result
                ],201);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo thửa đất không thành công !",
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
