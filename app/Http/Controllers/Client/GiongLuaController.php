<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\GiongLuaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GiongLuaController extends Controller
{
    protected $giongLuaService;

    public function __construct(GiongLuaService $giongLuaService)
    {
        $this->giongLuaService = $giongLuaService;
    }
    
    public function getListGiongLua(Request $request){
        try {
            $result = $this->giongLuaService->getListGiongLua($request)->makeHidden(['created_at','updated_at']);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách giống lúa",
                    "errorList" => [],
                    "data" => $result
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
}
