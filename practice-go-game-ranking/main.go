package main

import (
	"context"
	"database/sql"
	"log"
	"log/slog"
	"os"

	"practice-go-game-ranking/internal/app"
	"practice-go-game-ranking/internal/ranking/presentation"

	"github.com/go-playground/validator/v10"
	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"
	"github.com/uptrace/bun"
	"github.com/uptrace/bun/dialect/pgdialect"
	"github.com/uptrace/bun/driver/pgdriver"
)

func main() {
	// 設定
	config, err := app.NewAppConfig()
	if err != nil {
		log.Fatal("Error loading app config.")
	}

	// データベース接続
	sqldb := sql.OpenDB(pgdriver.NewConnector(pgdriver.WithDSN(config.DatabaseDSN)))
	defer sqldb.Close()
	db := bun.NewDB(sqldb, pgdialect.New())

	// バリデーター
	validate := validator.New()

	// Echo
	e := echo.New()

	// Logger
	logger := slog.New(slog.NewJSONHandler(os.Stdout, nil))
	e.Use(middleware.RequestLoggerWithConfig(middleware.RequestLoggerConfig{
		LogStatus:   true,
		LogURI:      true,
		LogError:    true,
		HandleError: true, // forwards error to the global error handler, so it can decide appropriate status code
		LogValuesFunc: func(c echo.Context, v middleware.RequestLoggerValues) error {
			if v.Error == nil {
				logger.LogAttrs(context.Background(), slog.LevelInfo, "REQUEST",
					slog.String("uri", v.URI),
					slog.Int("status", v.Status),
				)
			} else {
				logger.LogAttrs(context.Background(), slog.LevelError, "REQUEST_ERROR",
					slog.String("uri", v.URI),
					slog.Int("status", v.Status),
					slog.String("err", v.Error.Error()),
				)
			}
			return nil
		},
	}))

	// エラーハンドラー
	e.HTTPErrorHandler = app.CustomHTTPErrorHandler

	// ハンドラー
	scoreHandler := presentation.NewScoreHandler(db, validate)
	rankingHandler := presentation.NewRankingHandler(db, validate)

	// ルーティング
	e.POST("/scores", scoreHandler.StoreHiScore)
	e.GET("/rankings", rankingHandler.GetRankings)
	e.GET("/players/:name/ranking", rankingHandler.GetRankingByPlayerName)

	// サーバ起動
	e.Logger.Fatal(e.Start(":8080"))
}
