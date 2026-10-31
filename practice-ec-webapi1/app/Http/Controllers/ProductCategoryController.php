<?php

namespace App\Http\Controllers;

use App\Http\Resources\LargeCategoryResource;
use App\Packages\Catalog\Usecase\LargeCategoryListGetAction;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LargeCategoryListGetAction $largeCategoryListGetAction)
    {
        //
        $largeCategoryListData = $largeCategoryListGetAction();
        return LargeCategoryResource::collection($largeCategoryListData->value);
    }
}
