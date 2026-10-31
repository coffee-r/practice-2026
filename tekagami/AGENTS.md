# AGENTS.md

このファイルは、AI agent がこのリポジトリで作業するときの短いガイドです。詳細仕様の正本ではありません。

## プロジェクトの目的

- `tekagami` は、稼働中アプリケーションの実際の振る舞いを `tekagami-v1` JSONL として記録し、仕様調査・移行調査のための証拠を作るプロジェクトです。
- 出力するのは「観測された事実」です。仕様の断定、業務ルールの命名、要約、分析は observer 本体では行いません。
- APM ではありません。速度やレイテンシではなく、HTTP 入出力、SQL の時系列、custom event、effects、capture error など「何が起きたか」を扱います。
- JSONL schema は言語非依存の契約です。PHP 以外の observer も同じ契約を出せば、同じ data tools / spec workflow で扱える設計です。

## 安全原則

- 実値はデフォルトで保存しません。shape を基本とし、実値は明示 allowlist のみです。
- 個人情報・認証情報・決済情報が混ざりうるため、値の保存や token 化の変更は必ず下位ドキュメントと tests/schema を確認してください。
- ログ書き込みや capture に失敗しても、対象アプリケーション本体の挙動を変えない方針です。
- 観測できなかったケースを「存在しない仕様」と断定しないでください。観測範囲の限界として扱います。

## レイヤ責務

- `observer-php/`: PHP observer / Composer package。収集、正規化、マスキング、sink、JSONL 書き出しを扱います。
- `contracts/schema/`: `tekagami-v1` JSONL の言語非依存 schema contract です。
- `tools/tekagami-data/`: `summary`, `report`, `export`, `diff` などの決定論的な data tools です。
- `spec/`: 観測証拠とコード・DDL・fixture などを合わせて仕様候補を作る workflow / prompts です。
- `experiments/`: エンドツーエンドのシナリオ実装と代表的な生成成果物です。`experiments/<scenario>/` 単位でシナリオをまとめます（現在: `shop-migration/`）。

## 正本を見る場所

- プロジェクト入口: `README.md`
- PHP observer の API / 設定 / 使い方: `observer-php/README.md`
- JSONL schema: `contracts/schema/README.md` と `contracts/schema/tekagami-v1.schema.json`
- data tools: `tools/tekagami-data/README.md`
- spec workflow: `spec/README.md`
- experiments / sample outputs: `experiments/README.md` と `experiments/shop-migration/README.md`
- 実装の期待挙動: 該当レイヤの tests と fixtures

## メンテナンスルール

- 通常の仕様変更では、この `AGENTS.md` を更新しないでください。
- schema field、CLI option、SQL 解析挙動、diff/export の詳細、実験環境手順など、変わりやすい仕様をここに書き足さないでください。
- 仕様変更時は、責務を持つレイヤの README / schema / fixture / tests を更新してください。
- このファイルを更新するのは、AI の作業原則、正本リンク、またはリポジトリ全体の責務分担が変わる場合だけです。
