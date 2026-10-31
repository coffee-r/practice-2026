# tekagami diff report

- legacy: `2026-06-01T22:07:39+00:00` (69 traces)
- target: `2026-06-01T22:07:39+00:00` (69 traces)

## POST /api/cart/items

### Patterns: Added (7)

- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×5
- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> SELECT:SHOP_CART_ITEMS:S7 -> INSERT:SHOP_CART_ITEMS:S8 -> CUSTOM:gift_attached -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - effect: INSERT SHOP_CART_ITEMS ×1
  - custom: `gift_attached`
- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×21
- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 x2 -> UPDATE:SHOP_CART_ITEMS:S6 -> CUSTOM:yumail_product_code_reverted -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - effect: UPDATE SHOP_CART_ITEMS ×1
  - custom: `yumail_product_code_reverted`
- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 x3 -> UPDATE:SHOP_CART_ITEMS:S6 -> CUSTOM:yumail_product_code_swapped -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×2
  - effect: UPDATE SHOP_CART_ITEMS ×2
  - custom: `yumail_product_code_swapped`
- sig: `SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_RESERVATION_CART_ITEMS:S10 -> CUSTOM:reservation_cart_used -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_RESERVATION_CART_ITEMS ×1
  - custom: `reservation_cart_used`
- sig: `SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CUSTOMERS:S9 -> CUSTOM:point_exchange_checked -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - custom: `point_exchange_checked`

### Patterns: Removed (7)

- sig: `SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CUSTOMERS:S9 -> CUSTOM:point_exchange_checked -> SELECT:SHOP_PRODUCTS:S1 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - custom: `point_exchange_checked`
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×5
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> SELECT:SHOP_CART_ITEMS:S7 -> INSERT:SHOP_CART_ITEMS:S8 -> CUSTOM:gift_attached -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - effect: INSERT SHOP_CART_ITEMS ×1
  - custom: `gift_attached`
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×21
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 x2 -> UPDATE:SHOP_CART_ITEMS:S6 -> CUSTOM:yumail_product_code_reverted -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×1
  - effect: UPDATE SHOP_CART_ITEMS ×1
  - custom: `yumail_product_code_reverted`
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_CART_ITEMS:S2 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 x3 -> UPDATE:SHOP_CART_ITEMS:S6 -> CUSTOM:yumail_product_code_swapped -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_CART_ITEMS ×2
  - effect: UPDATE SHOP_CART_ITEMS ×2
  - custom: `yumail_product_code_swapped`
- sig: `SELECT:SHOP_PRODUCTS:S1 x2 -> INSERT:SHOP_RESERVATION_CART_ITEMS:S10 -> CUSTOM:reservation_cart_used -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S3 -> SELECT:SHOP_RESERVATION_CART_ITEMS+SHOP_PRODUCTS:S4 -> SELECT:SHOP_PRODUCTS:S1 -> SELECT:SHOP_CART_ITEMS+SHOP_PRODUCTS:S5 -> STATUS:201`
  - statuses: `201`
  - effect: INSERT SHOP_RESERVATION_CART_ITEMS ×1
  - custom: `reservation_cart_used`

## Meaning-Near-Matches (同一 fp・異 SQL)

> layer-B fp が一致するが SQL 文字列が異なる候補。等価保証ではなく意味近似一致候補です。

| fp | legacy SQL | target SQL |
|---|---|---|
| `fp1:1439b3714d6ac758b1cccdfd7a7a768ef06bf5a6f1ed25bf47a36477265046db` | `UPDATE shop_orders SET status = {parameter} WHERE id = {parameter}` | `update "SHOP_ORDERS" set "STATUS" = {parameter} where "ID" = {parameter}` |
| `fp1:18b19582069493208b1e29cef3fbdaa29ea23386a478556d7d47d2d75adfc6bf` | `DELETE FROM shop_reservation_cart_items` | `delete from "SHOP_RESERVATION_CART_ITEMS"` |
| `fp1:18c32d50acbe8aaef830fbefd65066ae0908f3db073565157318ebfabe97012b` | `INSERT INTO shop_cart_items (cart_id, customer_id, product_code, quantity, unit_price, is_gift, created_at)
             VALUES ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` | `insert into "SHOP_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` |
| `fp1:191445b7af47335b0148f8979cb58f7a5eef0f82bb4d3c4ffb9d1223cb48580e` | `UPDATE shop_cart_items SET product_code = {parameter}, unit_price = {parameter} WHERE cart_id = {parameter} AND product_code = {parameter}` | `update "SHOP_CART_ITEMS" set "PRODUCT_CODE" = {parameter}, "UNIT_PRICE" = {parameter} where "CART_ID" = {parameter} and "PRODUCT_CODE" = {parameter}` |
| `fp1:21d20e12737a3ed6cfff076442499a841c84f646de2834a7143dc670e4a268bd` | `INSERT INTO shop_order_items (order_id, product_code, name, quantity, unit_price, is_gift)
                 VALUES ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}) / INSERT INTO shop_order_items (order_id, product_code, name, quantity, unit_price, is_gift)
             VALUES ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})` | `insert into "SHOP_ORDER_ITEMS" ("ORDER_ID", "PRODUCT_CODE", "NAME", "QUANTITY", "UNIT_PRICE", "IS_GIFT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter})` |
