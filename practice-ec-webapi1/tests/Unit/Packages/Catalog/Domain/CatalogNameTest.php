<?php

namespace Tests\Unit\Packages\Catalog\Domain;

use App\Packages\Catalog\Domain\CategoryName;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class CategoryNameTest extends TestCase
{
    public function test_カテゴリ名がブランク文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('カテゴリ名は必須です');
        $categoryName = new CategoryName('');
    }

    public function test_カテゴリ名が空白文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('カテゴリ名は必須です');
        $categoryName = new CategoryName(' 　');
    }

    public function test_カテゴリ名に絵文字()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('カテゴリ名に絵文字は入力できません');
        $categoryName = new CategoryName('テスト🐸');
    }

    public function test_カテゴリ名が20文字より大きい()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('カテゴリ名は20文字以内です');
        $categoryName = new CategoryName('123456789012345678901');
    }

    /**
     * @doesNotPerformAssertions
     */
    public function test_カテゴリ名が20文字以内()
    {
        $categoryName = new CategoryName('12345678901234567890');
    }
}