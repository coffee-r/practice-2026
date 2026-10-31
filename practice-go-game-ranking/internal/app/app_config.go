package app

import (
	"os"

	"github.com/joho/godotenv"
)

type AppConfig struct {
	DatabaseDSN string
}

// 設定ファイルを読み込んで返す
func NewAppConfig() (*AppConfig, error) {
	err := godotenv.Load()

	if err != nil {
		return nil, err
	}

	return &AppConfig{
		DatabaseDSN: os.Getenv("DSN"),
	}, nil
}
