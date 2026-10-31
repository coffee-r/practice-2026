<?php

namespace App\Http\Controllers;

use App\Http\Middleware\TekagamiMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    // -------------------------------------------------------------------------
    // Endpoints
    // -------------------------------------------------------------------------

    public function health()
    {
        $ok = DB::selectOne('SELECT 1 AS ok FROM dual');
        return response()->json(['ok' => true, 'db' => $ok !== null]);
    }

    public function reset()
    {
        try {
            DB::transaction(function () {
                DB::table('SHOP_ORDER_ITEMS')->delete();
                DB::table('SHOP_ORDERS')->delete();
                DB::table('SHOP_RESERVATION_CART_ITEMS')->delete();
                DB::table('SHOP_CART_ITEMS')->delete();

                DB::table('SHOP_ORDERS')->insert([
                    'ID' => 9001, 'CUSTOMER_ID' => 1, 'ADDRESS_ID' => 101,
                    'EMAIL' => 'first@example.test', 'SHIPPING_METHOD' => 'takuhai',
                    'PAYMENT_METHOD' => 'prepaid', 'SUBTOTAL' => 1600, 'SHIPPING_FEE' => 300,
                    'STATUS' => 'ordered', 'PAYMENT_STATUS' => 'payment_planned',
                    'DELIVERY_DATE' => null, 'DELIVERY_TIME' => null,
                    'CREATED_AT' => DB::raw('SYSTIMESTAMP'),
                ]);
                DB::table('SHOP_ORDER_ITEMS')->insert([
                    'ORDER_ID' => 9001, 'PRODUCT_CODE' => 'LIMIT_ONCE',
                    'NAME' => '一回限定商品', 'QUANTITY' => 1, 'UNIT_PRICE' => 1600, 'IS_GIFT' => 'N',
                ]);
                DB::table('SHOP_ORDERS')->insert([
                    'ID' => 9002, 'CUSTOMER_ID' => 1, 'ADDRESS_ID' => 101,
                    'EMAIL' => 'first@example.test', 'SHIPPING_METHOD' => 'takuhai',
                    'PAYMENT_METHOD' => 'prepaid', 'SUBTOTAL' => 1200, 'SHIPPING_FEE' => 300,
                    'STATUS' => 'cancelled', 'PAYMENT_STATUS' => 'payment_planned',
                    'DELIVERY_DATE' => null, 'DELIVERY_TIME' => null,
                    'CREATED_AT' => DB::raw('SYSTIMESTAMP'),
                ]);
                DB::table('SHOP_ORDER_ITEMS')->insert([
                    'ORDER_ID' => 9002, 'PRODUCT_CODE' => 'LIMIT_QTY',
                    'NAME' => '一個限定商品', 'QUANTITY' => 1, 'UNIT_PRICE' => 1200, 'IS_GIFT' => 'N',
                ]);
            });
            $this->addCustom('fixtures_reset', ['scope' => 'e2e']);
            return response()->json(['ok' => true]);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'reset');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function addCartItem(Request $request)
    {
        try {
            $cartId     = (string) $request->input('cart_id', 'guest-cart');
            $customerId = $this->nullableInt($request->input('customer_id'));
            $productCode = (string) $request->input('product_code');
            $quantity   = max(1, (int) $request->input('quantity', 1));
            $baseDate   = (string) $request->input('base_date', date('Y-m-d'));

            $product = $this->product($productCode);
            if (!$product) {
                return response()->json(['error' => 'product_not_found'], 404);
            }
            if (!$this->productOnSale($product, $baseDate)) {
                return response()->json(['error' => 'product_not_on_sale'], 422);
            }

            if ($product->IS_POINT_EXCHANGE === 'Y') {
                if ($customerId === null) {
                    $this->addCustom('point_exchange_rejected', ['reason' => 'guest']);
                    return response()->json(['error' => 'point_exchange_requires_login'], 422);
                }
                $customer = $this->customer($customerId);
                $required = (int) $product->POINT_COST * $quantity;
                if (!$customer || (int) $customer->POINTS < $required) {
                    $this->addCustom('point_exchange_rejected', ['reason' => 'insufficient_points']);
                    return response()->json(['error' => 'insufficient_points'], 422);
                }
                $this->addCustom('point_exchange_checked', ['result' => 'ok']);
            }

            [$ok, $reason] = $this->addCartItemToDb($cartId, $customerId, $product, $quantity);
            if (!$ok) {
                return response()->json(['error' => $reason], 422);
            }

            if ($product->IS_RESERVED === 'Y') {
                $this->addCustom('reservation_cart_used', ['product_code' => $productCode]);
            }

            $this->normalizeShippingProductCodes($cartId);
            if ($this->addGiftIfNeeded($cartId)) {
                $this->addCustom('gift_attached', ['cart_id' => $cartId]);
            }

            return response()->json(['cart_id' => $cartId, 'added' => $productCode], 201);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'add_cart_item');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function cart(Request $request)
    {
        try {
            $cartId = (string) $request->query('cart_id');
            $items  = $this->cartItems($cartId);
            $payloadItems = [];
            foreach ($items as $item) {
                $entry = $this->cartItemPayload($item);
                if ($item->IS_SET === 'Y') {
                    // Intentional N+1: components are read per set product to make the report show repeated SELECTs.
                    $entry['components'] = $this->componentsForSet($item->PRODUCT_CODE);
                    $this->addCustom('set_components_loaded', ['product_code' => $item->PRODUCT_CODE]);
                }
                $payloadItems[] = $entry;
            }
            return response()->json(['cart_id' => $cartId, 'items' => $payloadItems]);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'cart');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function checkoutQuote(Request $request)
    {
        try {
            $quote = $this->buildQuote($request);
            if (!$quote['ok']) {
                return response()->json($quote, 422);
            }
            return response()->json($quote);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'checkout_quote');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function createOrder(Request $request)
    {
        try {
            $cartId     = (string) $request->input('cart_id');
            $customerId = $this->nullableInt($request->input('customer_id'));
            $addressId  = $this->nullableInt($request->input('address_id'));

            if ($customerId !== null) {
                $customer = $this->customer($customerId);
                if (!$customer || $customer->EMAIL === null) {
                    $this->addCustom('checkout_blocked', ['reason' => 'missing_email']);
                    return response()->json(['ok' => false, 'error' => 'customer_email_required'], 422);
                }
            }

            $items = $this->cartItems($cartId, $customerId);
            $limit = $this->checkPurchaseLimits($customerId, $items);
            if ($limit !== true) {
                return response()->json(['ok' => false, 'error' => $limit], 422);
            }

            $points = $this->checkPointBalance($customerId, $items);
            if ($points !== true) {
                return response()->json(['ok' => false, 'error' => $points], 422);
            }

            $variety = $this->validateVarietyBundle($items);
            if ($variety !== true) {
                return response()->json(['ok' => false, 'error' => $variety], 422);
            }

            $quote = $this->buildQuote($request);
            if (!$quote['ok']) {
                return response()->json($quote, 422);
            }

            $orderId = DB::transaction(function () use ($customerId, $addressId, $quote, $items, $cartId) {
                $idRow   = DB::selectOne('SELECT shop_orders_seq.NEXTVAL AS id FROM dual');
                $orderId = (int) ($idRow->ID ?? $idRow->id);
                $paymentStatus = $quote['payment_method'] === 'credit_card' ? 'pending_3ds' : 'payment_planned';
                $customer = $customerId ? $this->customer($customerId) : null;

                DB::table('SHOP_ORDERS')->insert([
                    'ID' => $orderId, 'CUSTOMER_ID' => $customerId, 'ADDRESS_ID' => $addressId,
                    'EMAIL' => $customer ? $customer->EMAIL : null,
                    'SHIPPING_METHOD' => $quote['shipping_method'],
                    'PAYMENT_METHOD'  => $quote['payment_method'],
                    'SUBTOTAL'        => $quote['subtotal'],
                    'SHIPPING_FEE'    => $quote['shipping_fee'],
                    'STATUS'          => 'ordered',
                    'PAYMENT_STATUS'  => $paymentStatus,
                    'DELIVERY_DATE'   => $quote['delivery_date'],
                    'DELIVERY_TIME'   => $quote['delivery_time'],
                    'CREATED_AT'      => DB::raw('SYSTIMESTAMP'),
                ]);

                foreach ($items as $item) {
                    DB::table('SHOP_ORDER_ITEMS')->insert([
                        'ORDER_ID'     => $orderId,
                        'PRODUCT_CODE' => $item->PRODUCT_CODE,
                        'NAME'         => $item->NAME,
                        'QUANTITY'     => (int) $item->QUANTITY,
                        'UNIT_PRICE'   => (int) $item->UNIT_PRICE,
                        'IS_GIFT'      => $item->IS_GIFT,
                    ]);
                }

                DB::table('SHOP_CART_ITEMS')->where('CART_ID', $cartId)->delete();
                DB::table('SHOP_RESERVATION_CART_ITEMS')->where('CART_ID', $cartId)->delete();

                return $orderId;
            });

            $this->addCustom('order_created', ['order_id' => $orderId, 'payment_status' => $quote['payment_method']]);
            return response()->json(['ok' => true, 'order_id' => $orderId], 201);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'create_order');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function cancelOrder(int $orderId)
    {
        try {
            DB::table('SHOP_ORDERS')->where('ID', $orderId)->update(['STATUS' => 'cancelled']);
            $this->addCustom('order_cancelled', ['order_id' => $orderId]);
            return response()->json(['ok' => true, 'order_id' => $orderId, 'status' => 'cancelled']);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'cancel_order');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    public function creditCallback(Request $request)
    {
        try {
            $orderId = (int) $request->input('order_id');
            $success = (string) $request->input('status') === 'success';
            $status  = $success ? 'credit_success' : 'credit_failed';
            DB::table('SHOP_ORDERS')->where('ID', $orderId)->update(['PAYMENT_STATUS' => $status]);
            $this->addCustom('credit_callback_recorded', ['order_id' => $orderId, 'success' => $success]);
            return response()->json(['ok' => true, 'payment_status' => $status]);
        } catch (\Throwable $e) {
            $this->addError('app_error', 'application error', 'credit_callback');
            return response()->json(['error' => 'internal_error'], 500);
        }
    }

    // -------------------------------------------------------------------------
    // Business logic (ported from CI3 Shop controller)
    // -------------------------------------------------------------------------

    private function buildQuote(Request $request): array
    {
        $cartId          = (string) $request->input('cart_id');
        $customerId      = $this->nullableInt($request->input('customer_id'));
        $addressId       = $this->nullableInt($request->input('address_id'));
        $requestedPayment = (string) $request->input('payment_method', 'prepaid');
        $deliveryDate    = $request->input('delivery_date');
        $deliveryTime    = $request->input('delivery_time');
        $baseDate        = (string) $request->input('base_date', '2026-06-01');

        $items    = $this->cartItems($cartId, $customerId);
        $address  = $addressId ? $this->address($addressId) : null;
        $customer = $customerId ? $this->customer($customerId) : null;
        $shipping = $this->shippingMethod($items);
        $subtotal = $this->subtotal($items);

        $this->addCustom('shipping_method_selected', ['method' => $shipping]);

        $availablePayments = $this->availablePayments($shipping, $subtotal, $customer, $address);
        $this->addCustom('payment_method_filtered', ['available' => $availablePayments]);
        if (!in_array($requestedPayment, $availablePayments, true)) {
            return ['ok' => false, 'error' => 'payment_method_unavailable', 'available_payments' => $availablePayments];
        }

        $dateCheck = $this->validateDeliveryDate($shipping, $address, $deliveryDate, $baseDate);
        if ($dateCheck !== true) {
            return ['ok' => false, 'error' => $dateCheck];
        }

        if ($deliveryTime !== null && $shipping === 'yumail') {
            $this->addCustom('delivery_time_rejected', ['reason' => 'yumail']);
            return ['ok' => false, 'error' => 'delivery_time_unavailable_for_yumail'];
        }

        $shippingFee = $this->shippingFee($subtotal, $customer);
        $this->addCustom('free_shipping_applied', ['subtotal' => $subtotal, 'shipping_fee' => $shippingFee]);

        return [
            'ok'               => true,
            'cart_id'          => $cartId,
            'shipping_method'  => $shipping,
            'available_payments' => $availablePayments,
            'payment_method'   => $requestedPayment,
            'subtotal'         => $subtotal,
            'shipping_fee'     => $shippingFee,
            'delivery_date'    => $deliveryDate,
            'delivery_time'    => $deliveryTime,
        ];
    }

    private function normalizeShippingProductCodes(string $cartId): void
    {
        $items = $this->cartItems($cartId);
        $nonGift = array_values(array_filter($items, fn($i) => $i->IS_GIFT !== 'Y'));

        if (count($nonGift) === 1) {
            $item    = $nonGift[0];
            $product = $this->product($item->PRODUCT_CODE);
            if ($product && $product->YUMAIL_SINGLE_ELIGIBLE === 'Y' && strpos($item->PRODUCT_CODE, 'P') !== 0) {
                $prefixed = 'P' . $item->PRODUCT_CODE;
                if ($this->product($prefixed)) {
                    $newProduct = $this->product($prefixed);
                    DB::table('SHOP_CART_ITEMS')
                        ->where('CART_ID', $cartId)
                        ->where('PRODUCT_CODE', $item->PRODUCT_CODE)
                        ->update(['PRODUCT_CODE' => $prefixed, 'UNIT_PRICE' => (int) $newProduct->PRICE]);
                    $this->addCustom('yumail_product_code_swapped', ['from' => $item->PRODUCT_CODE, 'to' => $prefixed]);
                }
            }
            return;
        }

        foreach ($nonGift as $item) {
            if (strpos($item->PRODUCT_CODE, 'P') === 0) {
                $plain = substr($item->PRODUCT_CODE, 1);
                if ($this->product($plain)) {
                    $plainProduct = $this->product($plain);
                    DB::table('SHOP_CART_ITEMS')
                        ->where('CART_ID', $cartId)
                        ->where('PRODUCT_CODE', $item->PRODUCT_CODE)
                        ->update(['PRODUCT_CODE' => $plain, 'UNIT_PRICE' => (int) $plainProduct->PRICE]);
                    $this->addCustom('yumail_product_code_reverted', ['from' => $item->PRODUCT_CODE, 'to' => $plain]);
                }
            }
        }
    }

    private function shippingMethod(array $items): string
    {
        $nonGift = array_values(array_filter($items, fn($i) => $i->IS_GIFT !== 'Y'));
        return count($nonGift) === 1 && strpos($nonGift[0]->PRODUCT_CODE, 'P') === 0 ? 'yumail' : 'takuhai';
    }

    private function availablePayments(string $shipping, int $subtotal, $customer, $address): array
    {
        $payments = ['prepaid', 'credit_card'];
        if ($shipping !== 'yumail' && $address && $address->IS_SELF === 'Y') {
            $payments[] = 'cod';
        }
        if ($customer && (int) $customer->CREDIT_REMAINING >= $subtotal) {
            $payments[] = 'deferred';
        }
        sort($payments);
        return $payments;
    }

    private function validateDeliveryDate(string $shipping, $address, $deliveryDate, string $baseDate)
    {
        if ($deliveryDate === null) {
            return true;
        }
        if ($shipping === 'yumail') {
            $this->addCustom('delivery_date_rejected', ['reason' => 'yumail']);
            return 'delivery_date_unavailable_for_yumail';
        }
        if ($address && $this->delayedPrefecture($address->PREFECTURE)) {
            $this->addCustom('delivery_date_rejected', ['reason' => 'delayed_prefecture']);
            return 'delivery_date_unavailable_for_delayed_area';
        }
        if ($address && $this->remoteIslandPostalCode($address->POSTAL_CODE)) {
            $this->addCustom('delivery_date_rejected', ['reason' => 'remote_island']);
            return 'delivery_date_unavailable_for_remote_island';
        }
        $window = $this->deliveryWindow($baseDate);
        if ($deliveryDate < $window['min'] || $deliveryDate > $window['max']) {
            $this->addCustom('delivery_date_rejected', ['reason' => 'outside_window', 'min' => $window['min'], 'max' => $window['max']]);
            return 'delivery_date_outside_window';
        }
        return true;
    }

    private function deliveryWindow(string $baseDate): array
    {
        $min = date('Y-m-d', strtotime($baseDate . ' +3 day'));
        $max = date('Y-m-d', strtotime($baseDate . ' +14 day'));
        while (true) {
            $days = DB::select(
                "SELECT work_date, is_working FROM shop_working_days WHERE work_date BETWEEN TO_DATE(?, 'YYYY-MM-DD') AND TO_DATE(?, 'YYYY-MM-DD') ORDER BY work_date",
                [date('Y-m-d', strtotime($baseDate . ' +1 day')), $min]
            );
            $nonWorking = count(array_filter($days, fn($d) => $d->IS_WORKING !== 'Y'));
            $nextMin = date('Y-m-d', strtotime($baseDate . ' +' . (3 + $nonWorking) . ' day'));
            if ($nextMin === $min) {
                break;
            }
            $min = $nextMin;
        }
        return ['min' => $min, 'max' => $max];
    }

    private function shippingFee(int $subtotal, $customer): int
    {
        $threshold = $customer && $customer->IS_FIRST_TIME === 'Y' ? 3000 : 7000;
        return $subtotal >= $threshold ? 0 : 300;
    }

    private function subtotal(array $items): int
    {
        $sum = 0;
        foreach ($items as $item) {
            $sum += (int) $item->UNIT_PRICE * (int) $item->QUANTITY;
        }
        $varietyQty = array_sum(array_map(fn($i) => $i->IS_VARIETY === 'Y' ? (int) $i->QUANTITY : 0, $items));
        return $varietyQty === 3 ? 3980 : $sum;
    }

    private function checkPurchaseLimits($customerId, array $items)
    {
        if ($customerId === null) {
            return true;
        }
        foreach ($items as $item) {
            $product = $this->product($item->PRODUCT_CODE);
            if (!$product || $item->IS_GIFT === 'Y') {
                continue;
            }
            if ($product->ONE_PER_CUSTOMER_QTY === 'Y' && (int) $item->QUANTITY > 1) {
                $this->addCustom('purchase_limit_rejected', ['type' => 'one_quantity']);
                return 'one_quantity_limit_exceeded';
            }
            if ($product->ONE_PER_CUSTOMER_QTY === 'Y' || $product->ONE_PER_CUSTOMER_ONCE === 'Y') {
                $prior = $this->priorActiveOrdersForProduct($customerId, $item->PRODUCT_CODE);
                if (count($prior) > 0) {
                    $type = $product->ONE_PER_CUSTOMER_ONCE === 'Y' ? 'one_time' : 'one_quantity';
                    $this->addCustom('purchase_limit_rejected', ['type' => $type]);
                    return $product->ONE_PER_CUSTOMER_ONCE === 'Y' ? 'one_time_limit_exceeded' : 'one_quantity_limit_exceeded';
                }
                $this->addCustom('purchase_limit_checked', ['result' => 'ok']);
            }
        }
        return true;
    }

    private function checkPointBalance($customerId, array $items)
    {
        $required = 0;
        foreach ($items as $item) {
            if ($item->IS_POINT_EXCHANGE === 'Y') {
                $required += (int) $item->POINT_COST * (int) $item->QUANTITY;
            }
        }
        if ($required === 0) {
            return true;
        }
        if ($customerId === null) {
            $this->addCustom('point_exchange_rejected', ['reason' => 'guest_order']);
            return 'point_exchange_requires_login';
        }
        $customer = $this->customer($customerId);
        if (!$customer || (int) $customer->POINTS < $required) {
            $this->addCustom('point_exchange_rejected', ['reason' => 'insufficient_points_order']);
            return 'insufficient_points';
        }
        $this->addCustom('point_exchange_checked', ['result' => 'ok_at_order']);
        return true;
    }

    private function validateVarietyBundle(array $items)
    {
        $varietyQty   = 0;
        $nonVarietyQty = 0;
        foreach ($items as $item) {
            if ($item->IS_GIFT === 'Y') {
                continue;
            }
            if ($item->IS_VARIETY === 'Y') {
                $varietyQty += (int) $item->QUANTITY;
            } else {
                $nonVarietyQty += (int) $item->QUANTITY;
            }
        }
        if ($varietyQty === 0) {
            return true;
        }
        if ($varietyQty !== 3 || $nonVarietyQty > 0) {
            $this->addCustom('variety_bundle_rejected', ['variety_qty' => $varietyQty, 'non_variety_qty' => $nonVarietyQty]);
            return 'invalid_variety_bundle';
        }
        $this->addCustom('variety_bundle_validated', ['price' => 3980]);
        return true;
    }

    // -------------------------------------------------------------------------
    // Data access helpers (produces Eloquent/QueryBuilder SQL for layer-A diff)
    // -------------------------------------------------------------------------

    private function customer(int $id)
    {
        return DB::table('SHOP_CUSTOMERS')->where('ID', $id)->first();
    }

    private function address(int $id)
    {
        return DB::table('SHOP_ADDRESSES')->where('ID', $id)->first();
    }

    private function product(string $code)
    {
        return DB::table('SHOP_PRODUCTS')->where('CODE', $code)->first();
    }

    private function cartItems(string $cartId, ?int $customerId = null): array
    {
        $regular = DB::select(
            'SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = ? ORDER BY ci.id',
            [$cartId]
        );
        $reserved = DB::select(
            'SELECT ci.cart_id, ci.customer_id, ci.product_code, ci.quantity, ci.unit_price, ci.is_gift,
                    p.name, p.price, p.is_set, p.is_reserved, p.is_point_exchange, p.point_cost, p.is_variety, p.variety_group
             FROM shop_reservation_cart_items ci JOIN shop_products p ON p.code = ci.product_code
             WHERE ci.cart_id = ? ORDER BY ci.id',
            [$cartId]
        );
        return array_merge($regular, $reserved);
    }

    private function addCartItemToDb(string $cartId, ?int $customerId, $product, int $quantity): array
    {
        $table = $product->IS_RESERVED === 'Y' ? 'SHOP_RESERVATION_CART_ITEMS' : 'SHOP_CART_ITEMS';
        DB::table($table)->insert([
            'CART_ID'      => $cartId,
            'CUSTOMER_ID'  => $customerId,
            'PRODUCT_CODE' => $product->CODE,
            'QUANTITY'     => $quantity,
            'UNIT_PRICE'   => (int) $product->PRICE,
            'IS_GIFT'      => 'N',
            'CREATED_AT'   => DB::raw('SYSTIMESTAMP'),
        ]);
        return [true, null];
    }

    private function addGiftIfNeeded(string $cartId): bool
    {
        $trigger = DB::selectOne(
            "SELECT 1 AS found FROM shop_cart_items ci JOIN shop_products p ON p.code = ci.product_code WHERE ci.cart_id = ? AND p.gift_trigger = 'Y' AND ROWNUM = 1",
            [$cartId]
        );
        if (!$trigger) {
            return false;
        }
        $existing = DB::selectOne(
            "SELECT 1 AS found FROM shop_cart_items WHERE cart_id = ? AND product_code = 'GIFT_MINI' AND ROWNUM = 1",
            [$cartId]
        );
        if ($existing) {
            return false;
        }
        DB::statement(
            "INSERT INTO shop_cart_items (cart_id, customer_id, product_code, quantity, unit_price, is_gift, created_at)
             SELECT ?, MIN(customer_id), 'GIFT_MINI', 1, 0, 'Y', SYSTIMESTAMP FROM shop_cart_items WHERE cart_id = ?",
            [$cartId, $cartId]
        );
        return true;
    }

    private function priorActiveOrdersForProduct(int $customerId, string $productCode): array
    {
        return DB::select(
            "SELECT oi.product_code, o.status
             FROM shop_orders o JOIN shop_order_items oi ON oi.order_id = o.id
             WHERE o.customer_id = ? AND o.status <> 'cancelled'
               AND REPLACE(oi.product_code, 'P', '') = REPLACE(?, 'P', '')",
            [$customerId, $productCode]
        );
    }

    private function componentsForSet(string $productCode): array
    {
        return DB::select(
            'SELECT component_code, component_name FROM shop_product_components WHERE parent_code = ? ORDER BY sort_order',
            [$productCode]
        );
    }

    private function delayedPrefecture(string $prefecture): bool
    {
        return (bool) DB::selectOne(
            "SELECT 1 AS found FROM shop_delay_prefectures WHERE prefecture = ? AND active = 'Y'",
            [$prefecture]
        );
    }

    private function remoteIslandPostalCode(string $postalCode): bool
    {
        return (bool) DB::selectOne(
            'SELECT 1 AS found FROM shop_remote_island_postals WHERE postal_code = ?',
            [$postalCode]
        );
    }

    // -------------------------------------------------------------------------
    // Tekagami helpers
    // -------------------------------------------------------------------------

    private function addCustom(string $label, $data = null): void
    {
        if (TekagamiMiddleware::$current) {
            TekagamiMiddleware::$current->addCustom($label, $data);
        }
    }

    private function addError(string $type, ?string $message = null, ?string $at = null): void
    {
        if (TekagamiMiddleware::$current) {
            TekagamiMiddleware::$current->addError($type, $message, $at);
        }
    }

    // -------------------------------------------------------------------------
    // Utilities
    // -------------------------------------------------------------------------

    private function productOnSale($product, string $baseDate): bool
    {
        if ($product->SALE_START_DATE !== null && $baseDate < $product->SALE_START_DATE) {
            return false;
        }
        if ($product->SALE_END_DATE !== null && $baseDate > $product->SALE_END_DATE) {
            return false;
        }
        return true;
    }

    private function cartItemPayload($item): array
    {
        return [
            'product_code' => $item->PRODUCT_CODE,
            'name'         => $item->NAME,
            'quantity'     => (int) $item->QUANTITY,
            'unit_price'   => (int) $item->UNIT_PRICE,
            'is_gift'      => $item->IS_GIFT === 'Y',
        ];
    }

    private function nullableInt($value): ?int
    {
        return $value === null || $value === '' ? null : (int) $value;
    }
}
