<?php

namespace App\Packages\Catalog\Domain;

interface LargeCategoryListRepositoryInterface
{
    public function find() : LargeCategoryList;
}