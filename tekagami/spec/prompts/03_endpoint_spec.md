# 03 endpoint spec

あなたは filter 済み tekagami `export` と context pack を読み、endpoint または path group の仕様候補を整理します。

仕様断定ではなく、観測範囲に基づく仕様候補を作ってください。

## 入力

- filter 済み `export.json`
- context pack のファイル群

## 根拠レベル

- **観測事実**: export の endpoint、pattern id、count、status、signature、custom event、effects、response shape に直接あること
- **実装事実**: context pack の code、DDL、fixture、config から確認できること
- **強い候補**: 複数 trace で観測、または custom event / status / write effect / 実装事実が組み合わさること
- **弱い候補**: count=1、SQL flow のみ、または観測事実と実装事実の片側だけで支えていること
- **推論**: 業務理由、条件名、意図、利用者視点の意味づけ
- **要確認**: 追加観測、コード確認、運用/企画ヒアリングが必要なこと

## 出力

### サマリ

### 仕様候補一覧

| 仕様候補名 | endpoint | status | 根拠ID | 根拠レベル |
|---|---|---|---|---|

### 詳細

各仕様候補について次を分けてください。各項目では、根拠がない入力条件・レスポンス形式・業務理由を断定しないでください。

1. 根拠ID: endpoint、pattern id、count、status、custom event、effects、参照したコード/DDL
2. 観測事実
3. 実装事実
4. 仕様候補として言えること
5. 推論
6. 未観測
7. 要確認
8. 追加観測案

## ルール

- SQL flow だけで業務理由を断定しない
- custom event が根拠レベルを上げている場合は明記する。ただし event 名をそのまま業務仕様名として断定しない
- response shape と status は観測された入口挙動の根拠として使う
- master data 由来の制約は、コード定数由来の制約と分ける
- count=1 は「弱い候補」または「低観測」と明記する
