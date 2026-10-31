package presentation

import (
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
	RankingHandler struct {
		db        *bun.DB
		validator *validator.Validate
	}

	Ranking struct {
		bun.BaseModel `bun:"table:hi_scores,alias:h"`
		Rank          int       `json:"rank" bun:"rank"`
		Name          string    `json:"name" bun:"name"`
		HiScore       int       `json:"hi_score" bun:"hi_score"`
		RecordedAt    time.Time `json:"recorded_at" bun:"recorded_at"`
	}

	RankingQuery struct {
		Offset int `query:"offset" validate:"min=0"`
		Limit  int `query:"limit" validate:"required,min=1,max=100"`
	}
)

func NewRankingHandler(d *bun.DB, v *validator.Validate) *RankingHandler {
	return &RankingHandler{
		db:        d,
		validator: v,
	}
}

func (h *RankingHandler) GetRankings(c echo.Context) error {
	// クエリパラメタ
	var q RankingQuery

	// クエリパラメタを構造体にマッピング
	if err := c.Bind(&q); err != nil {
		return app.NewAppError(err, http.StatusUnprocessableEntity, "パラメタのマッピングに失敗しました。")
	}

	// パラメタのバリデーション
	if err := h.validator.Struct(q); err != nil {
		return app.NewAppError(err, http.StatusUnprocessableEntity, "バリデーションに失敗しました。")
	}

	// ランキングデータ
	var rankings []Ranking

	// データストアからランキングデータを取得
	subq := h.db.NewSelect().
		Model((*Ranking)(nil)).
		ColumnExpr("*, RANK() OVER (ORDER BY hi_score DESC, recorded_at ASC) as rank")

	err := h.db.NewSelect().
		TableExpr("(?) as ranked", subq).
		Limit(q.Limit).
		Offset(q.Offset).
		Scan(c.Request().Context(), &rankings)

	if err != nil {
		return app.NewAppError(err, http.StatusInternalServerError, "データベースエラーが発生しました。")
	}

	if len(rankings) == 0 {
		return app.NewAppError(nil, http.StatusNotFound, "指定した条件ではランキングデータが見つかりませんでした。")
	}

	// JSONレスポンス
	return c.JSON(http.StatusOK, rankings)
}

func (h *RankingHandler) GetRankingByPlayerName(c echo.Context) error {
	// パスパラメタ
	name := c.Param("name")

	// ランキングデータ
	var ranking = new(Ranking)

	// データストアからランキングデータを取得
	subq := h.db.NewSelect().
		Model((*Ranking)(nil)).
		ColumnExpr("*, RANK() OVER (ORDER BY hi_score DESC, recorded_at ASC) as rank")

	err := h.db.NewSelect().
		TableExpr("(?) as ranked", subq).
		Where("name = ?", name).
		Scan(c.Request().Context(), ranking)

	if errors.Is(err, sql.ErrNoRows) {
		return app.NewAppError(err, http.StatusNotFound, "指定したプレイヤー名のランキングデータが見つかりませんでした。")
	}

	if err != nil {
		return app.NewAppError(err, http.StatusInternalServerError, "データベースエラーが発生しました。")
	}

	return c.JSON(http.StatusOK, ranking)
}
