<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TopController extends Controller
{
    //
    public function index (Request $request): Response
    {
        // 新着のゲームを取得
        $new_games = [
            [
                "game_id" => "gameid1",
                "game_name" => "gamename1",
                "user_name" => "username1",
                "user_view_name" => "userviewname1",
            ],
            [
                "game_id" => "gameid2",
                "game_name" => "gamename2",
                "user_name" => "username2",
                "user_view_name" => "userviewname2",
            ],
        ];

        return Inertia::render('Home', [
            'newGames' => $new_games
        ]);
    }
}
