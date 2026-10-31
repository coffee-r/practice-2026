<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductOutlineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'productId' => $this->productId,
            'productName' => $this->productName,
            'categoryId' => $this->categoryId,
            'productPriceWithTax' => $this->productPriceWithTax,
            'productPointPrice' => $this->productPointPrice,
            'productReviewScoreAverage' => $this->productReviewScoreAverage
        ];
    }
}
