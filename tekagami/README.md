# tekagami

`tekagami` creates runtime behavior evidence for specification discovery and migration review.

It observes what a running web application actually did and records that evidence as `tekagami-v1` JSONL. Deterministic data tools then turn the logs into endpoint summaries, behavior reports, compact AI/human review packs, and legacy-vs-target diffs.

tekagami is not an APM, and it does not infer business specifications. It records observed facts so people and AI workflows can reason from evidence.

## What You Get

- `tekagami.jsonl`: one request per line, with observed HTTP shapes, SQL timeline, custom events, effects, and capture errors.
- `summary.md/json`: an observed endpoint catalog for choosing investigation targets.
- `report.md/json`: grouped behavior patterns per endpoint, including status codes and SQL flows.
- `export.json/md`: a compact evidence pack for AI or human review, with SQL dictionary compression.
- `diff.json/md`: a deterministic legacy-vs-target comparison, including changed patterns and meaning-near SQL matches.
- Specification candidates: produced by the `spec/` workflow from observed evidence plus code, DDL, fixtures, config, and other context.

## Repository Map

```text
observer-php/              PHP observer / Composer package coffee-r/tekagami-php
contracts/schema/          Language-neutral tekagami-v1 JSONL contract
tools/tekagami-data/       Data processing commands: summary, report, export, diff
spec/                      Prompts and workflow for specification candidates
experiments/               CI3/Laravel experiments and generated evidence samples
```

## Try The Sample Outputs

Representative generated outputs for the shop-migration scenario:

- [CI3 summary](experiments/shop-migration/ci3-shop/var/summary.md)
- [CI3 behavior report](experiments/shop-migration/ci3-shop/var/report.md)
- [CI3 compact export](experiments/shop-migration/ci3-shop/var/export.json)
- [Laravel target export](experiments/shop-migration/laravel12-shop/var/export.json)
- [Legacy vs target diff](experiments/shop-migration/diff/diff.md)

## Quick Commands

Run from the repository root with the data tools CLI.

```bash
php tools/tekagami-data/bin/tekagami summary experiments/shop-migration/ci3-shop/var/tekagami.jsonl
php tools/tekagami-data/bin/tekagami report experiments/shop-migration/ci3-shop/var/tekagami.jsonl
php tools/tekagami-data/bin/tekagami export experiments/shop-migration/ci3-shop/var/tekagami.jsonl
php tools/tekagami-data/bin/tekagami diff \
  experiments/shop-migration/ci3-shop/var/export.json \
  experiments/shop-migration/laravel12-shop/var/export.json \
  --format md
```

## Authoritative Docs

- PHP observer: [observer-php/README.md](observer-php/README.md)
- JSONL contract: [contracts/schema/README.md](contracts/schema/README.md) and [contracts/schema/tekagami-v1.schema.json](contracts/schema/tekagami-v1.schema.json)
- Data tools: [tools/tekagami-data/README.md](tools/tekagami-data/README.md)
- Spec workflow: [spec/README.md](spec/README.md) (Japanese operational guide)
- Experiments: [experiments/README.md](experiments/README.md)

## License

MIT License - Copyright 2026 coffee-r
