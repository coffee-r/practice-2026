<?php

namespace TekagamiData\Tests\Report;

use TekagamiData\Report\DiffEngine;
use PHPUnit\Framework\TestCase;

class DiffEngineTest extends TestCase
{
    private function engine()
    {
        return new DiffEngine();
    }

    private function loadFixture($name)
    {
        $path = __DIR__ . '/../fixtures/' . $name;
        $data = json_decode(file_get_contents($path), true);
        $this->assertIsArray($data, "Failed to load fixture: $name");
        return $data;
    }

    // -------------------------------------------------------------------------
    // 基本構造
    // -------------------------------------------------------------------------

    public function testIdenticalExportsProduceEmptyDiff()
    {
        $export = $this->loadFixture('diff-legacy.json');
        $diff   = $this->engine()->diff($export, $export);

        $this->assertSame([], $diff['entrypoints']['added']);
        $this->assertSame([], $diff['entrypoints']['removed']);
        $this->assertSame([], $diff['entrypoints']['changed']);
        $this->assertSame([], $diff['meaning_near_matches']);
    }

    public function testEmptyExportsProduceEmptyDiff()
    {
        $empty = ['generated_at' => null, 'trace_count' => 0, 'observed_entrypoints' => [], 'sql_dictionary' => []];
        $diff  = $this->engine()->diff($empty, $empty);

        $this->assertSame([], $diff['entrypoints']['added']);
        $this->assertSame([], $diff['entrypoints']['removed']);
        $this->assertSame([], $diff['entrypoints']['changed']);
        $this->assertSame([], $diff['meaning_near_matches']);
    }

