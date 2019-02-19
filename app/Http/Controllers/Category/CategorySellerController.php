<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategorySellerController extends ApiController
{

    /**
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        $sellers = $category->products()
                            ->with('seller')
                            ->get()
                            ->pluck('seller')
                            ->unique()
                            ->values();

        return $this->showAll($sellers);
    }
}
