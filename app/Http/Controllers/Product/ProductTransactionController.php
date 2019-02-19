<?php

namespace App\Http\Controllers\Product;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ProductTransactionController extends ApiController
{
    /**
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Product $product)
    {
        $transactions = $product->transactions;

        return $this->showAll($transactions);
    }
}
