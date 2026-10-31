package app

import (
	"fmt"
	"net/http"

	"github.com/labstack/echo/v4"
)

func CustomHTTPErrorHandler(err error, c echo.Context) {
	// レスポンスが既に送信済みかチェック
	if c.Response().Committed {
		return
	}

	// AppError チェック
	if ae, ok := err.(*AppError); ok {
		c.Logger().Error(ae.Error())

		c.Logger().Error("Stack Trace:")
		for i, e := range ae.Errors {
			c.Logger().Errorf("[%d] %v", i, e)
		}

		c.JSON(ae.HttpStatusCode, map[string]string{
			"error": ae.Message,
		})
		return // ← 重要
	}

	// Echo のHTTPError
	if he, ok := err.(*echo.HTTPError); ok {
		c.Logger().Error(err)
		c.JSON(he.Code, map[string]string{
			"error": fmt.Sprintf("%v", he.Message),
		})
		return // ← 重要
	}

	// その他のエラー（デフォルト）
	c.Logger().Error(err)
	c.JSON(http.StatusInternalServerError, map[string]string{
		"error": "Internal Server Error",
	})
}
