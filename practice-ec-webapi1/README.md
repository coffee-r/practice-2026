## このリポジトリについて

ショッピングサイトのAPIをLaravel×DDDで試しに作ってみるという練習リポジトリです

### プロファイラ
https://readouble.com/laravel/9.x/ja/telescope.html
テレスコープでパフォーマンスを見ながら開発を進める

### レスポンスデータの加工
https://readouble.com/laravel/8.x/ja/eloquent-resources.html
APIリソースを使う
JsonResource::withoutWrapping();は記述しない

### 例外設計
* ハンドリングはapp/Exceptions/Handler.phpで集中管理する
* ドメインレイヤとユースケースレイヤでは独自例外を投げる
    * コンテキストごとに作った方が良いかよく分からない
    * 一旦app/Packages/Shared配下に独自例外クラスを作った
    
### テストコード
* ドメインレイヤのユニットテスト
* ユースケースレイヤの機能テスト
* コントローラーの機能テスト(thephpleague/openapi-psr7-validatorを使う)

### DBの採番処理が絡むところ
Factoryパターンで実装

### トランザクション
Usecase層でDB::transaction()を使って妥協する

### ページネーション&ソート
商品一覧のところを試しに実装
* Shared/UsecaseにページネーションのDTO(値オブジェクトっぽいけど)を用意
* 単一集約でも実装はQueryService
* apiのクエリパラメタは、perPage/page方式


### Usecase層での妥協
フレームワークに依存した処理はなるべく書かないようにしたいが、
実装上どうしてもフレームワークの機能を使いたくなる場面があるのでそのときは妥協する。

* トランザクション → DB::transaction()を使用
    * apicallなどが絡んだときに結果整合性を担保する必要が出てくる