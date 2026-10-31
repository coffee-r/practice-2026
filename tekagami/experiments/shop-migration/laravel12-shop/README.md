# Laravel 12 Shop Migration Target

This is a local E2E sample using Laravel 12, Oracle, and Eloquent. It implements
the same business scenarios as `ci3-shop/` so tekagami `export` and `diff` can
be used for migration comparison.

## Components

- `laravel12-shop`: PHP 8.3, Laravel 12, Yajra OCI8, and tekagami.
- Oracle: shared instance from `experiments/shop-migration/docker-compose.yml`.
- Log output: `experiments/shop-migration/laravel12-shop/var/tekagami.jsonl`.

## Run

Use the combined compose setup:

```bash
cd experiments/shop-migration
docker compose up --build -d
docker compose exec laravel12-shop php artisan key:generate
bash laravel12-shop/scripts/run-e2e.sh
bash laravel12-shop/scripts/analyze.sh
bash laravel12-shop/scripts/diff.sh
```

Generated outputs:

- `var/tekagami.jsonl`
- `var/flow-map.tsv`
- `var/export.json`
- `var/analysis.md`
- `var/diff.md`
- `var/diff.json`

The diff compares `ci3-shop/var/export.json` with the Laravel
`var/export.json`. Even when CI3 raw SQL and Eloquent-generated SQL differ,
layer-B fingerprint matches appear in `meaning_near_matches`.

## Notes

This app is not a Laravel starter template. It is a tekagami migration research
demo. Representative outputs are committed as learning material; reruns change
trace ids, timestamps, and flow ids.
