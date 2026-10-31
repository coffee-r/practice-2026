// backend/main.go
package main

import (
	"fmt"
	"log"
	"net/http"
	"time"

	"github.com/coffee-r/practice-next-go1/middleware"
	"github.com/gin-contrib/cors"
	"github.com/gin-gonic/gin"
	"github.com/samber/lo"

	"github.com/joho/godotenv"
)

type Item struct {
	ID   string `json:"id"`
	Name string `json:"name"`
}

type ItemDeleteInput struct {
	ID string `uri:"itemId"`
}

func main() {
	// .env読み込み
	if err := godotenv.Load(); err != nil {
		log.Fatalf("Error loading the .env file: %v", err)
	}

	// todo データベース
	items := []Item{
		{"01JSR2N70EB1HGJYTHPC4643CP", "水筒"},
		{"01JSR2NCCV5C4KAA54K7420382", "エアマット"},
		{"01JSR2NGNWW3D6QBF745RR5F19", "チェーンスパイク"},
	}

	// gin
	r := gin.Default()

	// リクエストのオリジンを確認するミドルウェア
	r.Use(func(c *gin.Context) {
		origin := c.Request.Header.Get("Origin")
		fmt.Printf("受信したリクエストのオリジン: %s\n", origin)
		c.Next()
	})

	// CORS
	r.Use(cors.New(cors.Config{
		AllowOrigins:     []string{"*"}, // Next.jsのURL
		AllowMethods:     []string{"GET", "POST", "DELETE"},
		AllowHeaders:     []string{"Origin", "Content-Type"},
		AllowCredentials: true,
		MaxAge:           12 * time.Hour,
	}))

	// Auth0 JWT 検証ミドルウェアを Gin で使う
	r.Use(middleware.EnsureValidTokenGin())
	{
		r.GET("/users/:userId/items", func(c *gin.Context) {
			c.JSON(200, gin.H{
				"items": items,
			})
		})

		r.POST("/users/:userId/items", func(c *gin.Context) {
			item := Item{}

			err := c.ShouldBindBodyWithJSON(&item)

			if err != nil {
				c.String(http.StatusBadRequest, "Bad request")
				return
			}

			items = append(items, item)

			c.JSON(201, gin.H{
				"item": item,
			})
		})

		r.DELETE("/users/:userId/items/:itemId", func(c *gin.Context) {
			input := ItemDeleteInput{}

			err := c.ShouldBindUri(&input)

			if err != nil {
				c.String(http.StatusBadRequest, "Bad request")
				return
			}

			items = lo.Reject(items, func(i Item, _ int) bool {
				return i.ID == input.ID
			})

			c.Status(http.StatusNoContent)
		})
	}

	r.Run(":8080") // ポート指定
}
