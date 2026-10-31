# R2 to AWS S3 Backup Service

Cloudflare R2バケットをAWS S3にバックアップするサービスです。
内部でrcloneを使用しています。
このプロジェクトはほぼ生成AIで作ってます。

## セットアップ

* R2バケット作成
* R2 ユーザー API トークン 作成
* S3バケット作成
* S3バケットにバックアップをどれくらい保持するかのライフサイクルルール設定
* 対象のS3バケットにアクセスが可能なIAMポリシー作成
* IAMユーザー作成、アクセスキーとシークレットアクセスキー発行

## ローカルでの使い方

```bash
# 環境変数ファイルをコピー
cp .env.example .env

# .envを編集
vi .env

# コンテナを立ち上げる
docker compose run --rm backup
```