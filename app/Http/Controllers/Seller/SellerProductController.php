<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\StoreProduct;
use App\Http\Requests\UpdateProductRequest;
use App\Product;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{
    /**
     * @param Seller $seller
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    public function store(StoreProduct $request, Seller $seller)
    {
        $data = $request->all();
        $data['image'] = $request->image->store('');
        $data['status'] = Product::UNAVAILABLE_PRODUCT;
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateProductRequest $request
     * @param  \App\Seller $seller
     * @param Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);

        $product->fill($request->only(
           'name',
                'description',
                'quantity'
        ));
        if ($request->has('status')) {
            $product->status = $request->status;
            if ($product->isAvailable() && $product->categories()->count == 0) {
                return $this->errorResponse('An active product must have at least one category', 409);
            }
        }
        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        if ($product->isClean()) {
            return $this->errorResponse('You need to specify different values to update', 422);
        }
        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller $seller
     * @param Product $product
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->checkSeller($seller, $product);
        Storage::delete($product->image);
        $product->delete();

        return $this->showOne($product);
    }

    protected function checkSeller (Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpExxeption(422, 'The specified seller is not actual seller of the product');
        }
    }
}
