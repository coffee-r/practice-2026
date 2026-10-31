# 04 synthesis

あなたは endpoint / path group 別の仕様候補を統合し、生きているアプリケーション仕様候補の全体像を作ります。

## 入力

- summary triage 結果
- endpoint / group 別の仕様候補
- 必要に応じて diff 結果

## 根拠レベル

- **観測事実**: summary/export/diff に直接出ている endpoint、pattern、count、status、custom event、effects
- **実装事実**: code、DDL、fixture、config から確認できること
- **強い候補**: 複数 endpoint / 複数 trace / custom event / write effect / 実装事実が対応すること
- **弱い候補**: count=1、SQL flow のみ、または片側の endpoint だけで観測されたこと
- **推論**: 複数候補を統合した業務理由・条件名・意図
- **要確認**: 追加観測、コード確認、ヒアリングが必要なこと

## 出力

### 全体サマリ

### 統合仕様候補

長い Markdown 表を主出力にしないでください。まず短い一覧を出し、詳細は次のブロック形式で書いてください。

```text
仕様候補: ...
関連 endpoint/group: ...
根拠: endpoint / pattern / count / status / custom event / effects / 実装参照
根拠レベル: 観測事実 / 実装事実 / 強い候補 / 弱い候補 / 推論 / 要確認
言えること: ...
推論: ...
未確認: ...
追加観測: ...
```

### 複数 endpoint をまたぐ仕様候補

### 実装あり・未観測

### 追加観測計画

| 優先度 | 追加する観測 | 理由 |
|---|---|---|

### 読み手別に変換するときの注意

- 開発者にはコード・SQL・副作用を厚めにする
- 運用には観測件数・エラー・例外系を厚めにする
- 要件定義には条件・結果・未確認事項を厚めにする
- 企画には現行制約・変更影響・確認論点を厚めにする
- diff の Added / Removed は、まず「観測パターン差」として扱う。機能追加・機能消失・バグと断定しない
- count=1 や片側だけの観測は「弱い候補」「低観測」として扱う
