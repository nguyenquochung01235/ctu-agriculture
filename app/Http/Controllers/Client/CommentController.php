<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CommentController extends Controller
{
    protected $commentService;
    
    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function createComment(Request $request){
        try {
            $result = $this->commentService->createComment($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Bình luận thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Bình luận không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc tạo bình luận",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }

    public function updateComment(Request $request){
        try {
            $result = $this->commentService->updateComment($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Chỉnh sửa bình luận thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Chỉnh sửa bình luận không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc chỉnh sửa bình luận",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
    public function deleteComment(Request $request){
        try {
            $result = $this->commentService->deleteComment($request);
            if($result){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Xóa bình luận thành công",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
    
            return response()->json([
                "statusCode" => 400,
                "message" => "Xóa bình luận không thành công",
                "errorList" => [Session::get('error')],
                "data" => null
            ],400);
         } catch (\Exception $error) {
           
            return response()->json([
                "statusCode" => 400,
                "message" => "Có lỗi trong lúc xóa bình luận",
                "errorList" => [$error],
                "data" => null
            ],400);
         }
    }
}
