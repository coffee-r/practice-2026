# tekagami endpoint summary

- 生成日時: `2026-06-01T22:07:39+00:00`
- 入力ファイル数: `1`
- 入力トレース数: `69`
- 対象トレース数: `69`
- 観測endpoint数: `7`
- 観測期間: `2026-06-01T22:06:53+00:00` ～ `2026-06-01T22:07:26+00:00`

> group_hint は URL prefix 由来の機械的なまとまりであり、業務名ではありません。

## Filter

- entrypoint: `-`
- path: `-`
- method: `-`

## Observed Endpoint Catalog

| group_hint | endpoint | count | statuses | patterns | rare | custom_events | writes | errors | truncated |
|---|---|---:|---|---:|---:|---:|---|---:|---:|
| /api/cart | POST /api/cart/items | 36 | 201:32, 404:1, 422:3 | 11 | 8 | 6 | yes | 0 | 0 |
| /api/checkout | POST /api/checkout/quote | 14 | 200:5, 422:9 | 9 | 7 | 5 | no | 0 | 0 |
| /api/orders | POST /api/orders | 11 | 201:5, 422:6 | 9 | 7 | 10 | yes | 0 | 0 |
| /api/cart | GET /api/cart | 5 | 200:5 | 2 | 1 | 1 | no | 0 | 0 |
| /api/test | POST /api/test/reset | 1 | 200:1 | 1 | 1 | 1 | yes | 0 | 0 |
| /api/orders | POST /api/orders/{id}/cancel | 1 | 200:1 | 1 | 1 | 1 | yes | 0 | 0 |
| /api/payments | POST /api/payments/credit/callback | 1 | 200:1 | 1 | 1 | 1 | yes | 0 | 0 |
