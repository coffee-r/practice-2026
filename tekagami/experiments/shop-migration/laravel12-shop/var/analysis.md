# 観測振る舞い証拠レポート

- 生成日時: `2026-06-01T22:07:39+00:00`
- トレース数: `69`
- 観測エントリポイント数: `7`
- 観測期間: `2026-06-01T22:07:31+00:00` ～ `2026-06-01T22:07:33+00:00`
- 値モード: `normalized`

> 観測エントリポイントは証拠ビューであり、移行単位の仮定ではありません。

## POST /api/cart/items

- エントリポイントキー: `POST /api/cart/items`
- 観測リクエスト数: `36`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 201 | 32 |
| 404 | 1 |
| 422 | 3 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-1 | 21 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |
| pattern-10 | 5 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |
| pattern-4 | 2 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → UPDATE SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |
| pattern-2 | 1 | 404 | SELECT SHOP_PRODUCTS |
| pattern-3 | 1 | 422 | SELECT SHOP_PRODUCTS |
| pattern-5 | 1 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → UPDATE SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |
| pattern-6 | 1 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS → INSERT SHOP_CART_ITEMS |
| pattern-7 | 1 | 422 | SELECT SHOP_PRODUCTS |
| pattern-8 | 1 | 201 | SELECT SHOP_PRODUCTS → SELECT SHOP_CUSTOMERS → INSERT SHOP_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |
| pattern-9 | 1 | 422 | SELECT SHOP_PRODUCTS → SELECT SHOP_CUSTOMERS |
| pattern-11 | 1 | 201 | SELECT SHOP_PRODUCTS → INSERT SHOP_RESERVATION_CART_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS |

### 振る舞いパターン: pattern-1

- 観測数: `21`
- 代表トレース: `634d01a6-190e-4179-8c9a-1069c51d5744`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 21 |

### 振る舞いパターン: pattern-10

- 観測数: `5`
- 代表トレース: `3832e143-d655-4743-94b5-c800cf3e15a6`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 5 |

### 振る舞いパターン: pattern-4

- 観測数: `2`
- 代表トレース: `e4fa8343-3a2d-4ab6-9db1-c233d7e517c7`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> UPDATE:SHOP_CART_ITEMS:sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017 -> CUSTOM:yumail_product_code_swapped -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e x3 -> UPDATE:SHOP_CART_ITEMS:sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017 -> CUSTOM:yumail_product_code_swapped -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
7. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
8. `UPDATE` on `SHOP_CART_ITEMS`
   ```sql
   update "SHOP_CART_ITEMS" set "PRODUCT_CODE" = {parameter}, "UNIT_PRICE" = {parameter} where "CART_ID" = {parameter} and "PRODUCT_CODE" = {parameter}
   ```
   ハッシュ: `sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017`
9. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 2 |
| UPDATE | SHOP_CART_ITEMS | 2 |

### 振る舞いパターン: pattern-2

- 観測数: `1`
- 代表トレース: `6eb8fd2a-fa47-4eac-95ed-a33145cd4155`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> STATUS:404`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> STATUS:404`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-3

- 観測数: `1`
- 代表トレース: `fb36c99e-0678-42d6-b749-0dc5141cda0f`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-5

- 観測数: `1`
- 代表トレース: `4c843f15-fd82-45c0-92da-9ac4030100bb`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> UPDATE:SHOP_CART_ITEMS:sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017 -> CUSTOM:yumail_product_code_reverted -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e x2 -> UPDATE:SHOP_CART_ITEMS:sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017 -> CUSTOM:yumail_product_code_reverted -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
7. `UPDATE` on `SHOP_CART_ITEMS`
   ```sql
   update "SHOP_CART_ITEMS" set "PRODUCT_CODE" = {parameter}, "UNIT_PRICE" = {parameter} where "CART_ID" = {parameter} and "PRODUCT_CODE" = {parameter}
   ```
   ハッシュ: `sha256:b7aeb2e293180ceff523db6b15b25470eeee1830bca46c38098d119a27b0f017`
8. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 1 |
| UPDATE | SHOP_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-6

- 観測数: `1`
- 代表トレース: `8f298d9e-b5dd-470b-a068-adfe56b87a1d`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> SELECT:SHOP_CART_ITEMS:sha256:69645bda0387f601ff8dd8aceb965a4f5116ae4ed7927e74dd572e20de83693a -> INSERT:SHOP_CART_ITEMS:sha256:962b4a09eb4ab2f059de5c1ca17fdfd3f76ae497e6a8c5ae1d8d2fd670bca66a -> CUSTOM:gift_attached -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> SELECT:SHOP_CART_ITEMS:sha256:69645bda0387f601ff8dd8aceb965a4f5116ae4ed7927e74dd572e20de83693a -> INSERT:SHOP_CART_ITEMS:sha256:962b4a09eb4ab2f059de5c1ca17fdfd3f76ae497e6a8c5ae1d8d2fd670bca66a -> CUSTOM:gift_attached -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`
7. `SELECT` on `SHOP_CART_ITEMS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items WHERE cart_id = {parameter} AND product_code = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:69645bda0387f601ff8dd8aceb965a4f5116ae4ed7927e74dd572e20de83693a`
8. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   INSERT INTO shop_cart_items (cart_id, customer_id, product_code, quantity, unit_price, is_gift, created_at)
             SELECT {parameter}, MIN(customer_id), {parameter}, {parameter}, {parameter}, {parameter}, {db_time} FROM shop_cart_items WHERE cart_id = {parameter}
   ```
   ハッシュ: `sha256:962b4a09eb4ab2f059de5c1ca17fdfd3f76ae497e6a8c5ae1d8d2fd670bca66a`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 1 |
| INSERT | SHOP_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-7

- 観測数: `1`
- 代表トレース: `dc9c293d-3562-4ea0-8621-7b1a24996b08`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:point_exchange_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:point_exchange_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-8

- 観測数: `1`
- 代表トレース: `41910467-2487-4a2b-af6c-0b8fd4433903`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_checked -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_checked -> INSERT:SHOP_CART_ITEMS:sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
3. `INSERT` on `SHOP_CART_ITEMS`
   ```sql
   insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:b222fd4d1f801a4af6e80b9ee1792a75c66a9d89a85f1d9e773a8d5312707bd4`
4. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
5. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
6. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
7. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-9

- 観測数: `1`
- 代表トレース: `8d39a838-600c-4fb8-b233-09370cf7c6a1`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-11

