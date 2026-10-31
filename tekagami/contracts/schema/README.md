# tekagami Schema v1

`tekagami-v1.schema.json` describes one JSONL line: one observed HTTP request.

The schema is a language-neutral contract. A PHP, C#, or any other observer can
feed the same data tools and spec workflow as long as it emits records that
conform to this schema.

## Top-Level Fields

| Field | Type | Description |
|---|---|---|
| `schema_version` | `1` (const) | Schema version. |
| `trace_id` | string | Identifier for one observed request. |
| `started_at` | string (date-time) | Request start time. |
| `flow` | object | Optional investigation correlation data. |
| `redaction` | object | Redaction/tokenization mode metadata. |
| `http` | object | Request and response envelope. |
| `timeline` | array | Data operation timeline. |
| `effects` | array | Write operation summary. |
| `errors` | array | Capture failures or observed application errors. |

## Value Classes

Values are represented in several ways:

| Suffix | Meaning | Kept in AI export |
|---|---|---|
| `*_shape` | Structure and scalar types only. No raw values. | Yes |
| `*_tokens` | HMAC tokens. Equal values produce equal tokens, but values cannot be recovered. | Yes |
| `*_values` (HTTP) / `observed_values` (SQL) | Raw values explicitly allowed by `keepKeys` or `sqlValueAllowlist`, intended only for non-sensitive business evidence. | Yes, intentionally |
| `statement_text` | Raw SQL text. Emitted only with `captureText=true`; plaintext and off by default. | No |

### Explicit Allowlist Only

Raw values are never emitted by default. Only explicitly allowlisted keys or
columns are stored as raw values:

- `keepKeys`: case-insensitive exact match for HTTP query/body keys. Matching
  values are stored in HTTP `*_values`.
- `keepHeaderKeys`: case-insensitive exact match for request headers. Matching
  headers are stored as shape/token only. Empty means request header presence is
  not recorded.
- `keepResponseHeaderKeys`: case-insensitive exact match for response headers.
  Matching headers are stored as shape/token only. Empty means response header
  presence is not recorded.
- `sqlValueAllowlist`: case-insensitive `table.column` or `column`. Matching
  values are stored in SQL `observed_values`.

The old `deny_keys` blacklist model is removed. There is no deny-vs-allow
precedence rule because raw values are opt-in only. When `secret` is set,
`*_tokens` are emitted for query/body values across all keys. Header tokens are
emitted only for request/response headers that match their header allowlists.

Configuration names here use the PHP `Config` property names, such as
`keepKeys` and `captureText`.

## Flow

`flow` is optional investigation correlation for grouping multiple HTTP
requests.

In normal observer usage, no `Flow` is passed and `flow_id` / `seq` are `null`.
Developers or QA may pass flow values from a test header or scenario runner when
they intentionally run a correlated investigation.

| Field | Description |
|---|---|
| `flow_id` | Optional correlation identifier. `null` means no flow was specified. |
| `seq` | Optional step number. May be `null`. |

Production session-derived ids do not necessarily represent business scenarios:
browser back, multiple tabs, async requests, and abandonment can all mix
requests. Reports must not infer specifications or business workflows from
`flow` alone.

## HTTP Envelope

The `http` object records request input and response output.

### Path Fields

| Field | Example | Description |
|---|---|---|
| `path` | `/products/123` | Actual request path. |
| `path_pattern` | `/products/{id}` | Endpoint pattern from router/config if available. |
| `path_tokens` | `/products/{p-a1b2}` | HMAC-tokenized path. Omitted when `path_pattern` is unavailable or `secret` is unset. |

### Response Kind

`response_kind` is `"json"`, `"html"`, `"other"`, or `null`.

- `"json"`: response structure is recorded in `response_shape`.
- `"html"`: rendered templates and variable shapes are recorded in `views[]`.
  HTML bodies are not recorded because they can be large.
- `"other"` / `null`: no response body shape is recorded.

### HTTP Headers

`request_headers_shape` / `request_headers_tokens` contain only request headers
matching `keepHeaderKeys`. `response_headers_shape` /
`response_headers_tokens` contain only response headers matching
`keepResponseHeaderKeys`. Both allowlists default to empty, so header presence is
not recorded by default.

There is no field for raw header values. When `secret` is set, only HMAC tokens
are emitted. `Authorization`, `Cookie`, `Set-Cookie`, `Location`, and
`X-Api-Key` can be sensitive even as presence information, so include only the
headers required for a specific investigation. `X-Tekagami-Flow` can be
tokenized as a request header when allowlisted, but `flow.flow_id` is stored as
the raw value because it is expected to be an explicit non-sensitive QA or
development investigation id.

