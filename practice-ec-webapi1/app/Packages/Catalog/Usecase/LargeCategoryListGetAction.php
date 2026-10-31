<?php

namespace App\Packages\Catalog\Usecase;

use App\Packages\Catalog\Domain\LargeCategoryListRepositoryInterface;
use App\Packages\Shared\Usecase\UsecaseException;

class LargeCategoryListGetAction{

    private readonly LargeCategoryListRepositoryInterface $largeCategoryListRepository;

    public function __construct(LargeCategoryListRepositoryInterface $largeCategoryListRepository)
    {
        $this->largeCategoryListRepository = $largeCategoryListRepository;
    }

    public function __invoke()
    {
        $largeCategoryList = $this->largeCategoryListRepository->find();

        return new LargeCategoryListData($largeCategoryList);
    }
}
