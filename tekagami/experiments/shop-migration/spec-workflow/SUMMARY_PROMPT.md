# tekagami summary 優先度付けプロンプト

あなたは tekagami の `summary` 出力を読み、仕様発見の深掘り順を決めます。

目的は仕様を断定することではありません。観測された endpoint 地図から、どの endpoint または endpoint group を次に詳しく見るべきかを整理してください。

## 先に読むファイル

- `experiments/shop-migration/ci3-shop/var/summary.md` または `summary.json`
- 必要に応じて `experiments/shop-migration/ci3-shop/app/application/config/routes.php`

## summary の読み方

- `observed_endpoint_catalog[]` は観測された endpoint の一覧です。
- `group_hint` は URL prefix 由来の機械的なまとまりであり、業務名ではありません。
- `pattern_count` が多い endpoint は、同じ入口に複数の分岐候補があります。
- `custom_event_count` が多い endpoint は、業務分岐名の観測アンカーが多い可能性があります。
- `has_write_effects=true` の endpoint は、状態変更や副作用を伴います。
- `rare_pattern_count` は count=1 の実行パターン数です。専用の異常検知ではなく、深掘り候補の目印として扱ってください。

## 根拠レベル

- **観測事実**: summary に直接出ている件数、status、pattern、custom event、write、error、truncated
- **強い深掘り候補**: 件数が多い、分岐が多い、custom event や write がある、拒否/エラーが混ざる
- **弱い深掘り候補**: count=1 や rare が中心で、観測が少ない
- **推論**: endpoint 名や group_hint から推測した役割。業務名として断定しない
- **要確認**: routes や追加 export がないと判断できないこと

## やってほしいこと

1. 深掘り優先度の高い endpoint / group_hint を列挙してください。
2. 優先度の理由を、観測事実に基づいて書いてください。
3. 次に実行する `tools/tekagami-data/bin/tekagami export` の filter 例を提案してください。

## 出力形式

日本語で、次の形式にしてください。

### 全体サマリ

観測 endpoint 数、主要 group_hint、write を伴う endpoint、分岐が多い endpoint を短くまとめる。

### 深掘り優先度

| 優先度 | 対象 | 理由 | 推奨 filter |
|---|---|---|---|

### 注意点

- `group_hint` を業務名として断定しないでください。
- 観測されていない endpoint を「存在しない」と扱わないでください。
- rare pattern を即バグ扱いしないでください。
- count=1 は「低観測」と明記し、仕様断定ではなく追加観測候補として扱ってください。
