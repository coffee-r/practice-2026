# ベースイメージの指定
FROM node:23.11.0-slim

# 作業ディレクトリを設定
WORKDIR /app

# ホスト側のファイルをコピー
COPY . .

# 開発時に必要なパッケージをインストール
RUN npm install

# ポート3000を開放
EXPOSE 3000

# 開発サーバー起動コマンド
CMD ["npm", "run", "dev"]