- 観測数: `1`
- 代表トレース: `94a5ce35-a62d-4ab6-bed9-e4c14b4aebc2`
- シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_RESERVATION_CART_ITEMS:sha256:4ae18a2bdc9c496f7f586277b4d08a756c670358df165f641ed282f9379bed1e -> CUSTOM:reservation_cart_used -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> INSERT:SHOP_RESERVATION_CART_ITEMS:sha256:4ae18a2bdc9c496f7f586277b4d08a756c670358df165f641ed282f9379bed1e -> CUSTOM:reservation_cart_used -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3 -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
2. `INSERT` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   insert into "SHOP_RESERVATION_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:4ae18a2bdc9c496f7f586277b4d08a756c670358df165f641ed282f9379bed1e`
3. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
4. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT {parameter} AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = {parameter} AND p.gift_trigger = {parameter} AND ROWNUM = {parameter}
   ```
   ハッシュ: `sha256:2382410535cf64726d11659c1ee937674b8bb516540b042c407aced3f89b5ba3`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_RESERVATION_CART_ITEMS | 1 |

## POST /api/checkout/quote

- エントリポイントキー: `POST /api/checkout/quote`
- 観測リクエスト数: `14`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 200 | 5 |
| 422 | 9 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-3 | 4 | 200 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS |
| pattern-2 | 3 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS |
| pattern-1 | 1 | 200 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES → SELECT SHOP_REMOTE_ISLAND_POSTALS → SELECT SHOP_WORKING_DAYS |
| pattern-4 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS |
| pattern-5 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES |
| pattern-6 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES → SELECT SHOP_REMOTE_ISLAND_POSTALS |
| pattern-7 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES → SELECT SHOP_REMOTE_ISLAND_POSTALS → SELECT SHOP_WORKING_DAYS → SELECT SHOP_WORKING_DAYS |
| pattern-8 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES → SELECT SHOP_REMOTE_ISLAND_POSTALS → SELECT SHOP_WORKING_DAYS |
| pattern-9 | 1 | 422 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS |

### 振る舞いパターン: pattern-3

- 観測数: `4`
- 代表トレース: `40079bb5-0cac-42e9-9f12-9e8bc022128f`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> STATUS:200`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> STATUS:200`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-2

- 観測数: `3`
- 代表トレース: `a0a4764c-2e72-4c63-91fb-74d542e93ece`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-1

- 観測数: `1`
- 代表トレース: `9638b5ea-0b0d-46d9-a3f8-0c03c0b1f693`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:free_shipping_applied -> STATUS:200`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:free_shipping_applied -> STATUS:200`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
5. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`
6. `SELECT` on `SHOP_REMOTE_ISLAND_POSTALS`
   ```sql
   SELECT {parameter} AS found FROM shop_remote_island_postals WHERE postal_code = {parameter}
   ```
   ハッシュ: `sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb`
7. `SELECT` on `SHOP_WORKING_DAYS`
   ```sql
   SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE({parameter}, {parameter}) AND TO_DATE({parameter}, {parameter}) ORDER BY work_date
   ```
   ハッシュ: `sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-4

- 観測数: `1`
- 代表トレース: `9369c034-e054-4d0b-8905-43e93470ec34`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:delivery_date_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:delivery_date_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-5

- 観測数: `1`
- 代表トレース: `4122d7df-3ebc-476e-b003-7a116c87beac`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> CUSTOM:delivery_date_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> CUSTOM:delivery_date_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
5. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-6

- 観測数: `1`
- 代表トレース: `e3dd7576-336c-4d00-861c-5ecc738f43b3`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> CUSTOM:delivery_date_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> CUSTOM:delivery_date_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
5. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`
6. `SELECT` on `SHOP_REMOTE_ISLAND_POSTALS`
   ```sql
   SELECT {parameter} AS found FROM shop_remote_island_postals WHERE postal_code = {parameter}
   ```
   ハッシュ: `sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-7

- 観測数: `1`
- 代表トレース: `a4e16312-0046-463c-bc0f-08fb04cefb46`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:delivery_date_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 x2 -> CUSTOM:delivery_date_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
5. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`
6. `SELECT` on `SHOP_REMOTE_ISLAND_POSTALS`
   ```sql
   SELECT {parameter} AS found FROM shop_remote_island_postals WHERE postal_code = {parameter}
   ```
   ハッシュ: `sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb`
7. `SELECT` on `SHOP_WORKING_DAYS`
   ```sql
   SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE({parameter}, {parameter}) AND TO_DATE({parameter}, {parameter}) ORDER BY work_date
   ```
   ハッシュ: `sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308`
8. `SELECT` on `SHOP_WORKING_DAYS`
   ```sql
   SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE({parameter}, {parameter}) AND TO_DATE({parameter}, {parameter}) ORDER BY work_date
   ```
   ハッシュ: `sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-8

- 観測数: `1`
- 代表トレース: `c587c122-a351-4f91-93ac-1e86b2a1f644`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:delivery_date_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:delivery_date_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
5. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`
6. `SELECT` on `SHOP_REMOTE_ISLAND_POSTALS`
   ```sql
   SELECT {parameter} AS found FROM shop_remote_island_postals WHERE postal_code = {parameter}
   ```
   ハッシュ: `sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb`
