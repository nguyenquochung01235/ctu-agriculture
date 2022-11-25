<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\VatTuSuDungService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class VatTuSuDungController extends Controller
{

    protected $vatTuSuDungService;

    public function __construct(VatTuSuDungService $vatTuSuDungService)
    {
        $this->vatTuSuDungService = $vatTuSuDungService;
    }

    public function autoCompleteVatTuSuDung(Request $request){
        try {
           $result = $this->vatTuSuDungService->autoCompleteVatTuSuDung($request);
           if($result){
               return response()->json([
                   "statusCode" => 200,
                   "message" => "Danh sách vật tư sử dụng của mùa vụ",
                   "errorList" => [],
                   "data" =>  $result
               ],200);
           }
   
           return response()->json([
               "statusCode" => 400,
               "message" => "Không lấy được thông tin",
               "errorList" => [Session::get('error')],
               "data" => null
           ],400);
        } catch (\Exception $error) {
          
           return response()->json([
               "statusCode" => 400,
               "message" => "Có lỗi trong lúc lấy thông tin danh sách vật tư",
               "errorList" => [ $error],
               "data" => null
           ],400);
        }
       }
}
