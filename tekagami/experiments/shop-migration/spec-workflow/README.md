# shop-migration spec workflow

This directory shows an example application of `spec/prompts/` using observation
evidence from the `shop-migration` scenario.

Large JSONL and export files do not need to be duplicated here. This example
usually references files under `ci3-shop/var/` directly.

## Representative Inputs

- `../ci3-shop/var/tekagami.jsonl`
- `../ci3-shop/var/summary.json`
- `../ci3-shop/var/summary.md`
- `../ci3-shop/var/export.json`
- `../ci3-shop/app/application/config/routes.php`
- `../ci3-shop/app/application/controllers/api/Shop.php`
- `../ci3-shop/app/application/models/Shop_model.php`
- `../ci3-shop/docker/oracle/init/01-schema.sql`
- `../diff/diff.json`: CI3 vs Laravel migration diff.

## Prompts

- `SUMMARY_PROMPT.md`: choose deep-dive priorities from the summary endpoint
  catalog.
- `ENTRYPOINT_PROMPT.md`: analyze one endpoint or endpoint group with filtered
  export evidence.
- `SYNTHESIS_PROMPT.md`: combine endpoint-level analysis outputs.
- `DIFF_PROMPT.md`: analyze legacy vs target migration differences.
- `PROMPT.md`: older small-example prompt that reads the scenario in one pass.

## Deep-Dive Example

```bash
php ../../../../tools/tekagami-data/bin/tekagami summary ../ci3-shop/var/tekagami.jsonl \
  --format json > inputs/summary.json

php ../../../../tools/tekagami-data/bin/tekagami export ../ci3-shop/var/tekagami.jsonl \
  --path "/api/cart/*" > inputs/cart.export.json
```

Then use `spec/prompts/01_summary_triage.md` and continue through the workflow.
