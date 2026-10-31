# CI3 shop tekagami 業務発見プロンプト

あなたは CodeIgniter3 + Oracle のデモアプリについて、tekagami が記録した
観測ログを分析します。

目的は「確定仕様」を作ることではありません。観測ログ、アプリケーション
コード、DDL、fixture から、業務挙動の仕様候補を整理してください。
観測事実・実装事実・推論は必ず分けて書いてください。

このプロンプトは、入力者があらかじめ列挙していない分岐を発見できるかを
試すための発見重視版です。下の「特に見てほしい観点」は完全な一覧では
ありません。そこにないflowや分岐も必ず探してください。

## 先に読むファイル

回答する前に、次のファイルを読んでください。

- `experiments/shop-migration/ci3-shop/var/export.json`
- `experiments/shop-migration/ci3-shop/var/report.md`
- `experiments/shop-migration/ci3-shop/app/application/config/routes.php`
- `experiments/shop-migration/ci3-shop/app/application/controllers/api/Shop.php`
- `experiments/shop-migration/ci3-shop/app/application/models/Shop_model.php`
- `experiments/shop-migration/ci3-shop/docker/oracle/init/01-schema.sql`
- `experiments/shop-migration/ci3-shop/scripts/run-e2e.sh`

`experiments/shop-migration/ci3-shop/var/flow-map.tsv` は読まないでください。
`flow_id`（8桁hex）は匿名相関IDであり仕様名ではありません。
`export.json` には flow_id は含まれません。

### export.json の構造

`export.json` は `tools/tekagami-data/bin/tekagami export` が出した minify JSON です。
ファイル先頭の `legend` キーに主要フィールドの意味が書いてあります。

各エントリポイント（`observed_entrypoints[]`）は複数の実行パターン（`patterns[]`）を持ちます。
パターンの主要フィールド:

- `id`: `pattern-1`, `pattern-2` のような連番ID（業務意味なし）
- `signature`: 圧縮されたSQLフロー + ステータスの文字列。**カスタムイベントは `CUSTOM:label` の形でここに埋め込まれます**  
  例: `SELECT:SHOP_PRODUCTS:S3 -> CUSTOM:purchase_limit_rejected -> STATUS:422`
- `statuses[]`: このパターンで観測されたHTTPステータスコードの配列
- `sql_flow[]`: 各要素 `{s, op, tables, fp}` — `s` は `sql_dictionary` の参照ID
- `effects[]`: 書き込み系SQLの副作用サマリ。各要素 `{op, table, statement_hash, count}`

SQL全文は末尾の `sql_dictionary` に集約されています。

### report.md について

`report.md` は `tools/tekagami-data/bin/tekagami report` が生成したMarkdownです。
endpoint ごとの status、pattern、SQL flow、effects を読むための補助情報として扱ってください。

`tekagami.jsonl` は読まないでください。
必要な観測情報は `export.json` と `report.md` を優先してください。

`01-schema.sql` には、現時点ではテーブル定義とseed fixtureが同居しています。
このデモにおけるDB文脈の一次情報として扱ってください。

`routes.php` は URLからcontroller/actionを探す索引として使ってください。
route名・URL文字列だけで業務仕様を断定せず、必ず観測ログとコードで裏取りしてください。

## やってほしいこと

観測されたAPI挙動パターンごとに、業務挙動の仕様候補を作ってください。
tekagami の `export.json` と `report.md` を主な証拠として使い、
アプリケーションコードとDDLは、その証拠を解釈するために使ってください。

あわせて、入力者があらかじめ予想していない可能性のある匿名flow、pattern、
SQL副作用、customイベント、ステータス差分を探してください。既知の観点に
当てはまるものだけを整理するのではなく、「これは意外かもしれない」
「仕様候補として別名を付けるべきかもしれない」という発見を独立して
列挙してください。

### 根拠レベル

| 根拠レベル | 条件 |
|---|---|
| 観測事実 | export/report に直接ある endpoint、pattern id、count、status、signature、custom event、effects |
| 実装事実 | controller/model、DDL、fixture、config から確認できること |
| 強い候補 | 複数 trace、または custom event / status / write effect / 実装事実が組み合わさる |
| 弱い候補 | count=1、SQL flow のみ、または観測事実と実装事実の片側だけで支えている |
| 推論 | 業務理由、条件名、意図、利用者視点の意味づけ |
| 要確認 | 追加観測、コード確認、運用/企画ヒアリングが必要 |

## 出力形式

回答は日本語で書いてください。

まず短い全体サマリを書き、その後に仕様候補の一覧表、最後に詳細と
追加観測の提案を書いてください。

