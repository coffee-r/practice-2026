# PracticeNextGo1

Next.jsとGoで作るWebアプリケーション練習1

# backend初期化

```
docker run --rm -v $(pwd)/backend:/app -w /app golang:1.24.2-alpine sh -c "go mod init github.com/coffee-r/practice-next-go1 && go mod tidy"
```

# frontend初期化

```
docker run --rm -it -v $(pwd)/frontend:/app -w /app node:23.11.0-slim npx create-next-app@latest . --ts --no-install
```

# モジュールを順次追加

```
# gin導入
docker compose exec backend sh -c "cd /app && go get github.com/gin-gonic/gin@v1.10.0 && go mod tidy"

# gin使う時のCORS
docker compose exec backend sh -c "cd /app && go get github.com/gin-contrib/cors && go mod tidy"

# bun導入
docker compose exec backend sh -c "cd /app && go get github.com/uptrace/bun@v1.2.11 && go mod tidy"

# godotenv導入
docker compose exec backend sh -c "cd /app && go get github.com/joho/godotenv && go mod tidy"

# samber/lo導入
docker compose exec backend sh -c "cd /app && go get github.com/samber/lo@v1 && go mod tidy"

# oklog/ulid
docker compose exec backend sh -c "cd /app && go get github.com/oklog/ulid/v2 && go mod tidy"

# auth0/go-jwt-middleware
docker compose exec backend sh -c "cd /app && go get github.com/auth0/go-jwt-middleware/v2"
docker compose exec backend sh -c "cd /app && go get github.com/auth0/go-jwt-middleware/v2/validator"
docker compose exec backend sh -c "cd /app && go get github.com/auth0/go-jwt-middleware/v2/jwks"

# spf13/viper
# auth0/go-jwt-middleware
# go-playground/validator
# stretchr/testify

# shadcn導入
docker compose exec frontend sh -c "npx shadcn@latest init"

# shadcnのコンポーネント
docker compose exec frontend sh -c "npx shadcn@latest add button"
docker compose exec frontend sh -c "npx shadcn@latest add card"
docker compose exec frontend sh -c "npx shadcn@latest add sidebar"
docker compose exec frontend sh -c "npx shadcn@latest add table"
docker compose exec frontend sh -c "npx shadcn@latest add form"

# zod導入 https://zod.dev/
docker compose exec frontend sh -c "npm install react-hook-form"
docker compose exec frontend sh -c "npm install zod"

# nextjs-auth0
docker compose exec frontend sh -c "npm i @auth0/nextjs-auth0"
```

# Golang側のCORSの設定

* 開発環境では全部許可
* .envで切り分けられればいいかな

# Auth0

* https://manage.auth0.com/

# frontend側でAuth0の認証情報取り回し

* https://github.com/auth0/nextjs-auth0 活用
* lib/auth0.tsで`scope`と`audience`を引数に入れないとJWTではなくJWEが返ってくるっぽい

# Redisでセッション中央管理

* Webサーバ冗長化を考慮
* Node.js側に保存しない

# backend側でAuth0を使用した認証情報改ざんチェック&認可

* https://auth0.com/docs/quickstart/backend/golang

# Cloudflare R2

# Railway.appにデプロイ

# frontendとbackendの繋ぎ