    public function testMetaFields()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $this->assertSame('2026-05-31T21:00:00+00:00', $diff['legacy_generated_at']);
        $this->assertSame('2026-06-01T09:00:00+00:00', $diff['target_generated_at']);
        $this->assertSame(10, $diff['legacy_trace_count']);
        $this->assertSame(12, $diff['target_trace_count']);
    }

    // -------------------------------------------------------------------------
    // entrypoint 追加/消失
    // -------------------------------------------------------------------------

    public function testEntrypointAdded()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $this->assertContains('GET /api/health', $diff['entrypoints']['added']);
        $this->assertEmpty($diff['entrypoints']['removed']);
    }

    public function testEntrypointRemoved()
    {
        // legacy にあって target にない
        $legacy = $this->loadFixture('diff-target.json');
        $target = $this->loadFixture('diff-legacy.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $this->assertContains('GET /api/health', $diff['entrypoints']['removed']);
        $this->assertEmpty($diff['entrypoints']['added']);
    }

    // -------------------------------------------------------------------------
    // status_codes diff
    // -------------------------------------------------------------------------

    public function testStatusCodesCountChange()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $cartEp = $this->findChangedEntrypoint($diff, 'GET /api/cart');
        $this->assertNotNull($cartEp, 'GET /api/cart should be in changed');

        $changed = $cartEp['status_codes']['changed'];
        $this->assertCount(1, $changed);
        $this->assertSame('200', $changed[0]['code']);
        $this->assertSame(5, $changed[0]['legacy_count']);
        $this->assertSame(6, $changed[0]['target_count']);
    }

    public function testStatusCodesAddedAndRemoved()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $cartItemsEp = $this->findChangedEntrypoint($diff, 'POST /api/cart/items');
        $this->assertNotNull($cartItemsEp);

        $sc = $cartItemsEp['status_codes'];
        // 404 が消えて 422 が増えた
        $this->assertCount(1, $sc['removed']);
        $this->assertSame('404', $sc['removed'][0]['code']);
        $this->assertCount(1, $sc['added']);
        $this->assertSame('422', $sc['added'][0]['code']);
    }

    // -------------------------------------------------------------------------
    // パターン追加/消失
    // -------------------------------------------------------------------------

    public function testPatternRemoved()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $ep = $this->findChangedEntrypoint($diff, 'POST /api/cart/items');
        $this->assertNotNull($ep);

        // 404 パターンが消えている
        $removedSigs = array_column($ep['patterns']['removed'], 'signature');
        $this->assertNotEmpty($removedSigs);
        $found = false;
        foreach ($removedSigs as $sig) {
            if (strpos($sig, 'STATUS:404') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, '404 pattern should be in removed');
    }

    public function testPatternAdded()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $ep = $this->findChangedEntrypoint($diff, 'POST /api/cart/items');
        $this->assertNotNull($ep);

        // 422 パターンが追加されている
        $addedSigs = array_column($ep['patterns']['added'], 'signature');
        $found = false;
        foreach ($addedSigs as $sig) {
            if (strpos($sig, 'STATUS:422') !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, '422 pattern should be in added');
    }

    // -------------------------------------------------------------------------
    // effects diff
    // -------------------------------------------------------------------------

    public function testEffectsCountChange()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $ep = $this->findChangedEntrypoint($diff, 'POST /api/cart/items');
        $this->assertNotNull($ep);

        $changedPats = $ep['patterns']['changed'];
        $this->assertNotEmpty($changedPats);
        $effectsChanged = $changedPats[0]['effects']['changed'];
        $this->assertCount(1, $effectsChanged);
        $this->assertSame('INSERT', $effectsChanged[0]['op']);
        $this->assertSame('SHOP_CART_ITEMS', $effectsChanged[0]['table']);
        $this->assertSame(3, $effectsChanged[0]['legacy_count']);
        $this->assertSame(4, $effectsChanged[0]['target_count']);
    }

    // -------------------------------------------------------------------------
    // custom event diff
    // -------------------------------------------------------------------------

    public function testCustomEventAdded()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $ep = $this->findChangedEntrypoint($diff, 'GET /api/cart');
        $this->assertNotNull($ep);

        $changedPats = $ep['patterns']['changed'];
        $this->assertNotEmpty($changedPats);
        $this->assertContains('gift_attached', $changedPats[0]['custom_events']['added']);
        $this->assertEmpty($changedPats[0]['custom_events']['removed']);
    }

    // -------------------------------------------------------------------------
    // meaning-near-match
    // -------------------------------------------------------------------------

    public function testMeaningNearMatchDetected()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        // 同一 fp で異なる SQL テキスト（UPPERCASE vs lowercase Eloquent）
        $this->assertNotEmpty($diff['meaning_near_matches']);
        $fps = array_column($diff['meaning_near_matches'], 'fp');
        $this->assertContains('fp1:aaa111222333444555666777888999aaabbbccc', $fps);
    }

    public function testMeaningNearMatchHasBothSqlVersions()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $match = null;
        foreach ($diff['meaning_near_matches'] as $m) {
            if ($m['fp'] === 'fp1:aaa111222333444555666777888999aaabbbccc') {
                $match = $m;
                break;
            }
        }
        $this->assertNotNull($match);
        $this->assertNotEmpty($match['legacy_sqls']);
        $this->assertNotEmpty($match['target_sqls']);
        $this->assertNotSame($match['legacy_sqls'], $match['target_sqls']);
    }

    public function testMeaningNearMatchIsSortedByFp()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $fps = array_column($diff['meaning_near_matches'], 'fp');
        $sorted = $fps;
        sort($sorted);
        $this->assertSame($sorted, $fps);
    }

    public function testNoMeaningNearMatchWhenSqlIdentical()
    {
        $export = $this->loadFixture('diff-legacy.json');
        $diff   = $this->engine()->diff($export, $export);
        $this->assertEmpty($diff['meaning_near_matches']);
    }

    // -------------------------------------------------------------------------
    // 決定論的ソート
    // -------------------------------------------------------------------------

    public function testAddedEntrypointsAreSorted()
    {
        $legacy = $this->loadFixture('diff-legacy.json');
        $target = $this->loadFixture('diff-target.json');
        $diff   = $this->engine()->diff($legacy, $target);

        $added = $diff['entrypoints']['added'];
        $sorted = $added;
        sort($sorted);
        $this->assertSame($sorted, $added);
    }

    // -------------------------------------------------------------------------
    // ヘルパー
    // -------------------------------------------------------------------------

    private function findChangedEntrypoint(array $diff, $key)
    {
        foreach ($diff['entrypoints']['changed'] as $ep) {
            if (isset($ep['entrypoint_key']) && $ep['entrypoint_key'] === $key) {
                return $ep;
            }
        }
        return null;
    }
}
