<?php

namespace App\Http\Services\ClientService;

use App\Events\Notification as EventsNotification;
use App\Http\Services\CommonService;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class NotificationService{

    
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
        
    }

    public function getNotificationService($request){
        $page = $request->page;
        if($page == null || $page == 0 || $page < 0){
            $page = 1;
        }
        $id_user = $this->commonService->getIDByToken();
        $list_notification = Notification::where('user', $id_user)
        ->skip(($page-1)*10)
        ->take(10)
        ->orderBy('created_at', 'desc')
        ->get();
        return $list_notification;
    }

    public function makeReadNotificationService($id){
        try{
            Notification::where('id', $id)->update([
                'status' => 1
            ]);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function createNotificationService($message,$status,$user,$link){
        try {
            $notify = Notification::create([
                'message' => $message,
                'status' => $status,
                'user' => $user,
                'link' => $link,
            ]);
            return $notify;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function sendNotificationService($id){
        $message = Notification::where('id', $id)->first();
        broadcast(new EventsNotification($message))->toOthers();
    }

}