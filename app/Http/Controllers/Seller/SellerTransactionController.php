<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    /**
     * @param Seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $transactions = $seller->products()
                              ->whereHas('transactions')
                              ->with('transactions')
                              ->get()
                              ->pluck('transactions')
                              ->collapse();

        return $this->showAll($transactions);
    }
}
