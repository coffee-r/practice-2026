<?php

namespace Tests\Unit\Packages\Shared\Usecase;

use App\Packages\Shared\Usecase\PagenationData;
use App\Packages\Shared\Usecase\UsecaseException;
use PHPUnit\Framework\TestCase;

class PagenationDataTest extends TestCase
{
    public function test_総件数が1未満()
    {
        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('総件数は1以上です');
        $pagenationData = new PagenationData(0, 0, 10);
    }

    public function test_現在ページ数が1未満()
    {
        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('現在のページ数は1以上です');
        $pagenationData = new PagenationData(10, 0, 10);
    }

    public function test_最後のページ番号の計算()
    {
        // 余りなし
        $pagenationData = new PagenationData(100, 1, 10);
        $this->assertEquals(10, $pagenationData->lastPage);

        // 余り有り
        $pagenationData = new PagenationData(101, 1, 10);
        $this->assertEquals(11, $pagenationData->lastPage);
    }

    public function test_前のページの存在チェック()
    {
        // 最後のページ番号が1
        $pagenationData = new PagenationData(10, 1, 10);
        $this->assertEquals(false, $pagenationData->hasPreviousPage);

        // 最後のページ番号が1より大きい、現在ページが1
        $pagenationData = new PagenationData(11, 1, 10);
        $this->assertEquals(false, $pagenationData->hasPreviousPage);

        // 最後のページ番号が1より大きい、現在ページが1より大きい
        $pagenationData = new PagenationData(11, 2, 10);
        $this->assertEquals(true, $pagenationData->hasPreviousPage);
    }

    public function test_次のページの存在チェック()
    {
        // 最後のページ番号が1
        $pagenationData = new PagenationData(10, 1, 10);
        $this->assertEquals(false, $pagenationData->hasNextPage);

        // 最後のページ番号が1より大きい、現在ページが1
        $pagenationData = new PagenationData(11, 1, 10);
        $this->assertEquals(true, $pagenationData->hasNextPage);

        // 最後のページ番号が1より大きい、現在ページが最後のページ番号と同じ
        $pagenationData = new PagenationData(11, 2, 10);
        $this->assertEquals(false, $pagenationData->hasNextPage);
    }

}