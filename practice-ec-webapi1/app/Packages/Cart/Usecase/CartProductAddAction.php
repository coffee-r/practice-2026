<?php

namespace App\Packages\Cart\Usecase;

use App\Packages\Cart\Domain\CartId;
use App\Packages\Cart\Domain\CartProductFactoryInterface;
use App\Packages\Cart\Domain\CartRepositoryInterface;
use App\Packages\Cart\Domain\ProductId;
use App\Packages\Cart\Domain\ProductQuantity;
use App\Packages\Shared\Usecase\UsecaseException;
use Illuminate\Support\Facades\DB;

class CartProductAddAction
{
    private readonly CartRepositoryInterface $cartRepository;
    private readonly CartProductFactoryInterface $cartProductFactory;

    public function __construct(CartRepositoryInterface $cartRepository, CartProductFactoryInterface $cartProductFactory)
    {
        $this->cartRepository = $cartRepository;
        $this->cartProductFactory = $cartProductFactory;
    }

    public function __invoke(CartProductAddCommand $cartProductAddCommand)
    {
        $cart = DB::transaction(function () use ($cartProductAddCommand) {
            $cart = $this->cartRepository->find(new CartId($cartProductAddCommand->cartId));

            if ($cart == null){
                throw new UsecaseException('存在しないカートです');
            }
        
            $cartProduct = $this->cartProductFactory->create(new ProductId($cartProductAddCommand->productId), new ProductQuantity($cartProductAddCommand->productQuantity));

            $cart->addProduct($cartProduct);

            $cart->tryTransferYupacketProduct();

            $this->cartRepository->save($cart);

            return $cart;
        });

        return new CartData($cart);
    }
}
