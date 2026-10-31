<?php

namespace App\Packages\Cart\Usecase;

use App\Packages\Cart\Domain;
use App\Packages\Cart\Domain\CartId;
use App\Packages\Cart\Domain\CartRepositoryInterface;
use App\Packages\Shared\Usecase\UsecaseException;

class CartGetAction
{
    private readonly CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(int $cartId)
    {
        $cart = $this->cartRepository->find(new CartId($cartId));
        
        if($cart == null) {
            throw new UsecaseException('ID:'.$cartId.' のカートは存在しません', 404);
        }

        return new CartData($cart);
    }
}
