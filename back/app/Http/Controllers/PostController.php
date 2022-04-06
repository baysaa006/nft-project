<?php

namespace App\Http\Controllers;

use App\Models\CategoryPosts;
use App\Models\Post;
use App\Models\PostRate;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function makeNft(Request $request){
        $request->validate([
            'postId' => 'required',
            'tokenId' => 'required',
        ], [
            'postId.required' => 'Контентийг ID-г оруулна уу',
            'tokenId.required'=>'Token ID-г оруулнауу'
        ]);

        $postId = $request->input("postId");
        $tokenId = $request->input("tokenId");

        $post = Post::find($postId);

        if(!$post){
            return $this->not(['message'=>'Контент олдсонгүй']);
        }

        $post->nft_id = $tokenId;
        $post->is_nft = ParameterController::$POST_NFT;
        $post->update();

        return $this->suc([]);
    }

    public function ratePost(Request $request)
    {
        $postId = $request->input("postId");

        $post = Post::find($postId);

        if (!$post) {
            return $this->not(['Контент олсдонгүй']);
        }

        $userId = Auth::user()->id;

        $rate = PostRate::where('post_id', $postId)->where('user_id', $userId)->first();

        if ($rate) {
            return $this->duplicated(['message' => 'Үнэлгээ өгсөн байна']);
        }

        $rate = new PostRate();
        $rate->post_id = $postId;
        $rate->user_id = $userId;
        $rate->created_at = new DateTime();
        $rate->save();

        $post->like_count += 1;
        $post->update();

        return $this->suc([]);
    }

    public function timelineList(Request $request)
    {
        $list = Post::orderBy('updated_at', 'desc')->paginate(ParameterController::$ALL_POST_PAGE);
        return $this->resp(['list' => $list]);
    }

    public function ownPostList(Request $request)
    {
        $userid = Auth::user()->id;

        $list = Post::where('user_id', $userid)->orderBy('updated_at', 'desc')->paginate(ParameterController::$USER_OWN_POST_PAGE);

        return $this->resp(['list' => $list]);
    }

    public function updatePost(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'Гарчиг хоосон байж болохгүй'
        ]);

        $postId = $request->input('id');
        $title = $request->input('title');
        $text = $request->input('content_text');

        $post = Post::find($postId);
        $post->title = $title;
        $post->content_text = $text;

        if (!$post) {
            return $this->not(['message' => 'Контент олдсонгүй']);
        }

        if ($request->hasFile('content_image')) {
            $path = date('Y/m') . "/post";
            $image = $request->file('content_image')->store($path);
            $post->content_image = $image;
        }

        $post->update();

        if ($request->has('category_id') && $request->input("category_id")) {
            $categoryId = $request->input("category_id");
            CategoryPosts::where('post_id', $postId)->delete();

            $category = new CategoryPosts();
            $category->category_id = $categoryId;
            $category->post_id = $postId;
            $category->user_id = $post->user_id;
            $category->save();
        }

        return $this->suc([]);
    }

    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ], [
            'title.required' => 'Гарчиг хоосон байж болохгүй'
        ]);

        $title = $request->input('title');
        $text = $request->input('content_text');
        $image = "";
        $userId =  Auth::user()->id;

        if ($request->hasFile('content_image')) {
            $path = date('Y/m') . "/post";
            $image = $request->file('content_image')->store($path);
        }

        $created = Post::create([
            'title' => $title,
            'content_text' => $text,
            'content_image' => $image,
            'user_id' => $userId,
            'like_count' => 0,
            'is_nft'=>ParameterController::$POST_NOT_NFT,
            'status' => ParameterController::$POST_ACTIVE
        ]);

        if ($request->has('category_id') && $request->input("category_id")) {
            $categoryId = $request->input("category_id");
            $category = new CategoryPosts();
            $category->category_id = $categoryId;
            $category->post_id = $created->id;
            $category->user_id = $userId;
            $category->save();
        }

        return $this->suc([]);
    }
}
