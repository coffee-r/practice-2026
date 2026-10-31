# ベースイメージの指定
FROM golang:1.24.2-alpine

# 作業ディレクトリを設定
WORKDIR /app

# ホスト側のファイルをコピー
COPY . .

# 必要なパッケージをインストール
RUN apk add --no-cache git && \
    go install github.com/air-verse/air@latest

# ポート3000を開放
EXPOSE 8080

CMD ["air", "-c", ".air.toml"]