7. `SELECT` on `SHOP_WORKING_DAYS`
   ```sql
   SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE({parameter}, {parameter}) AND TO_DATE({parameter}, {parameter}) ORDER BY work_date
   ```
   ハッシュ: `sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-9

- 観測数: `1`
- 代表トレース: `71a7bf67-028b-4191-900f-c3e0bd232102`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:delivery_time_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:delivery_time_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
4. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

## POST /api/orders

- エントリポイントキー: `POST /api/orders`
- 観測リクエスト数: `11`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 201 | 5 |
| 422 | 6 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-2 | 2 | 422 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_ORDERS+SHOP_ORDER_ITEMS |
| pattern-7 | 2 | 422 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS |
| pattern-1 | 1 | 201 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_ORDERS+SHOP_ORDER_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT SHOP_DELAY_PREFECTURES → SELECT SHOP_REMOTE_ISLAND_POSTALS → SELECT SHOP_WORKING_DAYS → SELECT NO_BUSINESS_TABLE → SELECT SHOP_CUSTOMERS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → DELETE SHOP_CART_ITEMS → DELETE SHOP_RESERVATION_CART_ITEMS |
| pattern-3 | 1 | 201 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_ORDERS+SHOP_ORDER_ITEMS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT NO_BUSINESS_TABLE → SELECT SHOP_CUSTOMERS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → DELETE SHOP_CART_ITEMS → DELETE SHOP_RESERVATION_CART_ITEMS |
| pattern-4 | 1 | 422 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS |
| pattern-5 | 1 | 201 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT NO_BUSINESS_TABLE → SELECT SHOP_CUSTOMERS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → DELETE SHOP_CART_ITEMS → DELETE SHOP_RESERVATION_CART_ITEMS |
| pattern-6 | 1 | 201 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT NO_BUSINESS_TABLE → SELECT SHOP_CUSTOMERS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → INSERT SHOP_ORDER_ITEMS → INSERT SHOP_ORDER_ITEMS → DELETE SHOP_CART_ITEMS → DELETE SHOP_RESERVATION_CART_ITEMS |
| pattern-8 | 1 | 422 | SELECT SHOP_CUSTOMERS |
| pattern-9 | 1 | 201 | SELECT SHOP_CUSTOMERS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCTS → SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_ADDRESSES → SELECT SHOP_CUSTOMERS → SELECT NO_BUSINESS_TABLE → SELECT SHOP_CUSTOMERS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → DELETE SHOP_CART_ITEMS → DELETE SHOP_RESERVATION_CART_ITEMS |

### 振る舞いパターン: pattern-2

- 観測数: `2`
- 代表トレース: `a80e629d-55fa-4591-929e-10189d4935d2`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_ORDERS, SHOP_ORDER_ITEMS`
   ```sql
   SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = {parameter} AND o.status <> {parameter}
               AND REPLACE(oi.product_code, {parameter}, {parameter}) = REPLACE({parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-7

- 観測数: `2`
- 代表トレース: `bb77f8bd-44b4-4fcb-8f0a-372eb2f24092`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:variety_bundle_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e x2 -> CUSTOM:variety_bundle_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-1

- 観測数: `1`
- 代表トレース: `5022fa8d-470e-4645-b608-7806069107c4`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> SELECT:SHOP_DELAY_PREFECTURES:sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb -> SELECT:SHOP_REMOTE_ISLAND_POSTALS:sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb -> SELECT:SHOP_WORKING_DAYS:sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308 -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_ORDERS, SHOP_ORDER_ITEMS`
   ```sql
   SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = {parameter} AND o.status <> {parameter}
               AND REPLACE(oi.product_code, {parameter}, {parameter}) = REPLACE({parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
7. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
8. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
9. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
10. `SELECT` on `SHOP_DELAY_PREFECTURES`
   ```sql
   SELECT {parameter} AS found FROM shop_delay_prefectures WHERE prefecture = {parameter} AND active = {parameter}
   ```
   ハッシュ: `sha256:3ee691eeb5b5e22ca2a55f615b5bd1dd65709965982b399538c017f0716c58cb`
11. `SELECT` on `SHOP_REMOTE_ISLAND_POSTALS`
   ```sql
   SELECT {parameter} AS found FROM shop_remote_island_postals WHERE postal_code = {parameter}
   ```
   ハッシュ: `sha256:6a30ddf1a9214cc279fe7a7fa4da1b4f31a39a500d7321f3c495011d9053feeb`
12. `SELECT` on `SHOP_WORKING_DAYS`
   ```sql
   SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE({parameter}, {parameter}) AND TO_DATE({parameter}, {parameter}) ORDER BY work_date
   ```
   ハッシュ: `sha256:31790135421304c8f183ec89ff9cf58b93f163854609f014adc01a76dc034308`
13. `SELECT` on ``
   ```sql
   SELECT shop_orders_seq.NEXTVAL AS id FROM dual
   ```
   ハッシュ: `sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd`
14. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
15. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
16. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
17. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a`
18. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_ORDERS | 1 |
| INSERT | SHOP_ORDER_ITEMS | 1 |
| DELETE | SHOP_CART_ITEMS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-3

