# tekagami-data

`tools/tekagami-data` is the language-neutral data processing layer for `tekagami-v1` observation logs.

Requires only PHP 8.0+ — no `composer install` needed.

```bash
php tools/tekagami-data/bin/tekagami summary <jsonl...> [--format md|json]
php tools/tekagami-data/bin/tekagami report  <jsonl...> [--format md|json] [--value-mode normalized|tokenized]
php tools/tekagami-data/bin/tekagami export  <jsonl...> [--format json|md] [--value-mode normalized|tokenized]
php tools/tekagami-data/bin/tekagami diff    <legacy-export.json> <target-export.json> [--format json|md]
```

This layer stays independent from any single observer implementation. PHP, C#, or another observer can all feed this layer as long as they emit records conforming to `contracts/schema/tekagami-v1.schema.json`.

## Responsibilities

- `summary`: build an endpoint catalog for choosing deep-dive targets
- `report`: group observed entrypoints and execution patterns for human review
- `export`: compact evidence for AI or human analysis, with SQL dictionary compression
- `diff`: compare legacy and target exports deterministically

The commands do not infer business specifications. They preserve observed facts and deterministic differences so `spec/` workflows or people can interpret them.

## Running tests

```bash
cd tools/tekagami-data
composer install
./vendor/bin/phpunit
```
