<?php

namespace App\Http\Controllers;

use App\Http\Requests\SellerUpdateRequest;
use App\Models\Seller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::all();

        return Inertia::render('SellerIndex', [
            'sellers' => $sellers
        ]);
    }

    public function edit(Seller $seller)
    {
        return Inertia::render('SellerEdit', [
            'seller' => $seller
        ]);
    }

    public function update(SellerUpdateRequest $request, Seller $seller)
    {
        $seller->approval_status = $request->approval_status;
        $seller->save();
        return back()->with('flashSuccessMessage', '出品者 '. $seller->name . ' を ' . $seller->approval_status . 'しました。');
    }
}
