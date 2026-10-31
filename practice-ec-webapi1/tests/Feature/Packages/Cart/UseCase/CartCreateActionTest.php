<?php

namespace Tests\Feature\Packages\Cart;

use App\Packages\Cart\Infrastructure\CartFactory;
use App\Packages\Cart\Infrastructure\CartRepository;
use App\Packages\Cart\Usecase\CartCreateAction;
use App\Packages\Cart\Usecase\CartData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartCreateActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_新規作成()
    {
        $userId = 9999;

        $cartCreateAction = new CartCreateAction(new CartRepository(), new CartFactory());
        $cartData = $cartCreateAction($userId);

        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertDatabaseHas(
            'carts',
            [
                'user_id' => 9999
            ]
        );
    }

}
