<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PostController extends Controller
{
    protected $postService;
    
    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }


    public function getDetailPost(Request $request){
        try {
            $result = $this->postService->getDetailPost($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chi tiết bài viết",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Chi tiết bài viết",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc lấy thông tin bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function getListPost(Request $request){
        try {
            $result = $this->postService->getListPost($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách bài viết",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Chi tiết bài viết",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc lấy thông tin bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function getListPostOfUser(Request $request){
        try {
            $result = $this->postService->getListPostOfUser($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách bài viết",
                    "errorList" => [],
                    "data" => $result[0],
                    "meta" => $result[1]
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Chi tiết bài viết",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc lấy thông tin bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    

    public function createPost(Request $request){
        try {
            $result = $this->postService->createPost($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Tạo bài viết thành công, vui lòng chờ duyệt",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Tạo bài viết không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function updatePost(Request $request){
        try {
            $result = $this->postService->updatePost($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Cập nhật bài viết thành công, vui lòng chờ duyệt",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Cập nhật bài viết không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc cập nhật bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
    public function deletePost(Request $request){
        try {
            $result = $this->postService->deletePost($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa bài viết thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa bài viết không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xóa bài viết",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
}
