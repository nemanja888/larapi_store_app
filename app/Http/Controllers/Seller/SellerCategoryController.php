<?php

namespace App\Http\Controllers\Seller;

use App\Category;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerCategoryController extends ApiController
{
    /**
     * SellerCategoryController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('scope:read-general')->only(['index']);
    }

    /**
     * @param Seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
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
