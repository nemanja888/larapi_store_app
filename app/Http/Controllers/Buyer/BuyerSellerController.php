<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{

    /**
     * @param Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()
                        ->with('product.seller')
                        ->get()
                        ->pluck('product.seller')
                        ->unique('id');

        return $this->showAll($sellers);
    }
}
