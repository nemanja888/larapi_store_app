<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerCategoryController extends ApiController
{
    public function index(Seller $seller)
    {
        $categories = $seller->products()
                             ->with('categories')
                             ->whereHas('categories')
                             ->get()
                             ->pluck('categories')
                             ->collapse()
                             ->unique('id')
                             ->values();

        return $this->showAll($categories);
    }
}
