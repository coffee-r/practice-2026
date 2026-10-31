<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use CoffeeR\Tekagami\Collector;
use CoffeeR\Tekagami\Config;
use CoffeeR\Tekagami\Flow;
use CoffeeR\Tekagami\Http\HttpInput;
use CoffeeR\Tekagami\Http\HttpResponse;
use CoffeeR\Tekagami\Sink\JsonlSink;
use CoffeeR\Tekagami\Sink\NullSink;
use CoffeeR\Tekagami\Sql\OracleSqlAnalyzer;
use Symfony\Component\HttpFoundation\Response;

class TekagamiMiddleware
{
    public static ?Collector $current = null;

    public function handle(Request $request, Closure $next): Response
    {
        if (env('TEKAGAMI_ENABLED', 'true') === 'false') {
            return $next($request);
        }

        $config = new Config(env('TEKAGAMI_SECRET') ?: null, [
            'enabled'          => true,
            'keepKeys'         => [
                'scenario', 'product_code', 'payment_method',
                'shipping_method', 'prefecture', 'delivery_date',
                'delivery_time', 'status',
            ],
            'keepHeaderKeys'   => [],
            'sqlValueAllowlist' => [
                'shop_orders.status', 'shop_orders.payment_status', 'shop_orders.payment_method',
                'shop_orders.shipping_method', 'shop_cart_items.product_code',
                'shop_reservation_cart_items.product_code',
            ],
            'captureText'      => false,
            'maxTimelineSize'  => 700,
        ]);

        $logPath = env('TEKAGAMI_LOG');
        $sink    = $logPath ? new JsonlSink($logPath) : new NullSink();

        $collector = new Collector($config, $sink, new OracleSqlAnalyzer());

        $path    = $request->getPathInfo();
        $method  = $request->method();
        $pattern = $this->pathPattern($method, $path);

        $http              = new HttpInput($method, $path);
        $http->pathPattern = $pattern;
        $http->queryRaw    = $request->query->all();
        $http->requestHeadersRaw = $request->headers->all();
        $http->requestRaw  = $request->all();

        $flowId  = $request->header('X-Tekagami-Flow');
        $flowSeq = $request->header('X-Tekagami-Seq');
        $flow    = $flowId ? new Flow($flowId, $flowSeq !== null ? (int) $flowSeq : null) : null;
        $collector->start($http, $flow);

        // DB::listen で SQL を採取
        DB::listen(function ($query) use ($collector) {
            $collector->addSql($query->sql, $query->bindings, ['source' => 'shop']);
        });

        self::$current = $collector;

        $response = $next($request);

        $httpResponse              = new HttpResponse();
        $httpResponse->status      = $response->getStatusCode();
        $httpResponse->responseKind = 'json';
        $httpResponse->contentType = $response->headers->get('Content-Type', 'application/json');
        $httpResponse->responseHeadersRaw = $response->headers->all();
        $httpResponse->responseBodyRaw = json_decode($response->getContent(), true) ?? [];
        $collector->finish($httpResponse);

        self::$current = null;

        return $response;
    }

    private function pathPattern(string $method, string $path): ?string
    {
        $patterns = [
            'GET /api/cart'                      => '/api/cart',
            'POST /api/cart/items'               => '/api/cart/items',
            'POST /api/checkout/quote'           => '/api/checkout/quote',
            'POST /api/orders'                   => '/api/orders',
            'POST /api/payments/credit/callback' => '/api/payments/credit/callback',
            'POST /api/test/reset'               => '/api/test/reset',
        ];

        $key = $method . ' ' . $path;
        if (isset($patterns[$key])) {
            return $patterns[$key];
        }
        if ($method === 'POST' && preg_match('#^/api/orders/[0-9]+/cancel$#', $path)) {
            return '/api/orders/{id}/cancel';
        }
        return null;
    }
}
