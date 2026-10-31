<?php

namespace App\Packages\Cart\Infrastructure;

use App\Models\Product as ModelProduct;
use App\Models\YupacketProductRelation;
use App\Packages\Cart\Domain\CartProduct;
use App\Packages\Cart\Domain\CartProductFactoryInterface;
use App\Packages\Cart\Domain\Product;
use App\Packages\Cart\Domain\ProductId;
use App\Packages\Cart\Domain\ProductName;
use App\Packages\Cart\Domain\ProductQuantity;
use App\Packages\Cart\Domain\ProductUnitPointPrice;
use App\Packages\Cart\Domain\ProductUnitPriceWithTax;
use App\Packages\Cart\Domain\ProductUnitTax;
use App\Packages\Shared\Infrastructure\InfrastructureException;

class CartProductFactory implements CartProductFactoryInterface
{
    public function create(ProductId $productId, ProductQuantity $productQuantity) : CartProduct
    {
        $modelProduct = ModelProduct::find($productId->value);

        if(empty($modelProduct)){
            throw new InfrastructureException('商品が見つかりませんでした');
        }

        $yupacketTransferBeforeProduct = null;
        $yupacketTransferAfterProduct = null;

        $modelYupacketProductRelation = YupacketProductRelation::where('yupacket_product_id', $modelProduct->id)
                                                                ->orWhere('non_yupacket_product_id', $modelProduct->id)
                                                                ->first();
        
        if (!empty($modelYupacketProductRelation) && $modelYupacketProductRelation->yupacket_product_id == $productId->value){
            $modelYupacketTransferBeforeProduct = ModelProduct::find($modelYupacketProductRelation->non_yupacket_product_id);
            $yupacketTransferBeforeProduct = new Product(
                new ProductId($modelYupacketTransferBeforeProduct->id),
                new ProductName($modelYupacketTransferBeforeProduct->name),
                new ProductUnitPriceWithTax($modelYupacketTransferBeforeProduct->price_with_tax),
                new ProductUnitTax($modelYupacketTransferBeforeProduct->tax),
                new ProductUnitPointPrice($modelYupacketTransferBeforeProduct->point_price)
            );
        }

        if (!empty($modelYupacketProductRelation) && $modelYupacketProductRelation->non_yupacket_product_id == $productId->value){
            $modelYupacketTransferAfterProduct = ModelProduct::find($modelYupacketProductRelation->yupacket_product_id);
            $yupacketTransferAfterProduct = new Product(
                new ProductId($modelYupacketTransferAfterProduct->id),
                new ProductName($modelYupacketTransferAfterProduct->name),
                new ProductUnitPriceWithTax($modelYupacketTransferAfterProduct->price_with_tax),
                new ProductUnitTax($modelYupacketTransferAfterProduct->tax),
                new ProductUnitPointPrice($modelYupacketTransferAfterProduct->point_price)
            );
        }

        return new CartProduct(
            $productId,
            new ProductName($modelProduct->name),
            new ProductUnitPriceWithTax($modelProduct->price_with_tax),
            new ProductUnitTax($modelProduct->tax),
            new ProductUnitPointPrice($modelProduct->point_price),
            $yupacketTransferBeforeProduct,
            $yupacketTransferAfterProduct,
            $productQuantity
        );
    }
}