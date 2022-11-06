<?php

namespace App\Http\Controllers;

use App\Http\Services\UploadImageService;
use Illuminate\Http\Request;

class UploadImageController extends Controller
{
    protected $uploadImageService;

    public function __construct(UploadImageService $uploadImageService)
    {
        $this->uploadImageService = $uploadImageService;
    }


    public function uploadImage(Request $request){
        $link = $this->uploadImageService->store($request);
        return response()->json([
            "statusCode" => 200,
            "message" => "Upload img successfull",
            "errorList" => [],
            "data" => $link
        ],200);
    }
}