- 観測数: `1`
- 代表トレース: `196a9d30-7fde-49e6-944e-62eca95dbc23`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_ORDERS+SHOP_ORDER_ITEMS:sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca -> CUSTOM:purchase_limit_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_ORDERS, SHOP_ORDER_ITEMS`
   ```sql
   SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = {parameter} AND o.status <> {parameter}
               AND REPLACE(oi.product_code, {parameter}, {parameter}) = REPLACE({parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f6bd1fc79b1c4c4685c64fc7cd7b68abbd110973b7483bb0e2d105232f4cd7ca`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
7. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
8. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
9. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
10. `SELECT` on ``
   ```sql
   SELECT shop_orders_seq.NEXTVAL AS id FROM dual
   ```
   ハッシュ: `sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd`
11. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
12. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
13. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
14. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a`
15. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_ORDERS | 1 |
| INSERT | SHOP_ORDER_ITEMS | 1 |
| DELETE | SHOP_CART_ITEMS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-4

- 観測数: `1`
- 代表トレース: `2b5deabb-3b56-46a6-b060-d8eb288d80df`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:purchase_limit_rejected -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:purchase_limit_rejected -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-5

- 観測数: `1`
- 代表トレース: `3d10f0cf-4d30-4bfb-98ca-a71395c38579`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:point_exchange_checked -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
6. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
7. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
8. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
9. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
10. `SELECT` on ``
   ```sql
   SELECT shop_orders_seq.NEXTVAL AS id FROM dual
   ```
   ハッシュ: `sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd`
11. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
12. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
13. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
14. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a`
15. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_ORDERS | 1 |
| INSERT | SHOP_ORDER_ITEMS | 1 |
| DELETE | SHOP_CART_ITEMS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-6

- 観測数: `1`
- 代表トレース: `97052737-7e15-4526-914c-c6711fc1f3c4`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> CUSTOM:variety_bundle_validated -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e x3 -> CUSTOM:variety_bundle_validated -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a x3 -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
6. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
7. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
8. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
9. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
10. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
11. `SELECT` on ``
   ```sql
   SELECT shop_orders_seq.NEXTVAL AS id FROM dual
   ```
   ハッシュ: `sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd`
12. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
13. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
14. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
15. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
16. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
17. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a`
18. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_ORDERS | 1 |
| INSERT | SHOP_ORDER_ITEMS | 3 |
| DELETE | SHOP_CART_ITEMS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |

### 振る舞いパターン: pattern-8

- 観測数: `1`
- 代表トレース: `acab9d3b-6af3-4d1f-b055-840b81a49a43`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:checkout_blocked -> STATUS:422`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:checkout_blocked -> STATUS:422`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-9

- 観測数: `1`
- 代表トレース: `4f38b361-3440-4526-80a2-7d3bcf5c634b`
- シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`
- 圧縮シグネチャ: `SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCTS:sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_ADDRESSES:sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> CUSTOM:shipping_method_selected -> CUSTOM:payment_method_filtered -> CUSTOM:free_shipping_applied -> SELECT:NO_BUSINESS_TABLE:sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd -> SELECT:SHOP_CUSTOMERS:sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> DELETE:SHOP_CART_ITEMS:sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213 -> CUSTOM:order_created -> STATUS:201`

**SQL フロー:**

1. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
2. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
3. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
4. `SELECT` on `SHOP_PRODUCTS`
   ```sql
   select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:38403e2160ee8ff41b9ec10a5bf39a1630c600b866f38f61b148deb678fe8d8e`
5. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
6. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
7. `SELECT` on `SHOP_ADDRESSES`
   ```sql
   select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:1a5608df3b1bc39aa5845dfdd676de1fa7fde63c68cc4d1ce0dd5b9d4cd7592d`
8. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
9. `SELECT` on ``
   ```sql
   SELECT shop_orders_seq.NEXTVAL AS id FROM dual
   ```
   ハッシュ: `sha256:b80844ea55b080932ef34e1f3da4ac3a8ade227eaf2c5dad5bd2b80f768287dd`
10. `SELECT` on `SHOP_CUSTOMERS`
   ```sql
   select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}
   ```
   ハッシュ: `sha256:5dca201a31114a1740f28e86cd0c182dd074dcd8f92e507fbbf766b6902b150c`
11. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
12. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
13. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:0afa11592c270fc94e61664aaabdfc1164028c161a4038eda8c1f964b7f2016a`
14. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}
   ```
   ハッシュ: `sha256:69753b901391e2dac2438ac0ff70c13c1ce102fe7e0a6746b671858603d43213`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| INSERT | SHOP_ORDERS | 1 |
| INSERT | SHOP_ORDER_ITEMS | 1 |
| DELETE | SHOP_CART_ITEMS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |

## GET /api/cart

- エントリポイントキー: `GET /api/cart`
- 観測リクエスト数: `5`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 200 | 5 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-1 | 4 | 200 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS |
| pattern-2 | 1 | 200 | SELECT SHOP_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS → SELECT SHOP_PRODUCT_COMPONENTS → SELECT SHOP_PRODUCT_COMPONENTS |

### 振る舞いパターン: pattern-1

- 観測数: `4`
- 代表トレース: `6dec8847-70d2-4874-8629-c7f864ccaa68`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> STATUS:200`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> STATUS:200`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`

