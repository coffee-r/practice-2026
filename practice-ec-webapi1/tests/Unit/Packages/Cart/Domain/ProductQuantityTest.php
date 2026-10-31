<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\ProductQuantity;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class ProductQuantityTest extends TestCase
{
    public function test_商品数量が1未満()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品数量は1以上です');
        $productQuantity = new ProductQuantity(0);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_商品数量が1以上()
    {
        $productQuantity = new ProductQuantity(1);
    }
}