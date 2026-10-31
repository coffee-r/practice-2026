<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\ProductUnitPointPrice;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class ProductUnitPointPriceTest extends TestCase
{
    public function test_ポイント単価が0未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品ポイント価格は0以上です');
        $productUnitPointPrice = new ProductUnitPointPrice(-1);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_ポイント単価が0以上()
    {
        $productUnitPointPrice = new ProductUnitPointPrice(0);
    }
}