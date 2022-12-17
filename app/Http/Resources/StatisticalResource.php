<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StatisticalResource extends JsonResource
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
            'interest_in_month' => $this['interestInMonth'],
            'funds' => $this['funds'],
            'product_totail' => $this['productTotail'],
            'best_selling_products' => $this['bestSellingProducts'],
            'most_profitable_products' => $this['mostProfitableProducts'],
        ];
    }
}
