# CI3 shop → Laravel12 shop 移植差分分析プロンプト

あなたは tekagami が記録した2つのアプリケーションの観測ログを比較し、
移植後の挙動差分候補を整理します。

目的は「確定バグ報告」ではありません。
**観測事実・実装事実・推論を必ず分けて書いてください。**

---

## 先に読むファイル

回答する前に、以下のファイルを読んでください。

- `experiments/shop-migration/diff/diff.md` (または `diff.json`)
- `experiments/shop-migration/ci3-shop/var/export.json`
- `experiments/shop-migration/ci3-shop/var/report.md`
- `experiments/shop-migration/laravel12-shop/var/export.json`

---

## diff.json / diff.md の読み方

`tools/tekagami-data/bin/tekagami diff` が生成する差分レポートの構造:

- `entrypoints.added[]` / `entrypoints.removed[]` — エントリポイント追加/消失
- `entrypoints.changed[]` — 変化したエントリポイント。各要素に:
  - `status_codes` — HTTPステータスコードの増減
  - `patterns.added[]` / `patterns.removed[]` / `patterns.changed[]` — 実行パターンの追加/消失/変化
  - `patterns.changed[].custom_events` — カスタムイベントの増減
  - `patterns.changed[].effects` — 書き込みSQL副作用の増減
- `meaning_near_matches[]` — **layer-B fp が一致するが SQL 文字列が異なる候補**

### meaning_near_matches の解釈

```
"fp": "fp1:...",
"legacy_sqls": ["SELECT * FROM SHOP_PRODUCTS WHERE CODE = :p1"],
"target_sqls": ["select * from \"SHOP_PRODUCTS\" where \"CODE\" = ?"]
```

これは「意味近似一致候補」です。fp（操作・対象テーブル・絞り込み列・書込列から計算）が
同一であることから、同一の業務ロジックを実装している可能性が高いと推定されます。
ただし fp の一致は意味的等価の保証ではありません。

---

## 分析の方針

### 観測事実と実装事実を分ける

- **観測事実**: diff.json に現れた差分（status_codes の変化、パターンの追加/消失、custom_events の差など）
- **実装事実**: ci3-shop と laravel12-shop のコードを読んで確認できること
- **推論**: 上記から導出した「移植後の挙動差分候補」

### 根拠レベル

- **観測事実**: diff/export に直接出ている endpoint、pattern、count、status、custom event、effects
- **実装事実**: code、DDL、fixture、config から確認できること
- **強い候補**: 複数 trace、custom event、write effect、実装事実が対応する差分候補
- **弱い候補**: count=1、SQL flow のみ、片側だけの観測、または scenario 不足の可能性が高い差分候補
- **推論**: 差分理由、業務影響、移植上の意図
- **要確認**: 追加観測、コード確認、ヒアリングが必要なこと

### 「観測されなかった = 存在しない」ではない

E2E シナリオが観測していない実行パスは差分に現れません。
パターンの消失は「そのパスを通るシナリオがなかった可能性」を含みます。
diff の Added / Removed は、まず「観測パターン差」として扱い、機能追加・機能消失・バグと断定しないでください。

---

## 特に見てほしい観点

1. **エントリポイント追加/消失**
   - 追加 → target 側で観測された入口。新規実装、テスト用入口、シナリオ差の可能性を分ける
   - 消失 → legacy 側でのみ観測された入口。移植漏れ、未実行、条件差の可能性を分ける

2. **status_codes の変化**
   - 同じエンドポイントで異なるステータスコードが観測 → 入力バリデーションやエラーハンドリングの差異候補

3. **custom_events の差**
   - 追加 → target 側でのみ観測された分岐アンカー。新規チェック、観測条件差、イベント実装差の可能性を分ける
   - 消失 → legacy 側でのみ観測された分岐アンカー。未移植、未実行、条件分岐差の可能性を分ける

4. **effects の差**
   - INSERT/UPDATE/DELETE の追加/消失 → 副作用の観測差。実際の有無が変わったかはコードと追加観測で確認する
   - count の変化 → ループ回数や一括処理の差分候補

5. **meaning_near_matches の解釈**
   - 同一 fp の SQL テキスト差は CI3 の生 SQL と Laravel Eloquent の生成 SQL の違いが主因
   - fp が不一致の場合は業務ロジックの差異候補（テーブル・絞り込み列・書込列の違い）

6. **パターン追加/消失の意味**
   - 追加: target 側でのみ観測された実行経路候補
   - 消失: legacy 側でのみ観測された実行経路候補
   - どちらもまず観測パターン差として扱い、機能差やバグは確認推奨に留める

---

## 出力フォーマット

### 1. サマリ

差分の全体像を3〜5行で。

### 2. エントリポイント差分

追加/消失があれば列挙し、各々の推定原因を書く。

### 3. 挙動差分候補（エントリポイント別）

各エントリポイントについて:

```
#### POST /api/cart/items

【観測事実】
- status_codes: 201 が legacy=32 → target=40（+8回観測）
- patterns.changed: custom_event `gift_attached` が追加

【実装事実】
- legacy: GIFT_TRIGGER 商品追加時に gift_attached カスタムイベント
- target: (コード確認結果を記載)

【推論・確認すべき差分】
- 移植版でも gift_attached の条件は同一か確認推奨
```

### 4. meaning_near_matches 解釈

各候補について、どのビジネスロジックのクエリか推定する。
fp が一致していても実装に差異が生じうる点を指摘する。

### 5. 確認推奨事項リスト

移植後の動作確認で重点的に見るべき点を箇条書きで。
バグ断定はしない。「確認推奨」として記載する。
count=1 や片側だけの観測は「弱い候補」「低観測」と明記する。
