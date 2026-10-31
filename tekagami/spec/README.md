# spec

`spec` は、tekagami が採取した観測証拠と、現在のアプリケーション文脈を合わせて「生きているアプリケーション仕様候補」を作るための上位レイヤです。

`tekagami` 本体は、実行中アプリの HTTP / SQL / custom event / response shape を安全に記録し、`summary` / `report` / `export` / `diff` で決定論的に加工するところまでを担当します。

`spec` は、その先の作業を扱います。

```text
1. 観測する
   tekagami: JSONL / summary / export / diff

2. 文脈を集める
   spec: code, routes, DDL, master data, fixture, config の束ね方

3. 照合する
   spec: 観測された挙動と実装・マスタ値の対応づけ

4. 仕様候補にする
   spec: 観測事実 / 実装事実 / 推論 / 未観測を分けた仕様候補

5. 読み手別に出す
   spec: 開発者、運用、要件定義、企画向けのビュー
```

## ディレクトリ

```text
spec/
  README.md
  prompts/
    01_summary_triage.md
    02_context_pack.md
    03_endpoint_spec.md
    04_synthesis.md
    audience_developer.md
    audience_operations.md
    audience_requirements.md
    audience_planning.md
  # シナリオ固有の適用例は experiments/<scenario>/spec-workflow/ 以下にあります
```

## 入力の考え方

観測証拠は原本と派生成果物を分けます。

| 種類 | 例 | 扱い |
|---|---|---|
| 原本 | `tekagami.jsonl` | 直接編集しない。観測範囲の証拠として残す |
| 地図 | `summary.json` / `summary.md` | どの endpoint / group を深掘りするか決める |
| AI投入用 | `export.json` | endpoint / path filter 済みの小さい証拠パック |
| 差分 | `diff.json` / `diff.md` | legacy / target の移行確認用 |
| 文脈 | routes, controller, model, DDL, master data, config | 観測事実を解釈するための材料 |

大きい JSONL や export をこのディレクトリへ必ずコピーする必要はありません。まずは `experiments/<scenario>/spec-workflow/inputs/README.md` に、どのファイルを入力として使うかを明記します。必要になったら、調査回ごとに `inputs/` へコピーまたは生成物を置きます。

## 出力ラベル

仕様候補は必ず次のラベルを分けます。

- **観測事実**: tekagami のログで実際に見えたこと
- **実装事実**: コード、DDL、master data、config から確認できること
- **強い候補**: 複数 trace、custom event、write effect、status、実装事実など複数の根拠が対応すること
- **弱い候補**: count=1、SQL flow のみ、または観測事実と実装事実の片側だけで支えていること
- **推論**: 観測事実と実装事実から導いた候補
- **未観測**: コード上はありそうだが、観測範囲では見えていないこと
- **要確認**: 運用意図、業務意図、企画判断、法務・顧客対応など、ログとコードだけでは決めないこと

## 運用ルール

`spec` workflow は仕様書の自動生成ではありません。観測証拠から、仕様候補と追加観測計画を作るための作業手順です。

- 「観測されなかった」ことを「存在しない」と扱わないでください。
- custom event 名は強い観測アンカーですが、業務仕様名としては確認対象です。
- count=1 の pattern は、低観測または弱い候補として扱ってください。
- SQL flow だけで業務理由、入力条件、利用者向け制約を断定しないでください。
- audience 向けに変換するときも、観測で確認できたこと、仕様候補、推論、未確認を分けてください。

## 最初の運用

1. `php tools/tekagami-data/bin/tekagami summary <jsonl...> --format json > summary.json`
2. `prompts/01_summary_triage.md` で深掘りする endpoint / path group を選ぶ
3. `php tools/tekagami-data/bin/tekagami export <jsonl...> --path "/api/cart/*" > cart.export.json`
4. `prompts/02_context_pack.md` で関連コード・DDL・マスタを束ねる
5. `prompts/03_endpoint_spec.md` で endpoint/group の仕様候補を書く
6. `prompts/04_synthesis.md` で統合する
7. audience prompt で読み手別に変換する
