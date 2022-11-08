<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Services\ClientService\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {   
        $this->notificationService = $notificationService;
    }

    public function getNotification(Request $request){
        try {
            $result = $this->notificationService->getNotificationService($request);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Danh sách thông báo",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
         } catch (\Exception $error) {
           
         }
    }
    public function makeReadNotification($id){
        try {
            $result = $this->notificationService->makeReadNotificationService($id);
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "make read",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
         } catch (\Exception $error) {
           
         }
    }

    public function isReadAllNotify(){
        try {
            $result = $this->notificationService->isReadAllNotify();
            if($result != false){
                return response()->json([
                    "statusCode" => 200,
                    "message" => "Data read notify",
                    "errorList" => [],
                    "data" => $result
                ],200);
            }
         } catch (\Exception $error) {
           
         }
    }
}
