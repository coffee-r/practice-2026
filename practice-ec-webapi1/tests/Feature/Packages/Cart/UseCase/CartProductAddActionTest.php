<?php

namespace Tests\Feature\Packages\Cart;

use App\Models\Cart;
use App\Models\Product;
use App\Models\YupacketProductRelation;
use App\Packages\Cart\Infrastructure\CartProductFactory;
use App\Packages\Cart\Infrastructure\CartRepository;
use App\Packages\Cart\Usecase\CartData;
use App\Packages\Cart\Usecase\CartProductAddAction;
use App\Packages\Cart\Usecase\CartProductAddCommand;
use App\Packages\Shared\Infrastructure\InfrastructureException;
use App\Packages\Shared\Usecase\UsecaseException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartProductAddActionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_商品の追加()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $cartProductAddCommand = new CartProductAddCommand($cart->id, $product->id, 1);
        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());
        $cartData = $cartProductAddAction($cartProductAddCommand);

        $this->assertInstanceOf(CartData::class, $cartData);
        $this->assertDatabaseHas(
            'carts',
            [
                'id' => $cart->id,
            ]
        );

        $this->assertDatabaseHas(
            'cart_products',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price_with_tax' => $product->price_with_tax,
                'product_tax' => $product->tax,
                'product_point_price' => $product->point_price,
                'product_quantity' => 1
            ]
        );
    }

    public function test__存在しない商品の追加()
    {
        $cart = Cart::factory()->create();

        $cartProductAddCommand = new CartProductAddCommand($cart->id, 1, 1);
        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());

        $this->expectException(InfrastructureException::class);
        $this->expectExceptionMessage('商品が見つかりませんでした');
        $cartProductAddAction($cartProductAddCommand);
    }

    public function test__存在しないカートへの追加()
    {
        $product = Product::factory()->create();

        $cartProductAddCommand = new CartProductAddCommand(1, $product->id, 1);
        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());

        $this->expectException(UsecaseException::class);
        $this->expectExceptionMessage('存在しないカートです');
        $cartProductAddAction($cartProductAddCommand);
    }

    public function test_既存商品の数量追加()
    {
        $cart = Cart::factory()->create();
        $product = Product::factory()->create();

        $cartProductAddCommand1 = new CartProductAddCommand($cart->id, $product->id, 1);
        $cartProductAddCommand2 = new CartProductAddCommand($cart->id, $product->id, 2);

        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());
        $cartProductAddAction($cartProductAddCommand1);
        $cartProductAddAction($cartProductAddCommand2);

        $this->assertDatabaseHas(
            'cart_products',
            [
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'product_price_with_tax' => $product->price_with_tax,
                'product_tax' => $product->tax,
                'product_point_price' => $product->point_price,
                'product_quantity' => 3
            ]
        );
    }

    public function test_ゆうパケット振り替え()
    {
        $cart = Cart::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $yupacketRelation = YupacketProductRelation::factory()->create(['yupacket_product_id' => $product2->id, 'non_yupacket_product_id' => $product1->id]);

        $cartProductAddCommand = new CartProductAddCommand($cart->id, $product1->id, 1);
        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());
        $cartProductAddAction($cartProductAddCommand);

        $this->assertDatabaseHas(
            'cart_products',
            [
                'cart_id' => $cart->id,
                'product_id' => $product2->id,
                'product_name' => $product2->name,
                'product_price_with_tax' => $product2->price_with_tax,
                'product_tax' => $product2->tax,
                'product_point_price' => $product2->point_price,
                'product_quantity' => 1
            ]
        );
    }

    public function test_ゆうパケット振り戻し()
    {
        $cart = Cart::factory()->create();
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $yupacketRelation = YupacketProductRelation::factory()->create(['yupacket_product_id' => $product2->id, 'non_yupacket_product_id' => $product1->id]);

        $cartProductAddCommand = new CartProductAddCommand($cart->id, $product2->id, 2);
        $cartProductAddAction = new CartProductAddAction(new CartRepository(), new CartProductFactory());
        $cartProductAddAction($cartProductAddCommand);

        $this->assertDatabaseHas(
            'cart_products',
            [
                'cart_id' => $cart->id,
                'product_id' => $product1->id,
                'product_name' => $product1->name,
                'product_price_with_tax' => $product1->price_with_tax,
                'product_tax' => $product1->tax,
                'product_point_price' => $product1->point_price,
                'product_quantity' => 2
            ]
        );
    }

}
