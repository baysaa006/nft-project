<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Амжилттай
     */
    public function notEqual($arr=[]){
        if(!isset($arr['message'])){
            $arr['message'] = 'Ижил утга биш байна.';
        }
        return response()->json($arr, 406);
    }

    /**
     * Амжилттай
     */
    public function suc($arr=[]){
        if(!isset($arr['message'])){
            $arr['message'] = 'Амжилттай';
        }
        return response()->json($arr);
    }

    public function resp($arr=[]){
        return response()->json($arr);
    }

    /**
     * 404 амжилтгүй
     */
    public function not($arr=[]){
        if(!isset($arr['message'])){
            $arr['message'] = 'Мэдээлэл үүсээгүй байна';
        }
        return response()->json($arr, 404);
    }

    /**
     * 409 Мэдээлэл давхардаж байна
     */
    public function duplicated($arr){
        if(!isset($arr['message'])){
            $arr['message'] = 'Мэдээлэл давхардаж байна';
        }
        return response()->json($arr, 409);
    }

    public function sendNotification($userId, $title, $content) {
        $notification = new Notification();
        $notification->user_id = $userId;
        $notification->title = $title;
        $notification->content = $content;
        $notification->unread = ParameterController::$NOTIFICATION_UNREAD;
        $notification->created_at = new DateTime();
        $notification->save();
    }

    public function getStoragePath($path){
        return 'public/'.date('Y/m') . "/".$path;
    }
}
