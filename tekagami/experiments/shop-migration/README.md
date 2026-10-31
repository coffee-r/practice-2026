# shop-migration

This scenario demonstrates a **CI3 to Laravel 12 migration** for an Oracle shop
application.

- `ci3-shop/`: legacy application, CodeIgniter 3 + Oracle.
- `laravel12-shop/`: target application, Laravel 12 + Oracle
  (Eloquent + yajra/laravel-oci8).
- `diff/`: migration comparison outputs (`diff.md`, `diff.json`).
- `spec-workflow/`: example application of `spec/prompts/` to this scenario.

Both applications implement the same 7 endpoints and the same custom event
labels. The `diff` command can then show meaning-near SQL matches using layer-B
SQL fingerprints.

## Run

```bash
cd experiments/shop-migration
docker compose up --build -d
docker compose exec laravel12-shop php artisan key:generate

# Observation and evidence generation
bash ci3-shop/scripts/run-e2e.sh
bash ci3-shop/scripts/report.sh
bash ci3-shop/scripts/analyze.sh
bash laravel12-shop/scripts/run-e2e.sh
bash laravel12-shop/scripts/analyze.sh
bash laravel12-shop/scripts/diff.sh
```

## Generated Outputs

| File | Description |
|---|---|
| `ci3-shop/var/tekagami.jsonl` | Raw CI3 observation log. |
| `ci3-shop/var/summary.md` | CI3 endpoint catalog. |
| `ci3-shop/var/report.md` | CI3 behavior pattern report. |
| `ci3-shop/var/export.json` | Compact CI3 evidence pack for AI/human review. |
| `laravel12-shop/var/tekagami.jsonl` | Raw Laravel observation log. |
| `laravel12-shop/var/export.json` | Compact Laravel evidence pack for AI/human review. |
| `diff/diff.md` | Migration diff, including meaning-near SQL matches. |
| `diff/diff.json` | Migration diff in JSON. |

Regenerating outputs changes trace ids and timestamps, so committed samples will
show diffs after reruns. The committed samples can be inspected as-is.

To create specification candidates from the generated outputs, see
`spec-workflow/` and `spec/prompts/`.
