<?php

namespace App\Packages\Cart\Domain;

class Cart
{
    public readonly CartId $cartId;
    public readonly UserId $userId;
    public CartProductList $cartProductList;

    public function __construct(CartId $cartId, UserId $userId, CartProductList $cartProductList)
    {
        $this->cartId = $cartId;
        $this->userId = $userId;
        $this->cartProductList = $cartProductList;
    }

    public function addProduct(CartProduct $cartProduct)
    {
        $this->cartProductList = $this->cartProductList->add($cartProduct);
    }

    public function removeProduct(ProductId $productId)
    {
        $this->cartProductList = $this->cartProductList->remove($productId);
    }

    public function tryTransferYupacketProduct()
    {
        $this->cartProductList = $this->cartProductList->tryTransferToYupacketProduct();
        $this->cartProductList = $this->cartProductList->tryTransferToNotYupacketProduct();
    }
}
