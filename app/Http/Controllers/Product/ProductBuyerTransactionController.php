<?php

namespace App\Http\Controllers\Product;

use App\Http\Requests\StoreTransactionRequest;
use App\Product;
use App\Transaction;
use App\User;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    public function store(StoreTransactionRequest $request, Product $product, User $buyer)
    {
        if ($buyer->id == $product->seller->id) {
            return $this->errorResponse('The buyer must be differnt from the seller', 409);
        }
        if (!$buyer->isVerified()) {
            return $this->errorResponse('The buyer must be verified', 409);
        }
        if (!$product->seller->isVerified()) {
            return $this->errorResponse('The seller must be verified', 409);
        }
        if (!$product->isAvailable()) {
            return $this->errorResponse('The product is not available', 409);
        }
        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('The product does not have enough units for this transaction', 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'product_id' => $buyer->id,
                'buyer_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
