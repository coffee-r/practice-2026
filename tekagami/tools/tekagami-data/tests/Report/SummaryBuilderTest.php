<?php

namespace TekagamiData\Tests\Report;

use TekagamiData\Report\SummaryBuilder;
use PHPUnit\Framework\TestCase;

class SummaryBuilderTest extends TestCase
{
    public function testBuildsEndpointCatalog()
    {
        $summary = (new SummaryBuilder())->build([
            $this->trace('t1', 'POST', '/api/cart/items', 201, [
                ['seq' => 1, 'type' => 'custom', 'label' => 'gift_attached'],
                $this->sql('INSERT', ['SHOP_CART_ITEMS'], 'sha256:insert-cart'),
            ], [
                ['op' => 'INSERT', 'table' => 'SHOP_CART_ITEMS', 'statement_hash' => 'sha256:insert-cart', 'count' => 1],
            ]),
            $this->trace('t2', 'POST', '/api/cart/items', 422, [
                ['seq' => 1, 'type' => 'custom', 'label' => 'cart_rejected'],
            ]),
            $this->trace('t3', 'POST', '/api/orders', 201, [
                $this->sql('INSERT', ['SHOP_ORDERS'], 'sha256:insert-order'),
            ], [
                ['op' => 'INSERT', 'table' => 'SHOP_ORDERS', 'statement_hash' => 'sha256:insert-order', 'count' => 1],
            ], [
                ['type' => 'capture_failure', 'message' => 'timeline truncated: limit=500'],
            ]),
        ], [
            'input_file_count' => 1,
            'source_trace_count' => 3,
            'filter' => ['entrypoint' => [], 'path' => [], 'method' => null],
        ]);

        $this->assertSame(2, $summary['observed_endpoint_count']);
        $cart = $summary['observed_endpoint_catalog'][0];

        $this->assertSame('/api/cart', $cart['group_hint']);
        $this->assertSame('POST /api/cart/items', $cart['entrypoint_key']);
        $this->assertSame(2, $cart['observed_count']);
        $this->assertSame(['201' => 1, '422' => 1], $cart['status_codes']);
        $this->assertSame(2, $cart['pattern_count']);
        $this->assertSame(2, $cart['rare_pattern_count']);
        $this->assertSame(2, $cart['custom_event_count']);
        $this->assertTrue($cart['has_write_effects']);
    }

    public function testGroupHintUsesApiSecondSegment()
    {
        $summary = (new SummaryBuilder())->build([
            $this->trace('t1', 'POST', '/api/orders/{id}/cancel', 200),
        ]);

        $this->assertSame('/api/orders', $summary['observed_endpoint_catalog'][0]['group_hint']);
    }

    private function trace($id, $method, $path, $status, array $timeline = [], array $effects = [], array $errors = [])
    {
        return [
            'trace_id' => $id,
            'started_at' => '2026-06-01T00:00:00+00:00',
            'http' => [
                'method' => $method,
                'path' => $path,
                'path_pattern' => $path,
                'status' => $status,
            ],
            'timeline' => $timeline,
            'effects' => $effects,
            'errors' => $errors,
        ];
    }

    private function sql($op, array $tables, $hash)
    {
        return [
            'seq' => 1,
            'type' => 'sql',
            'operation' => $op,
            'tables' => $tables,
            'statement_normalized' => $op . ' sample',
            'statement_hash' => $hash,
            'statement_fingerprint' => [
                'op' => $op,
                'tables' => $tables,
                'filter_columns' => [],
                'write_columns' => [],
                'fp_hash' => 'fp1:' . $hash,
            ],
        ];
    }
}