## Timeline

`timeline[]` records data operations in occurrence order within one request.
`seq` is one shared counter across SQL and custom events, starting at 1.

### type: "sql"

One SQL statement.

- `statement_normalized`: SQL with literals replaced by `{parameter}`. Database
  generated time values are normalized to `{db_time}`.
- `statement_hash`: `sha256:<hex>` of `statement_normalized`. This is layer A:
  strict equality of normalized SQL text. It corresponds to
  `effects[].statement_hash`.
- `statement_fingerprint`: layer B, built from operation, target tables, filter
  columns, and write columns. Required in the current `tekagami-v1` schema.
- `statement_fingerprint.fp_hash`: `fp1:<hex>`. Useful for migration review
  when raw SQL text changes across frameworks but the operation is meaning-near.
- `statement_fingerprint.filter_columns`: columns extracted from comparison
  left-hand sides in `WHERE`, `ON`, and `HAVING`.
- `statement_fingerprint.write_columns`: columns extracted from `INSERT` column
  lists and `UPDATE SET` left-hand sides.
- `observed_values`: raw SQL values matching `sqlValueAllowlist`, for example
  `{ "ORDERS.STATUS": { "redacted": false, "values": ["shipped"] } }`.
  Extraction is regex-based best effort for executed SQL text and may miss
  functions, comma-containing strings, complex subqueries, or dialect-specific
  literals. Tokenized `observed_values` are reserved for future extension.
- `analysis.analyzer`: analyzer implementation style. Currently `regex`.
- `analysis.dialect`: SQL dialect used by the injected analyzer, such as
  `oracle` or `sqlite`.
- `analysis.input_quality`: SQL capture quality. `bound_sql` means placeholder
  SQL plus binds; `expanded_sql` means values were already expanded, such as
  `last_query` or query history.
- `analysis.warnings`: reasons capture confidence is lower, such as
  `expanded_sql_may_fragment_statement_hash`,
  `query_history_capture_has_no_bind_values`,
  `last_query_capture_has_no_bind_values`, `db_time_normalized`, or
  `oracle_dual_select`.

Prefer `Collector::addSql($sql, $binds, ['source' => '...'])` when placeholder
SQL and binds are available. Use
`Collector::addExpandedSql($sql, ['source' => 'ci3-last_query'])` when only
expanded SQL is available; the record will carry the lower-quality input signal.

Layer A answers "is the normalized SQL text the same?" Layer B answers "does the
operation target the same tables and columns?" In migration review, layer A can
be noisy across frameworks, so `tools/tekagami-data/bin/tekagami export`
includes layer B data for legacy/target comparison.

### type: "custom"

Manual instrumentation from the app or adapter. `label` identifies the event.
`data_shape` is not part of pattern signatures, so encode stable branch
distinctions in the label when they must be compared as distinct behavior.

## Effects

`effects[]` summarizes write SQL statements from `timeline[type=sql]`:
`INSERT`, `UPDATE`, `DELETE`, `MERGE`, `REPLACE`, and `UPSERT`. Summaries are
grouped by `(op, table, statement_hash)`.

`effects[].statement_hash` corresponds to `timeline[type=sql].statement_hash`.

Set `captureEffects: false` to disable effect summaries.

## Production Validation

The schema is used as a test/CI contract. `tests/SchemaConformanceTest.php`
validates both fixtures and actual `Collector` output. Production requests are
not validated per request to avoid observation overhead.

Fields that are especially useful for AI or human analysis:

- `statement_normalized`: SQL pattern reading.
- `statement_hash`: joins timeline entries to effects.
- `statement_fingerprint`: meaning-near SQL comparison across framework
  migrations.
- `observed_values`: allowlisted SQL values that provide business evidence.
- `effects[]`: write summaries without scanning the full timeline.
- `query_values` / `request_values`: allowlisted HTTP values that provide
  business evidence.

For AI review, prefer
`tools/tekagami-data/bin/tekagami export <jsonl...>` over raw JSONL. The export
deduplicates SQL text into `sql_dictionary` and lets behavior patterns refer to
short SQL ids and `fp_hash`.

For migration review, export legacy and target logs separately, then run
`tools/tekagami-data/bin/tekagami diff <legacy-export.json> <target-export.json>`
to produce deterministic differences.

## Currently Represented as Custom Events

The following dedicated timeline types are not in the v1 `oneOf`. Record them
with `addCustom()` when needed:

- `type: "external_http"`: external Web API calls.
- `type: "file"`: file reads/writes.
- `type: "session"`: session writes.
- Tokenized `observed_values` (`redacted: true`).
