<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\ProductUnitTax;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class ProductUnitTaxTest extends TestCase
{
    public function test_税額が0未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品単価税額は0以上です');
        $productUnitTax = new ProductUnitTax(-1);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_税額が0以上()
    {
        $productUnitTax = new ProductUnitTax(0);
    }
}