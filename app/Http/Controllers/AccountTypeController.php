<?php

namespace App\Http\Controllers;

use App\Http\Services\AccountTypeService;
use Illuminate\Http\Request;

class AccountTypeController extends Controller
{

    protected $accountTypeService;

    public function __construct(AccountTypeService $accountTypeService)
    {
        $this->accountTypeService = $accountTypeService;
    }

    public function getAllAccountType(){
        try {
            $dataAccountType = $this->accountTypeService->getAllAccountType();
           return response()->json([
            "statusCode" => 200,
            "message" => "Get User Account Type Successfully !",
            "errorList" => [],
            "data" => $dataAccountType
        ],200);
        } catch (\Exception $error) {
            return response()->json([
                "statusCode" => 400,
                "message" => "Can't get data",
                "errorList" => [$error->getMessage()],
                "data" => null
            ],400);
        }
    }


}
