<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * CategoryBuyerController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $buyers = $category->products()
                           ->whereHas('transactions')
                           ->with('transactions.buyer')
                           ->get()
                           ->pluck('transactions')
                           ->collapse()
                           ->pluck('buyer')
                           ->unique('id')
                           ->values();

        return $this->showAll($buyers);
    }
}