| `fp1:2b6387587c419e6a5893bd689b62a3c547927b14fe209220855e12d5fac954ab` | `INSERT INTO shop_orders (id, customer_id, address_id, email, shipping_method, payment_method, subtotal, shipping_fee, status, payment_status, delivery_date, delivery_time, created_at)
             VALUES ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` | `insert into "SHOP_ORDERS" ("ID", "CUSTOMER_ID", "ADDRESS_ID", "EMAIL", "SHIPPING_METHOD", "PAYMENT_METHOD", "SUBTOTAL", "SHIPPING_FEE", "STATUS", "PAYMENT_STATUS", "DELIVERY_DATE", "DELIVERY_TIME", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` |
| `fp1:3ed410ae0a5c481631c7583d3bd8f51edc9d6f3c61905c369198cac117ab1edc` | `SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift, p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter}
             ORDER BY ci.id` | `SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id` |
| `fp1:5f616f9af2817764e48a0eaa448455e611f2130cadbe88cfee85196d09ded7d7` | `SELECT * FROM shop_addresses WHERE id = {parameter}` | `select * from (select * from "SHOP_ADDRESSES" where "ID" = {parameter}) where rownum = {parameter}` |
| `fp1:7183b3030d97832789a36dfac4f37ca6662d320861a95af46c0b2f17dfe4dc2d` | `DELETE FROM shop_cart_items WHERE cart_id = {parameter}` | `delete from "SHOP_CART_ITEMS" where "CART_ID" = {parameter}` |
| `fp1:724a0c1f2abc10c22ce9a775b85eb057c452e1559ccee7ee8002cca0a6353079` | `DELETE FROM shop_cart_items` | `delete from "SHOP_CART_ITEMS"` |
| `fp1:8a2fde1712635eecb0c1e89953ca49fbd549d6f8fc707dd96639e0f759110ad5` | `DELETE FROM shop_reservation_cart_items WHERE cart_id = {parameter}` | `delete from "SHOP_RESERVATION_CART_ITEMS" where "CART_ID" = {parameter}` |
| `fp1:900533886b3970b5dbe527c4f8cd3fcb79bd45e0ea51a4764e9672b408473db2` | `SELECT * FROM shop_customers WHERE id = {parameter}` | `select * from (select * from "SHOP_CUSTOMERS" where "ID" = {parameter}) where rownum = {parameter}` |
| `fp1:a0a3c5f8cd8da657e2744ed551b108f651d24d76d1700684f37bb052be1b4a2e` | `DELETE FROM shop_orders` | `delete from "SHOP_ORDERS"` |
| `fp1:a5321c8c7c70244c621213c270e5cfc2fe228a0cb4ee096eb1553b4479c09e12` | `UPDATE shop_orders SET payment_status = {parameter} WHERE id = {parameter}` | `update "SHOP_ORDERS" set "PAYMENT_STATUS" = {parameter} where "ID" = {parameter}` |
| `fp1:ae37783f6926a826d4836ed6cc46dfc9ae68e778bded3dda621533c022335abc` | `SELECT * FROM shop_products WHERE code = {parameter}` | `select * from (select * from "SHOP_PRODUCTS" where "CODE" = {parameter}) where rownum = {parameter}` |
| `fp1:c4ad9051aa0abdc2681b3e3e1fa57a691eb541c1b20fd6c5655b739737275bc4` | `SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift, p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter}
             ORDER BY ci.id` | `SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = {parameter} ORDER BY ci.id` |
| `fp1:d55febeabd530f4194135ae5c3ffc76ede11a74af220ca5b2854ca3703a42233` | `DELETE FROM shop_order_items` | `delete from "SHOP_ORDER_ITEMS"` |
| `fp1:e539a972031e6536f8e4dd9aca030de8d46434e74822b836ae67a5e28dbadbd2` | `SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = {parameter}
               AND o.status <> {parameter}
               AND REPLACE(oi.product_code, {parameter}, {parameter}) = REPLACE({parameter}, {parameter}, {parameter})` | `SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = {parameter} AND o.status <> {parameter}
               AND REPLACE(oi.product_code, {parameter}, {parameter}) = REPLACE({parameter}, {parameter}, {parameter})` |
| `fp1:e8e00d6422955abbde6e352c11a4396a377862d989cc4608571c85062a835166` | `INSERT INTO shop_reservation_cart_items (cart_id, customer_id, product_code, quantity, unit_price, is_gift, created_at)
             VALUES ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` | `insert into "SHOP_RESERVATION_CART_ITEMS" ("CART_ID", "CUSTOMER_ID", "PRODUCT_CODE", "QUANTITY", "UNIT_PRICE", "IS_GIFT", "CREATED_AT") values ({parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {parameter}, {db_time})` |

