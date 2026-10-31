<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Operator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Login', []);
    }

    public function login(LoginRequest $request)
    {
        // オペレーターを検索
        $operator = Operator::where('id', $request->id)->first();

        // 実務で触っている既存テーブルのパスワードが平文なので仕方なく平文でログイン
        if ($operator->password === $request->password) {
            Auth::login($operator);
            return redirect(route('dashboard'));
        }

        // 認証失敗
        return back()->withErrors([
            'authError' => '認証情報が一致しません。'
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect(route('login.show'));
    }
}
