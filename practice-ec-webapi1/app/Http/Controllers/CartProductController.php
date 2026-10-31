<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Packages\Cart\Domain\CartProductFactoryInterface;
use App\Packages\Cart\Domain\CartRepositoryInterface;
use App\Packages\Cart\Usecase\CartProductAddAction;
use App\Packages\Cart\Usecase\CartProductAddCommand;
use App\Packages\Cart\Usecase\CartProductRemoveAction;
use App\Packages\Cart\Usecase\CartProductRemoveCommand;
use Illuminate\Http\Request;


class CartProductController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $cartId, CartRepositoryInterface $cartRepository, CartProductFactoryInterface $cartProductFactory)
    {
        //
        $cartProductAddCommand = new CartProductAddCommand($cartId, $request->input('productId'), $request->input('productQuantity'));
        $cartProductAddAction = new CartProductAddAction($cartRepository, $cartProductFactory);
        $cartData = $cartProductAddAction($cartProductAddCommand);
        return new CartResource($cartData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($cartId, $productId, CartRepositoryInterface $cartRepository)
    {
        //
        $cartRemoveCommand = new CartProductRemoveCommand($cartId, $productId);
        $cartProductRemoveAction = new CartProductRemoveAction($cartRepository);
        $cartData = $cartProductRemoveAction($cartRemoveCommand);
        return new CartResource($cartData);
    }
}
