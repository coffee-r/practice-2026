<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\ProductUnitPriceWithTax;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class ProductUnitPriceWithTaxTest extends TestCase
{
    public function test_税込単価が0未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品税込価格は0以上です');
        $productUnitPriceWithTax = new ProductUnitPriceWithTax(-1);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_税込単価が0以上()
    {
        $productUnitPriceWithTax = new ProductUnitPriceWithTax(0);
    }
}