## このリポジトリについて

ECモール管理者向けCMSの練習用プロジェクトです。

## プロジェクト作成

```sh
$ composer create-project laravel/laravel PracticeEcMallOffice
$ cd PracticeEcMallOffice/
```

## Breezeを使ってフロントエンドをセットアップ

```sh
$ composer require laravel/breeze --dev
$ php artisan breeze:install react --typescript
```

## Sailの導入

```sh
$ composer require laravel/sail --dev
$ php artisan sail:install
### mysqlを選択

$ ./vendor/bin/sail up -d
$ ./vendor/bin/sail npm install
$ ./vendor/bin/sail npm run dev
```

## shadcn/uiの導入

https://ui.shadcn.com/docs/installation/laravel

```sh
$ npx shadcn-ui@latest init
### 基本デフォルト値を使う
### 以下だけ入力
### Where is your global CSS file? … resources/css/app.css
```

## セッション管理をファイルベースに変更

* config/session.php
* .env SESSION_DRIVER=file

## 日本の設定

* config/app.php タイムゾーン、ロケールを変更
* .env タイムゾーン、ロケールを変更

## 業務フローを考える

1. 出品者は出品者向けCMSで出品者登録を行う
2. モール管理者はECモール管理者向けCMSで出品者を閲覧し審査を行う
3. モール管理者の審査の結果がNGであれば、承認ステータスを却下に変更
4. 出品者は出品者向けCMSで出品者登録を編集、再度申請する
5. モール管理者の再審査の結果がOKであれば、承認ステータスを承認済みに変更
6. 承認後、出品者は出品者向けCMSで様々な操作を行えるようになる

この練習用リポジトリでは2,3,5を実装する。

## 必要な画面洗い出し

* ログインフォーム
* ダッシュボードTOP
* 出品者一覧
* 出品者詳細 (フォーム表示、承認ステータスのみ編集して更新可能)

## テーブル設計

* Operator (管理者ユーザー)
  * id
  * name 管理者名
  * password
* Seller (出品者)
  * id
  * name 出品者名
  * email
  * tel
  * website_url
  * approval_status 承認ステータス 'pending', 'approved', 'rejected'

実務はもっと複雑だけれども今回練習用なので簡易的にした。

## モデル・マイグレーションファイル作成

```sh
$ php artisan make:Model Operator --migration
$ php artisan make:Model Seller --migration
### マイグレーションファイル編集後、
$ ./vendor/bin/sail artisan migrate
```

## モデルファクトリの作成

```sh
$ ./vendor/bin/sail artisan make:factory OperatorFactory
$ ./vendor/bin/sail artisan make:factory SellerFactory
```

## シーダーの作成と実行

```sh
$ ./vendor/bin/sail artisan make:seeder OperatorSeeder
$ ./vendor/bin/sail artisan make:seeder SellerSeeder
### 編集後
$ ./vendor/bin/sail artisan db:seed --class=OperatorSeeder
$ ./vendor/bin/sail artisan db:seed --class=SellerSeeder
```

## コントローラーの作成

```sh
$ ./vendor/bin/sail artisan make:controller DashboardController
$ ./vendor/bin/sail artisan make:controller SellerController
```

## v0を使ってUIのモックを作成

https://v0.dev/

## / → /dashboard のリダイレクト設定

routes/web.php に記述

## 認証されていないユーザーのリダイレクト

参考 : https://qiita.com/horatjp/items/aa1dbb9641634ecc8c7d

bootstrap/app.phpで

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->redirectGuestsTo('/login');
})
```

## usersでないテーブル(このプロジェクトではoperators)で認証できる設定を入れる

参考 : https://qiita.com/n_oshiumi/items/a466ff2c8f20c3494538

usersをoperatorsに差し替えていく。

## 実装

* ログインフォームの実装
* 出品者一覧の実装
* 出品者詳細の実装
* 出品者の承認ステータス更新処理の実装

## 成功時のフラッシュメッセージを表示できるようにする

https://inertiajs.com/shared-data
参考にする

## VSCodeで 「import { Label } from "@/Components/ui/label"」 でエラー表示する事象を解消させる

resources/js/Components/ui/form.tsx
に変更を加える。

```
import { Label } from "@/components/ui/label"
↓
import { Label } from "@/Components/ui/label"
```

## PHPUnitでテストコードを書く

## 各環境のDockerファイルを作る

## Oracleと接続させる

## オンプレミスの仮想マシンで環境構築する

## オンプレミスの仮想マシンにデプロイする