一覧表の列: 仕様候補名 / エンドポイント / status / 根拠ID / 根拠レベル

各仕様候補は `### {仕様候補名}` の見出しで書き、以下の順に記述してください。

1. **根拠ID** — `METHOD /path`、pattern id、count、status、custom event、effects、参照したコード/DDL
2. **観測証拠** — signature 中の `CUSTOM:xxx`、effects の op/table、status分布
3. **実装証拠** — controller/model メソッド、関連テーブル・カラム、seed fixture
4. **仕様候補として言えること**
5. **推論**
6. **根拠レベル** — 観測事実 / 実装事実 / 強い候補 / 弱い候補 / 推論 / 要確認
7. **未確定事項** — 現証拠では断定できないこと
8. **推奨アクション** — 追加E2Eシナリオまたはcustomイベント名

さらに、「予想外・未分類の発見」という章を作ってください。
そこでは次を確認してください。

- endpoint ごとの主要 pattern から漏れていそうな flow や pattern がないか
- 同じエンドポイント・同じステータスなのに SQL フローが違うものがないか
- 入力に flow id 相当の情報がある場合、同じ匿名 flow id なのに複数の pattern に分かれているものがないか
- customイベントがないのに業務上重要そうな分岐がないか
- customイベント名やイベント内のキー名が、実際に記録している意味とズレていないか
- `INSERT` / `UPDATE` / `DELETE` が発生しているのに、業務名が付いていない副作用がないか
- 逆に、成功に見えるのに期待される更新SQLが出ていないケースがないか
- 件数が1件だけの rare pattern が、単なるテストケースなのか、見落としやすい業務分岐なのか
- N+1、重複SELECT、想定外のテーブル参照など、移行時に注意すべき実装上の癖がないか
- 仕様候補ではなく、移行時にバグ・仕様誤認・データ不整合・性能問題になりそうな実装上の癖がないか

## 解釈ルール

- 証拠が直接的で十分に強い場合を除き「これは仕様である」と断定しないでください。「観測された挙動」「仕様候補」「推測」といった表現を使ってください。
- 観測事実・実装事実・推論は必ず分けて書いてください。customイベントがあるから読めている分岐はそのことを明記してください。
- テーブル名・SQL更新・URL文字列だけで業務上の理由を断定しないでください。理由がコードまたはcustomイベントからの推論の場合はそう明記してください。
- custom event 名は強い観測アンカーですが、業務仕様名としては確認対象です。
- customイベント名やpayloadのキー名が実態とズレている場合は、仕様候補ではなく「命名・観測設計上の注意」として分けて書いてください。
- count=1 は「弱い候補」または「低観測」と明記してください。
- 400番台レスポンスの `error` / `code` / `reason` などの値は業務分岐名の手がかりとして使ってかまいませんが、customイベント・SQL副作用・controller分岐条件と突き合わせてください。
- 同じendpoint/statusでも SQL flow（signature）が違う場合は、別分岐候補として扱ってください。
- 「観測されていない」ことを「存在しない」と扱わないでください。
- 仕様候補と、次に観測すべきケースを分けて書いてください。
- exportが大きい場合でも、まず entrypoint、status分布、pattern count、rare pattern、customイベント、effects を優先して見てください（サイズ対策のために情報を無視した場合はその前提を明記してください）。
- 既存の想定や人手の業務分岐表がある場合もヒントとして扱い、そこにない分岐も積極的に探してください。

## 特に見てほしい観点

この一覧はヒントであり、完全な観点リストではありません。
ここにないflowや分岐も必ず探してください。

- 購入制限
- 配送方法の選択
- 支払方法の絞り込み
- 配送日・配送時間の制約
- 送料の閾値
- プレゼント同梱
- ポイント交換
- バラエティ商品の組み合わせ条件
- 予約商品のカート投入先
- セット商品の構成品読み込み
- メールアドレスなし顧客の注文拒否

## 最後に確認してほしいこと

分析結果の最後に、次の観点で不足を指摘してください。

- tekagamiだけで十分に読める分岐
- customイベントがないと意味付けが難しい分岐
- E2Eシナリオを追加した方がよい分岐
- テーブル定義やfixtureを追加した方がよい分岐
- 入力者の予想や既存の業務分岐表から漏れていそうな分岐
- 「仕様」ではなく、実装上の癖や移行リスクとして扱うべきpattern
- customイベント名・payloadキー名が誤読を招きそうな箇所
- 移行時にバグ、仕様誤認、データ不整合、性能問題になりそうな箇所

移行リスクは、できれば 高 / 中 / 低 の優先度を付けてください。
