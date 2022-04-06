<?php

namespace App\Http\Controllers;

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
    public function suc($arr){
        $arr['status'] = 1;
        if(!isset($arr['message'])){
            $arr['message'] = 'Амжилттай';
        }
        return response()->json($arr);
    }

    public function resp($arr){
        $arr['status'] = 1;
        return response()->json($arr);
    }

    /**
     * 404 амжилтгүй
     */
    public function not($arr){
        $arr['status'] = 0;
        if(!isset($arr['message'])){
            $arr['message'] = 'Илэрцгүй';
        }
        return response()->json($arr, 404);
    }
}
