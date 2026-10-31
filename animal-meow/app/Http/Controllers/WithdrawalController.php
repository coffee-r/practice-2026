<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CoffeeR\UseCases\WithdrawalAction;

class WithdrawalController extends Controller
{
    /**
     * 退会アクション
     *
     * @param Request $request
     * @return void
     */
    public function withdrawal(Request $request, WithdrawalAction $withdrawalAction)
    {
        // フォームバリデーション
        $validated = $request->validate([
            'confirmText' => 'required|regex:/^退会する$/',
        ],[
            'confirmText.required' => '退会確認用メッセージを入力してください',
            'confirmText.regex' => '退会確認用メッセージを正しく入力してください',
        ]);

        // 退会処理
        $withdrawalAction();

        // ログアウト
        Auth::logout();

        // 退会後画面にリダイレクト
        return redirect('/after_withdrawal')->with('successMessages', ['退会しました。']);
    }
}
