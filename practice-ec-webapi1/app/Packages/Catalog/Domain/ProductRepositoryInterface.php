<?php

namespace App\Packages\Catalog\Domain;

interface ProductRepositoryInterface
{
    public function find(ProductId $productId) : Product | null;
}