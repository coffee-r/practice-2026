<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\CoffeeR\UseCases\LikeUpsertAction;
use App\Exceptions\PostNotFoundException;

class LikeApiController extends Controller
{
    /**
     * 投稿にいいねをする
     *
     * @param [type] $posts.id
     * @param Request $request
     * @return void
     */
    public function upsert(int $post_id, Request $request, LikeUpsertAction $likeUpsertAction)
    {
        // いいね処理
        try{
            $likeUpsertAction($post_id);
        }catch(PostNotFoundException $e){
            return response($e->getMessage(), 403);
        }

        response('', 200);        
    }
}
