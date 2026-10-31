<?php

namespace TekagamiData\Tests\Report;

use TekagamiData\Report\TraceFilter;
use PHPUnit\Framework\TestCase;

class TraceFilterTest extends TestCase
{
    public function testPathPatternsAreOrConditions()
    {
        $traces = [
            $this->trace('GET', '/api/cart'),
            $this->trace('GET', '/api/products'),
            $this->trace('GET', '/api/orders'),
        ];

        $filtered = (new TraceFilter([
            'path' => ['/api/cart*', '/api/products*'],
        ]))->filter($traces);

        $this->assertCount(2, $filtered);
        $this->assertSame('/api/cart', $filtered[0]['http']['path_pattern']);
        $this->assertSame('/api/products', $filtered[1]['http']['path_pattern']);
    }

    public function testMethodIsAndCondition()
    {
        $traces = [
            $this->trace('GET', '/api/cart'),
            $this->trace('POST', '/api/cart/items'),
            $this->trace('POST', '/api/orders'),
        ];

        $filtered = (new TraceFilter([
            'method' => 'POST',
            'path' => ['/api/cart*'],
        ]))->filter($traces);

        $this->assertCount(1, $filtered);
        $this->assertSame('POST', $filtered[0]['http']['method']);
        $this->assertSame('/api/cart/items', $filtered[0]['http']['path_pattern']);
    }

    public function testSlashStarAlsoMatchesBasePath()
    {
        $traces = [
            $this->trace('GET', '/api/cart'),
            $this->trace('GET', '/api/cart/items'),
            $this->trace('GET', '/api/orders'),
        ];

        $filtered = (new TraceFilter([
            'path' => ['/api/cart/*'],
        ]))->filter($traces);

        $this->assertCount(2, $filtered);
    }

    public function testEntrypointMatchesMethodAndPathPattern()
    {
        $traces = [
            $this->trace('GET', '/api/cart'),
            $this->trace('POST', '/api/cart/items'),
        ];

        $filtered = (new TraceFilter([
            'entrypoint' => ['POST /api/cart/*'],
        ]))->filter($traces);

        $this->assertCount(1, $filtered);
        $this->assertSame('POST', $filtered[0]['http']['method']);
    }

    private function trace($method, $path)
    {
        return [
            'trace_id' => $method . '-' . $path,
            'started_at' => '2026-06-01T00:00:00+00:00',
            'http' => [
                'method' => $method,
                'path' => $path,
                'path_pattern' => $path,
                'status' => 200,
            ],
            'timeline' => [],
            'effects' => [],
            'errors' => [],
        ];
    }
}