**更新操作:**

_(なし — 読み取り専用パターン)_

### 振る舞いパターン: pattern-2

- 観測数: `1`
- 代表トレース: `1ec45640-ef92-4967-969c-72e4d66d9b87`
- シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCT_COMPONENTS:sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e -> CUSTOM:set_components_loaded -> SELECT:SHOP_PRODUCT_COMPONENTS:sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e -> CUSTOM:set_components_loaded -> STATUS:200`
- 圧縮シグネチャ: `SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd -> SELECT:SHOP_PRODUCT_COMPONENTS:sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e -> CUSTOM:set_components_loaded -> SELECT:SHOP_PRODUCT_COMPONENTS:sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e -> CUSTOM:set_components_loaded -> STATUS:200`

**SQL フロー:**

1. `SELECT` on `SHOP_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:868114e4bcefbfbe65da5ff192d0a10af02a55314e021d0d5a242c98c6cdc9b9`
2. `SELECT` on `SHOP_RESERVATION_CART_ITEMS, SHOP_PRODUCTS`
   ```sql
   SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id
   ```
   ハッシュ: `sha256:543622b8f49af43d9c01b52da6420c6552fc6ab184c5732be2e38c14180777fd`
3. `SELECT` on `SHOP_PRODUCT_COMPONENTS`
   ```sql
   SELECT component_code, component_name FROM shop_product_components WHERE parent_code = {parameter} ORDER BY sort_order
   ```
   ハッシュ: `sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e`
4. `SELECT` on `SHOP_PRODUCT_COMPONENTS`
   ```sql
   SELECT component_code, component_name FROM shop_product_components WHERE parent_code = {parameter} ORDER BY sort_order
   ```
   ハッシュ: `sha256:19d8f8952adb7e2200a133f85716dfe42573fd08db865053b6dd81e584abe35e`

**更新操作:**

_(なし — 読み取り専用パターン)_

## POST /api/test/reset

- エントリポイントキー: `POST /api/test/reset`
- 観測リクエスト数: `1`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 200 | 1 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-1 | 1 | 200 | DELETE SHOP_ORDER_ITEMS → DELETE SHOP_ORDERS → DELETE SHOP_RESERVATION_CART_ITEMS → DELETE SHOP_CART_ITEMS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS → INSERT SHOP_ORDERS → INSERT SHOP_ORDER_ITEMS |

### 振る舞いパターン: pattern-1

- 観測数: `1`
- 代表トレース: `f90c6f00-a9cb-4e39-ba09-b5ae3da83247`
- シグネチャ: `DELETE:SHOP_ORDER_ITEMS:sha256:6a22ab8c1d7a5cfcdd8276e1596f139ee59467e75887da32b525533df88e4ab5 -> DELETE:SHOP_ORDERS:sha256:f02d9cdaf785ff508e3eef2db31595cd8dec4b34ab76cdeb9dd066cb2fc8e12f -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:4642032f3d8a48e09b144904cac0fb7501ef37c93319a6ed7b36481e0885938a -> DELETE:SHOP_CART_ITEMS:sha256:c394d0f292848d318645ef66ec0299c325b413ff19c4082af57865fff14b8233 -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> CUSTOM:fixtures_reset -> STATUS:200`
- 圧縮シグネチャ: `DELETE:SHOP_ORDER_ITEMS:sha256:6a22ab8c1d7a5cfcdd8276e1596f139ee59467e75887da32b525533df88e4ab5 -> DELETE:SHOP_ORDERS:sha256:f02d9cdaf785ff508e3eef2db31595cd8dec4b34ab76cdeb9dd066cb2fc8e12f -> DELETE:SHOP_RESERVATION_CART_ITEMS:sha256:4642032f3d8a48e09b144904cac0fb7501ef37c93319a6ed7b36481e0885938a -> DELETE:SHOP_CART_ITEMS:sha256:c394d0f292848d318645ef66ec0299c325b413ff19c4082af57865fff14b8233 -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> INSERT:SHOP_ORDERS:sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b -> INSERT:SHOP_ORDER_ITEMS:sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a -> CUSTOM:fixtures_reset -> STATUS:200`

**SQL フロー:**

1. `DELETE` on `SHOP_ORDER_ITEMS`
   ```sql
   delete from "SHOP_ORDER_ITEMS"
   ```
   ハッシュ: `sha256:6a22ab8c1d7a5cfcdd8276e1596f139ee59467e75887da32b525533df88e4ab5`
2. `DELETE` on `SHOP_ORDERS`
   ```sql
   delete from "SHOP_ORDERS"
   ```
   ハッシュ: `sha256:f02d9cdaf785ff508e3eef2db31595cd8dec4b34ab76cdeb9dd066cb2fc8e12f`
3. `DELETE` on `SHOP_RESERVATION_CART_ITEMS`
   ```sql
   delete from "SHOP_RESERVATION_CART_ITEMS"
   ```
   ハッシュ: `sha256:4642032f3d8a48e09b144904cac0fb7501ef37c93319a6ed7b36481e0885938a`
4. `DELETE` on `SHOP_CART_ITEMS`
   ```sql
   delete from "SHOP_CART_ITEMS"
   ```
   ハッシュ: `sha256:c394d0f292848d318645ef66ec0299c325b413ff19c4082af57865fff14b8233`
5. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
6. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`
7. `INSERT` on `SHOP_ORDERS`
   ```sql
   insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})
   ```
   ハッシュ: `sha256:20dea8f3117cbc2cc7b8af64510995ee2335232325be6997171a38fea9ad0f0b`
