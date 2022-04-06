<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function ownPostList(Request $request){
        $userid = Auth::user()->id;

        $list = Post::where('user_id', $userid)->paginate( ParameterController::$USER_OWN_POST_PAGE );
        
        return $this->resp(['list'=>$list]);
    }

    public function updatePost(Request $request){
        $request->validate([
            'title'=>'required'
        ],[
            'title.required'=>'Гарчиг хоосон байж болохгүй'
        ]);

        $postId = $request->input('id');
        $title = $request->input('title');
        $text = $request->input('content_text');
        $image = $request->input('content_image');
        $userid =  Auth::user()->id;

        $post = Post::find($postId );

        if(!$post){
            return $this->not(['message'=>'Контент олдсонгүй']);
        }

        Post::create([
            'title'=>$title,
            'content_text'=>$text,
            'content_image'=>$image,
            'user_id'=>$userid,
            'status'=>ParameterController::$POST_ACTIVE
        ]);


        return $this->suc([]);
    }

    public function storePost(Request $request) {
        $request->validate([
            'title'=>'required'
        ],[
            'title.required'=>'Гарчиг хоосон байж болохгүй'
        ]);

        $title = $request->input('title');
        $text = $request->input('content_text');
        $image = $request->input('content_image');
        $userid =  Auth::user()->id;

        Post::create([
            'title'=>$title,
            'content_text'=>$text,
            'content_image'=>$image,
            'user_id'=>$userid,
            'status'=>ParameterController::$POST_ACTIVE
        ]);


        return $this->suc([]);
    }
}
