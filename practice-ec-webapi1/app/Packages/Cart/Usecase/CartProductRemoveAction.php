<?php

namespace App\Packages\Cart\Usecase;

use App\Packages\Cart\Domain\CartId;
use App\Packages\Cart\Domain\CartRepositoryInterface;
use App\Packages\Cart\Domain\ProductId;
use App\Packages\Shared\Usecase\UsecaseException;
use Illuminate\Support\Facades\DB;


class CartProductRemoveAction
{
    private readonly CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(CartProductRemoveCommand $cartProductRemoveCommand)
    {
        $cart = DB::transaction(function () use ($cartProductRemoveCommand) {
            $cart = $this->cartRepository->find(new CartId($cartProductRemoveCommand->cartId));

            if ($cart == null){
                throw new UsecaseException('存在しないカートです');
            }
        
            $productId = new ProductId($cartProductRemoveCommand->productId);

            $cart->removeProduct($productId);

            $cart->tryTransferYupacketProduct();

            $this->cartRepository->save($cart);

            return $cart;
        });

        return new CartData($cart);
        
    }
}
