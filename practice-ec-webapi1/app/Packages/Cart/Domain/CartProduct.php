<?php

namespace App\Packages\Cart\Domain;

class CartProduct
{
    public readonly ProductId $productId;
    public ProductName $productName;
    public ProductUnitPriceWithTax $productPriceWithTax;
    public ProductUnitTax $productTax;
    public ProductUnitPointPrice $productPointPrice;
    public Product | null $yupacketTransferBeforeProduct;
    public Product | null $yupacketTransferAfterProduct;
    public ProductQuantity $productQuantity;

    public function __construct(
        ProductId $productId,
        ProductName $productName,
        ProductUnitPriceWithTax $productPriceWithTax,
        ProductUnitTax $productTax,
        ProductUnitPointPrice $productPointPrice,
        Product | null $yupacketTransferBeforeProduct,
        Product | null $yupacketTransferAfterProduct,
        ProductQuantity $productQuantity)
        {
            $this->productId = $productId;
            $this->productName = $productName;
            $this->productPriceWithTax = $productPriceWithTax;
            $this->productTax = $productTax;
            $this->productPointPrice = $productPointPrice;
            $this->yupacketTransferBeforeProduct = $yupacketTransferBeforeProduct;
            $this->yupacketTransferAfterProduct = $yupacketTransferAfterProduct;
            $this->productQuantity = $productQuantity;
        }
    
    public function toProduct()
    {
        return new Product(
            $this->productId,
            $this->productName,
            $this->productPriceWithTax,
            $this->productTax,
            $this->productPointPrice
        );
    }
}