8. `INSERT` on `SHOP_ORDER_ITEMS`
   ```sql
   insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})
   ```
   ハッシュ: `sha256:f0bf4fb357357ab6dafc4591d003eb4ddace60d3c92ae0fd25d1bb562acfcc9a`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| DELETE | SHOP_ORDER_ITEMS | 1 |
| DELETE | SHOP_ORDERS | 1 |
| DELETE | SHOP_RESERVATION_CART_ITEMS | 1 |
| DELETE | SHOP_CART_ITEMS | 1 |
| INSERT | SHOP_ORDERS | 2 |
| INSERT | SHOP_ORDER_ITEMS | 2 |

## POST /api/orders/{id}/cancel

- エントリポイントキー: `POST /api/orders/{id}/cancel`
- 観測リクエスト数: `1`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 200 | 1 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-1 | 1 | 200 | UPDATE SHOP_ORDERS |

### 振る舞いパターン: pattern-1

- 観測数: `1`
- 代表トレース: `6d96ec52-613b-4c17-a9de-bdc36461d02a`
- シグネチャ: `UPDATE:SHOP_ORDERS:sha256:7d5d9d5ee3ec262530d80aad9469bdfa06521bd11d8550ed3433f4f5e4c1e569 -> CUSTOM:order_cancelled -> STATUS:200`
- 圧縮シグネチャ: `UPDATE:SHOP_ORDERS:sha256:7d5d9d5ee3ec262530d80aad9469bdfa06521bd11d8550ed3433f4f5e4c1e569 -> CUSTOM:order_cancelled -> STATUS:200`

