# tekagami endpoint 分析統合プロンプト

あなたは複数の endpoint / endpoint group 分析結果を統合し、仕様候補の全体像と追加観測計画を整理します。

目的は、endpoint ごとの分析を機械的につなげることではありません。重複、抜け、複数 endpoint をまたぐ業務フロー、追加で観測すべき分岐を見つけてください。

## 先に読むもの

- `SUMMARY_PROMPT.md` で作った深掘り優先度結果
- `ENTRYPOINT_PROMPT.md` で作った endpoint 別分析結果
- 必要に応じて routes / controller / model / DDL

## やってほしいこと

1. endpoint 別の仕様候補を統合してください。
2. 同じ業務ルールを別名で説明している候補があればまとめてください。
3. 複数 endpoint をまたぐ仕様候補を独立して整理してください。
4. custom event が足りず、意味付けが弱い箇所を列挙してください。
5. 追加 E2E / 追加 custom event / 追加 fixture の候補を出してください。

## 根拠レベル

- **観測事実**: summary/export/diff に直接出ている endpoint、pattern、count、status、custom event、effects
- **実装事実**: code、DDL、fixture、config から確認できること
- **強い候補**: 複数 endpoint / 複数 trace / custom event / write effect / 実装事実が対応すること
- **弱い候補**: count=1、SQL flow のみ、または片側の endpoint だけで観測されたこと
- **推論**: 複数候補を統合した業務理由・条件名・意図
- **要確認**: 追加観測、コード確認、ヒアリングが必要なこと

## 出力形式

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

### 複数 endpoint をまたぐ候補

### 追加観測計画

| 優先度 | 追加するもの | 理由 |
|---|---|---|

### 注意点

- endpoint group や group_hint を業務名として断定しないでください。
- 観測範囲の不足と実装差分を混同しないでください。
- 仕様候補と移行リスクを分けてください。
- diff の Added / Removed は、まず「観測パターン差」として扱ってください。機能追加・機能消失・バグと断定しないでください。
- count=1 や片側だけの観測は「弱い候補」「低観測」として扱ってください。
