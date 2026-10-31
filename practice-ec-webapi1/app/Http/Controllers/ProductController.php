<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductOutlinePagenationResource;
use App\Http\Resources\ProductResource;
use App\Packages\Catalog\Usecase\ProductGetAction;
use App\Packages\Catalog\Usecase\ProductOutlineListGetAction;
use App\Packages\Catalog\Usecase\ProductOutlineQuery;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ProductOutlineListGetAction $productOutlineListGetAction)
    {
        //
        $productOutlineQuery = new ProductOutlineQuery(
            $request->query('page'),
            $request->query('perPage'),
            $request->query('categoryId'),
            $request->query('productKeyword'),
            $request->query('sort')
        );

        $productOutlinePaginationData = $productOutlineListGetAction($productOutlineQuery);
        return new ProductOutlinePagenationResource($productOutlinePaginationData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, ProductGetAction $procuctGetAction)
    {
        $productData = $procuctGetAction($id);
        return new ProductResource($productData);
    }
}
