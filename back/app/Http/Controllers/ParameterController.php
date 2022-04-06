<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use Illuminate\Http\Request;

class ParameterController extends Controller
{
    public static $POST_ACTIVE = 1;
    public static $POST_INACTIVE = 0;

    public static $USER_OWN_POST_PAGE = 10;


    public function listCategory() {
        $categories = Categories::where('status', 1)->get();
        return $this->resp(['list'=>$categories]);
    }
}
