<?php

namespace Tests\Unit\Packages\Cart\Domain;

use App\Packages\Cart\Domain\CartProduct;
use App\Packages\Cart\Domain\CartProductList;
use App\Packages\Cart\Domain\Product;
use App\Packages\Cart\Domain\ProductId;
use App\Packages\Cart\Domain\ProductName;
use App\Packages\Cart\Domain\ProductQuantity;
use App\Packages\Cart\Domain\ProductUnitPointPrice;
use App\Packages\Cart\Domain\ProductUnitPriceWithTax;
use App\Packages\Cart\Domain\ProductUnitTax;
use App\Packages\Shared\Domain\DomainException;
use PHPUnit\Framework\TestCase;

class CartProductListTest extends TestCase
{
    public function test_商品配列以外の配列でインスタンス化()
    {
        $cartProducts = [
            '商品1', '商品2', '商品3'
        ];

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('商品リストには商品のみを入れられます');
        $cartProductList = new CartProductList($cartProducts);
    }

    public function test_商品の追加()
    {
        $cartProductList = new CartProductList([]);

        $addCartProduct1 = new CartProduct(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(1)
        );

        $addCartProduct2 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(1)
        );

        $addCartProduct3 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(2)
        );

        $cartProductList = $cartProductList->add($addCartProduct1);
        $cartProductList = $cartProductList->add($addCartProduct2);
        $cartProductList = $cartProductList->add($addCartProduct3);
        
        // 商品の種類数
        $this->assertEquals(2, count($cartProductList->value));

        // 商品の数量
        $this->assertEquals(1, $cartProductList->value[0]->productQuantity->value);
        $this->assertEquals(3, $cartProductList->value[1]->productQuantity->value);
    }

    public function test_商品の削除()
    {
        $cartProduct1 = new CartProduct(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(1)
        );

        $cartProduct2 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(1)
        );

        $cartProductList = new CartProductList([$cartProduct1, $cartProduct2]);

        $cartProductList = $cartProductList->remove($cartProduct2->productId);

        $this->assertEquals(1, count($cartProductList->value));
    }

    public function test_商品の数量集計()
    {
        $cartProduct1 = new CartProduct(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(1)
        );

        $cartProduct2 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(0),
            null,
            null,
            new ProductQuantity(2)
        );

        $cartProductList = new CartProductList([$cartProduct1, $cartProduct2]);

        $this->assertEquals(3, $cartProductList->sumQuantity());
    }

    public function test_カート商品リストが単品でゆうパケット振り替え対象商品である場合の振り替え()
    {
        $yupacketTransferAfterProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            null,
            $yupacketTransferAfterProduct,
            new ProductQuantity(1)
        );

        $cartProductList = new CartProductList([$cartProduct]);

        $cartProductList = $cartProductList->tryTransferToYupacketProduct();

        // 商品が振り替わっているはず
        $this->assertEquals(1, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品1', $cartProductList->value[0]->productName->value);
        $this->assertEquals(100, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(10, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[0]->productQuantity->value);
    }

    public function test_カート商品リストが単種類・数量が2以上でゆうパケット振り替え対象商品である場合の振り替え()
    {
        $yupacketTransferAfterProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            null,
            $yupacketTransferAfterProduct,
            new ProductQuantity(2)
        );

        $cartProductList = new CartProductList([$cartProduct]);

        $cartProductList = $cartProductList->tryTransferToYupacketProduct();

        // 商品は振り替わらないはず
        $this->assertEquals(2, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品2', $cartProductList->value[0]->productName->value);
        $this->assertEquals(200, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(20, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(2, $cartProductList->value[0]->productQuantity->value);
    }

    public function test_カート商品リストが複数種ありゆうパケット振り替え対象商品がある場合の振り替え()
    {
        $yupacketTransferAfterProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct1 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            null,
            $yupacketTransferAfterProduct,
            new ProductQuantity(1)
        );

        $cartProduct2 = new CartProduct(
            new ProductId(3),
            new ProductName('商品3'),
            new ProductUnitPriceWithTax(300),
            new ProductUnitTax(30),
            new ProductUnitPointPrice(30),
            null,
            null,
            new ProductQuantity(1)
        );

        $cartProductList = new CartProductList([$cartProduct1, $cartProduct2]);

        $cartProductList = $cartProductList->tryTransferToYupacketProduct();

        // 商品は振り替わらないはず
        $this->assertEquals(2, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品2', $cartProductList->value[0]->productName->value);
        $this->assertEquals(200, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(20, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[0]->productQuantity->value);

        // 対象でない商品はそのまま
        $this->assertEquals(3, $cartProductList->value[1]->productId->value);
        $this->assertEquals('商品3', $cartProductList->value[1]->productName->value);
        $this->assertEquals(300, $cartProductList->value[1]->productPriceWithTax->value);
        $this->assertEquals(30, $cartProductList->value[1]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[1]->productQuantity->value);
    }

    public function test_カート商品リストが単品でゆうパケット商品である場合の振り戻し()
    {
        $yupacketTransferBeforeProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            $yupacketTransferBeforeProduct,
            null,
            new ProductQuantity(1)
        );

        $cartProductList = new CartProductList([$cartProduct]);

        $cartProductList = $cartProductList->tryTransferToNotYupacketProduct();

        // 商品は振り戻らないはず
        $this->assertEquals(2, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品2', $cartProductList->value[0]->productName->value);
        $this->assertEquals(200, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(20, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[0]->productQuantity->value);
    }

    public function test_カート商品リストが単種類・数量が2以上でゆうパケット商品である場合の振り戻し()
    {
        $yupacketTransferBeforeProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            $yupacketTransferBeforeProduct,
            null,
            new ProductQuantity(2)
        );

        $cartProductList = new CartProductList([$cartProduct]);

        $cartProductList = $cartProductList->tryTransferToNotYupacketProduct();

        // 商品は振り戻るはず
        $this->assertEquals(1, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品1', $cartProductList->value[0]->productName->value);
        $this->assertEquals(100, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(10, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(2, $cartProductList->value[0]->productQuantity->value);
    }

    public function test_カート商品が複数種ありゆうパケット商品が存在する場合の振り戻し()
    {
        $yupacketTransferBeforeProduct = new Product(
            new ProductId(1),
            new ProductName('商品1'),
            new ProductUnitPriceWithTax(100),
            new ProductUnitTax(10),
            new ProductUnitPointPrice(10)
        );
        
        $cartProduct1 = new CartProduct(
            new ProductId(2),
            new ProductName('商品2'),
            new ProductUnitPriceWithTax(200),
            new ProductUnitTax(20),
            new ProductUnitPointPrice(20),
            $yupacketTransferBeforeProduct,
            null,
            new ProductQuantity(1)
        );

        $cartProduct2 = new CartProduct(
            new ProductId(3),
            new ProductName('商品3'),
            new ProductUnitPriceWithTax(300),
            new ProductUnitTax(30),
            new ProductUnitPointPrice(30),
            null,
            null,
            new ProductQuantity(1)
        );

        $cartProductList = new CartProductList([$cartProduct1, $cartProduct2]);

        $cartProductList = $cartProductList->tryTransferToNotYupacketProduct();

        // 商品は振り戻るはず
        $this->assertEquals(1, $cartProductList->value[1]->productId->value);
        $this->assertEquals('商品1', $cartProductList->value[1]->productName->value);
        $this->assertEquals(100, $cartProductList->value[1]->productPriceWithTax->value);
        $this->assertEquals(10, $cartProductList->value[1]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[1]->productQuantity->value);

        // 対象でない商品はそのまま
        $this->assertEquals(3, $cartProductList->value[0]->productId->value);
        $this->assertEquals('商品3', $cartProductList->value[0]->productName->value);
        $this->assertEquals(300, $cartProductList->value[0]->productPriceWithTax->value);
        $this->assertEquals(30, $cartProductList->value[0]->productPointPrice->value);
        $this->assertEquals(1, $cartProductList->value[0]->productQuantity->value);
    }
}