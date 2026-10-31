<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Aws\S3\S3Client;

class GameUploadApiController extends Controller
{
    public function object(string $gameId)
    {
        // セッションのユーザーIDとゲームIDが紐づくかを事前にチェックする必要がある
        // 他人のゲームを上書きさせないため

        $s3client = S3Client::factory([
        ]);

        $command = $s3client->getCommand('PutObject', [
            'Bucket' => "coffee-r-test",
            'Key' => 'games/'.$gameId.'/../games/2/index.html'
        ]);

        $request = $s3client->createPresignedRequest($command, '+1 hour');

        return response()->json(
            [
                'url' => $request->getUri()
            ]
        );
    }   

    public function cacheclear()
    {

    }
}
