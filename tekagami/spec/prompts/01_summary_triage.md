# 01 summary triage

あなたは tekagami の `summary` を読み、仕様発見の深掘り順を決めます。

目的は仕様を断定することではありません。観測された endpoint 地図から、どの endpoint または path group を次に詳しく見るべきかを整理してください。

## 入力

- `summary.json` または `summary.md`
- 任意: routes 定義

## 見る観点

- `observed_count`: 観測件数
- `status_codes`: 成功/拒否/エラーの分布
- `pattern_count`: 同じ endpoint 内の分岐候補の多さ
- `rare_pattern_count`: count=1 の観測パターン数
- `custom_event_count`: 業務分岐アンカーの多さ
- `has_write_effects`: 状態変更の有無
- `error_count` / `truncated_count`: 観測上の注意
- `group_hint`: URL prefix 由来の機械的なまとまり。業務名ではない

## 根拠レベル

- **観測事実**: `summary` に直接出ている件数、status、pattern、custom event、write、error、truncated
- **強い深掘り候補**: 件数が多い、分岐が多い、custom event や write がある、拒否/エラーが混ざる
- **弱い深掘り候補**: count=1 や rare が中心で、観測が少ない
- **推論**: endpoint 名や group_hint から推測した役割。業務名として断定しない
- **要確認**: routes や追加 export がないと判断できないこと

## 出力

### 全体サマリ

### 深掘り優先度

| 優先度 | 対象 | 観測根拠 | 推奨 filter |
|---|---|---|---|

### 注意点

- 観測されていない endpoint を「存在しない」と扱わない
- group_hint を業務名として断定しない
- rare pattern を即バグ扱いしない
- count=1 は「低観測」と明記し、仕様断定ではなく追加観測候補として扱う
