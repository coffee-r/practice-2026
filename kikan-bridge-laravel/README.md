## kikan-bridge-laravel

環境構築練習用リポジトリです。
SQLServerとDocker使う。

### プロジェクト作成

```sh
$ mkdir kikan-bridge-laravel
$ cd kikan-bridge-laravel
$ composer create-project --prefer-dist laravel/laravel src
```

### git

```sh
$ cd kikan-bridge-laravel
$ git init
$ git add .
$ git commit -m "first commit"
$ git branch -M main
$ git remote add origin git@github.com:coffee-r/kikan-bridge-laravel.git
$ git push -u origin main
```

### Dockerfile、docker-compose.yml

```sh
$ cd kikan-bridge-laravel
$ vim docker-compose.local.yml ### 記述内容は省略
$ vim Dockerfile ### 記述内容は省略
$ vim nginx.conf ### 記述内容は省略
$ vim opcache.ini ### 記述内容は省略
$ vim xdebug.ini ### 記述内容は省略
```

### devcontainer.json

開発コンテナー構成ファイルを追加

### vscodeの拡張機能 (書きかけ)

* Japanese Language Pack for VS Code
* PHP Debug
* Laravel Extra Intellisense

### Laravelタイムゾーン設定

src/config/app.php
src/.env

APP_TIMEZONE → Asia/Tokyo

### Laravelロケール設定

src/config/app.php
src/.env

locale → ja
faker_locale → ja_JP

### Laravelログ出力 (書きかけ)

### Laravelデータベースの文字コード設定 (書きかけ)

### Laravelデータベース接続設定

src/.env

```
DB_CONNECTION=sqlsrv
DB_HOST=db
DB_PORT=1433
DB_DATABASE=master
DB_USERNAME=sa
DB_PASSWORD=r00tP@ss3014
DB_ENCRYPT=no
DB_TRUST_SERVER_CERTIFICATE=true
```

### Laravelでroute/web.phpセッションを永続化しない

src/.env
SESSION_DRIVER=array