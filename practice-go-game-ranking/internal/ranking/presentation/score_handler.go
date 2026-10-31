package presentation

import (
	"context"
	"database/sql"
	"errors"
	"net/http"
	"practice-go-game-ranking/internal/app"
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/labstack/echo/v4"
	"github.com/uptrace/bun"
)

type (
	ScoreHandler struct {
		db        *bun.DB
		validator *validator.Validate
	}

	Score struct {
		Name  string `json:"name" validate:"required,min=1,max=10"`
		Score int    `json:"score" validate:"required,min=0"`
	}

	HiScore struct {
		bun.BaseModel `bun:"table:hi_scores,alias:h"`
		Name          string    `json:"name" bun:"name,pk"`
		HiScore       int       `json:"score" bun:"hi_score"`
		RecordedAt    time.Time `json:"recorded_at" bun:"recorded_at"`
	}
)

func NewScoreHandler(d *bun.DB, v *validator.Validate) *ScoreHandler {
	return &ScoreHandler{
		db:        d,
		validator: v,
	}
}

func (h *ScoreHandler) StoreHiScore(c echo.Context) error {
	// リクエストパラメタ
	var request Score

	// リクエストパラメタを構造体にマッピング
	if err := c.Bind(&request); err != nil {
		return app.NewAppError(err, http.StatusUnprocessableEntity, "パラメタのマッピングに失敗しました。")
	}

	// パラメタのバリデーション
	if err := h.validator.Struct(request); err != nil {
		return app.NewAppError(err, http.StatusUnprocessableEntity, "バリデーションに失敗しました。")
	}

	// データベーストランザクションのフラグデータ
	var isNewRecord bool
	var isUpdated bool

	// トランザクション
	err := h.db.RunInTx(c.Request().Context(), &sql.TxOptions{}, func(ctx context.Context, tx bun.Tx) error {
		current := new(HiScore)

		err := tx.NewSelect().
			Model(current).
			Where("name = ?", request.Name).
			For("UPDATE").
			Scan(ctx)

		if err != nil {
			if errors.Is(err, sql.ErrNoRows) {
				// 新規登録
				isNewRecord = true
				hiScore := &HiScore{
					Name:       request.Name,
					HiScore:    request.Score,
					RecordedAt: time.Now(),
				}
				_, err := tx.NewInsert().Model(hiScore).Exec(ctx)
				return err
			}
			return err
		}

		// ハイスコア更新判定
		if request.Score > current.HiScore {
			isUpdated = true
			current.HiScore = request.Score
			current.RecordedAt = time.Now()

			_, err := tx.NewUpdate().
				Model(current).
				Where("name = ?", request.Name).
				Exec(ctx)
			return err
		}

		return nil // 更新不要
	})

	if err != nil {
		return app.NewAppError(err, http.StatusInternalServerError, "データベースエラーが発生しました。")
	}

	// ステータスコードとメッセージを使い分け
	if isNewRecord {
		return c.JSON(http.StatusCreated, map[string]string{
			"message": "ハイスコアを新規登録しました。",
		})
	}

	if isUpdated {
		return c.JSON(http.StatusOK, map[string]string{
			"message": "ハイスコアを更新しました。",
		})
	}

	return c.JSON(http.StatusOK, map[string]string{
		"message": "スコアを受け付けましたが、ハイスコアは更新されませんでした。",
	})
}