**SQL フロー:**

1. `UPDATE` on `SHOP_ORDERS`
   ```sql
   update "SHOP_ORDERS" set "STATUS" = {parameter} where "ID" = {parameter}
   ```
   ハッシュ: `sha256:7d5d9d5ee3ec262530d80aad9469bdfa06521bd11d8550ed3433f4f5e4c1e569`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| UPDATE | SHOP_ORDERS | 1 |

## POST /api/payments/credit/callback

- エントリポイントキー: `POST /api/payments/credit/callback`
- 観測リクエスト数: `1`
- エラー数: `0`

### ステータスコード

| ステータス | 件数 |
|---|---:|
| 200 | 1 |

### エラー

_(なし)_

### 観測実行パターン

| パターン | 件数 | ステータス | SQL フロー |
|---|---:|---|---|
| pattern-1 | 1 | 200 | UPDATE SHOP_ORDERS |

### 振る舞いパターン: pattern-1

- 観測数: `1`
- 代表トレース: `ea8cbd51-2685-440b-a4c3-bc9693faee13`
- シグネチャ: `UPDATE:SHOP_ORDERS:sha256:8959b1a03332ba51057d24b6a7f4ede5b860c6dd07cdee88ac74d362d1c7bcf6 -> CUSTOM:credit_callback_recorded -> STATUS:200`
- 圧縮シグネチャ: `UPDATE:SHOP_ORDERS:sha256:8959b1a03332ba51057d24b6a7f4ede5b860c6dd07cdee88ac74d362d1c7bcf6 -> CUSTOM:credit_callback_recorded -> STATUS:200`

**SQL フロー:**

1. `UPDATE` on `SHOP_ORDERS`
   ```sql
   update "SHOP_ORDERS" set "PAYMENT_STATUS" = {parameter} where "ID" = {parameter}
   ```
   ハッシュ: `sha256:8959b1a03332ba51057d24b6a7f4ede5b860c6dd07cdee88ac74d362d1c7bcf6`

**更新操作:**

| 操作 | テーブル | 件数 |
|---|---|---:|
| UPDATE | SHOP_ORDERS | 1 |

