<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CustomBasicAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // 本番の環境とローカルの開発環境はbasic認証させない
        $allowSiteUrls = [
            'localhost',
            '127.0.0.1',
            'animal-meow.com'
        ];

        if(in_array($request->getHost(), $allowSiteUrls) == true){
            return $next($request);   
        }

        $username = $request->getUser();
        $password = $request->getPassword();

        if ($username == 'testuser' && $password == 'flyio3014') {
            return $next($request);
        }

        abort(401, "Enter username and password.", [
            header('WWW-Authenticate: Basic realm="Sample Private Page"'),
            header('Content-Type: text/plain; charset=utf-8')
        ]);
    }
}
