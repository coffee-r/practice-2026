# tekagami endpoint 仕様候補発見プロンプト

あなたは tekagami の filter 済み `export` を読み、1 endpoint または endpoint group の仕様候補を整理します。

目的は「確定仕様」を作ることではありません。観測ログ、アプリケーションコード、DDL、fixture から、業務挙動の仕様候補を整理してください。観測事実・実装事実・推論は必ず分けて書いてください。

## 先に読むファイル

- filter 済み `export.json`
- 対象 endpoint に関係する controller / model
- `experiments/shop-migration/ci3-shop/docker/oracle/init/01-schema.sql`
- 必要に応じて `experiments/shop-migration/ci3-shop/scripts/run-e2e.sh`

## export の読み方

- `observed_entrypoints[]` は今回 filter で切り出した endpoint または endpoint group です。
- `patterns[]` は観測された実行パターンです。
- `signature` 中の `CUSTOM:label` は、業務分岐に名前を付ける観測アンカーです。
- `effects[]` は INSERT / UPDATE / DELETE などの副作用サマリです。
- SQL 全文は `sql_dictionary` に集約されています。

## 根拠レベル

- **観測事実**: export の endpoint、pattern id、count、status、signature、custom event、effects、response shape に直接あること
- **実装事実**: controller/model、DDL、fixture、config から確認できること
- **強い候補**: 複数 trace で観測、または custom event / status / write effect / 実装事実が組み合わさること
- **弱い候補**: count=1、SQL flow のみ、または観測事実と実装事実の片側だけで支えていること
- **推論**: 業務理由、条件名、意図、利用者視点の意味づけ
- **要確認**: 追加観測、コード確認、運用/企画ヒアリングが必要なこと

## やってほしいこと

1. endpoint ごとに、観測された pattern を確認してください。
2. custom event、status、effects、SQL flow から仕様候補を作ってください。
3. custom event がなく意味付けが難しい分岐は、その旨を明記してください。
4. 追加した方がよい custom event や E2E シナリオを提案してください。

## 出力形式

まず短いサマリを書き、その後に仕様候補一覧表、最後に詳細を書いてください。

一覧表の列:

| 仕様候補名 | エンドポイント | status | 根拠ID | 根拠レベル |
|---|---|---|---|---|

各仕様候補は次の順で書いてください。

1. **根拠ID** — endpoint、pattern id、count、status、custom event、effects、参照したコード/DDL
2. **観測証拠**
3. **実装証拠**
4. **仕様候補として言えること**
5. **推論**
6. **根拠レベル**
7. **未確定事項**
8. **推奨アクション**

## 解釈ルール

- 「観測された挙動」「仕様候補」「推測」を分けてください。
- custom event がある場合は、その event が根拠レベルを上げていることを明記してください。ただし event 名をそのまま業務仕様名として断定しないでください。
- SQL やテーブル名だけで業務理由を断定しないでください。
- 観測されていないことを「存在しない」と扱わないでください。
- count=1 は「弱い候補」または「低観測」と明記してください。
