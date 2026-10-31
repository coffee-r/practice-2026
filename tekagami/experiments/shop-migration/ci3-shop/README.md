# CI3 Shop E2E Example

This is a local E2E sample using CodeIgniter 3, PHP 7.3, and Oracle Database
Free. It exercises shop/order branches through real HTTP APIs and generates
tekagami JSONL plus reports.

## Components

- `web`: PHP 7.3 Apache, CodeIgniter 3, OCI8, and tekagami.
- `oracle`: `gvenzl/oracle-free:23-slim`.
- Log output: `experiments/shop-migration/ci3-shop/var/tekagami.jsonl`.

The Oracle image is not Oracle 12c. The scenario uses `23-slim` as a pragmatic
local option that runs well on arm64 Macs, while keeping SQL conservative and
Oracle-like.

## Run

```bash
cd experiments/shop-migration
docker compose up -d --build
./ci3-shop/scripts/wait-for-app.sh
./ci3-shop/scripts/run-e2e.sh
./ci3-shop/scripts/report.sh
./ci3-shop/scripts/analyze.sh
```

Generated outputs:

- `var/tekagami.jsonl`
- `var/flow-map.tsv`
- `var/report.md`
- `var/report.json`
- `var/export.json`
- `var/analysis.md`

Representative sample outputs are committed under `var/`. You can inspect the
JSONL, report, and export without running the example. Reruns change `trace_id`,
timestamps, and flow ids, so diffs are expected.

`var/flow-map.tsv` is a human verification aid and is usually not passed to AI
analysis.

## API

- `POST /api/cart/items`
- `GET /api/cart`
- `POST /api/checkout/quote`
- `POST /api/orders`
- `POST /api/orders/{id}/cancel`
- `POST /api/payments/credit/callback`

`scripts/run-e2e.sh` assigns an 8-digit random `flow_id` to each scenario. The
JSONL records only that id; the human-readable mapping is written to
`var/flow-map.tsv`.

## Notes

This sample creates observation data for specification and migration research.
It is not intended to be a complete ecommerce implementation. It is designed to
show branches, SQL flows, side effects, N+1 behavior, and custom event
observations.
