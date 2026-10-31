<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\ProductName;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class ProductNameTest extends TestCase
{
    public function test_商品名がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品名は必須です');
        $productName = new ProductName('');
    }

    public function test_商品名が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品名は必須です');
        $productName = new ProductName(' 　');
    }

    public function test_商品名に絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品名に絵文字は入力できません');
        $productName = new ProductName('テスト🐸');
    }

    public function test_商品名が50文字より大きい()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品名は50文字以内です');
        $productName = new ProductName('123456789012345678901234567890123456789012345678901');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_商品名が50文字以内()
    {
        $productName = new ProductName('12345678901234567890123456789012345678901234567890');
    }
}