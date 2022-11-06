<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\MenuClientService;
use Illuminate\Http\Request;

class MenuClientController extends Controller
{
    protected $menuClientService;

    public function __construct(MenuClientService $menuClientService)
    {
        $this->menuClientService = $menuClientService;
    }

    public function getMenuClient(){
        try {
           $result = $this->menuClientService->getMenuClientService();
           return response()->json([
            "statusCode" => 200,
            "message" => "Get Data Role Of User Successfully !",
            "errorList" => [],
            "data" => $result
        ],200);
        } catch (\Exception $error) {
            return response()->json([
                "statusCode" => 400,
                "message" => "Can't get data",
                "errorList" => [$error->getMessage()],
                "data" => NULL
            ],400);
        }
    }

}
