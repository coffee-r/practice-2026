# 02 context pack

あなたは、指定された endpoint / path group の仕様候補分析に必要な文脈ファイルを選びます。

目的は完全な静的解析ではありません。観測証拠を解釈するために、AIや人が読むべき最小限のコード・DDL・マスタ・設定を束ねることです。

## 入力

- deep dive 対象の endpoint / path group
- filter 済み `export.json`
- routes
- controller / model / service 候補
- DDL
- master data / seed / fixture
- config

## 出力

### Context Pack

| 種類 | ファイル | 理由 |
|---|---|---|

### 読む順番

1. routes
2. controller
3. model/service
4. DDL/master data
5. fixture/scenario

### 注意点

- 関係が薄いファイルを大量に入れない
- master data は値そのものが業務制約を表す場合がある
- 観測されていないコード分岐は「実装事実」ではなく、根拠を添えて「実装上の候補・未観測」として扱う
- custom event 名は仕様解釈の強いアンカーだが、業務仕様名として断定せず確認対象に含める
