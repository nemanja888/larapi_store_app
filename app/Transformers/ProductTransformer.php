<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'title' => (string)$product->name,
            'details' => (string)$product->description,
            'stock' => (int)$product->quantity,
            'situation' => (string)$product->status,
            'seller' => (int)$product->seller_id,
            'picture' => url("img/{$product->image}"),
            'creationDate' => (string)$product->created_at,
            'lastChange' => (string)$product->updated_at,
            'deleteDate' => isset($product->deleted_at) ? (string) $product->deleted_at : null,

            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('products.show', $product->id),
                ],
                [
                    'rel' => 'product.buyers',
                    'href' => route('products.buyers.index', $product->id),
                ],
                [
                    'rel' => 'product.category',
                    'href' => route('products.categories.index', $product->id),
                ],
                [
                    'rel' => 'seller',
                    'href' => route('sellers.show', $product->seller_id),
                ],
                [
                    'rel' => 'product.transactions',
                    'href' => route('products.transactions.index', $product->id),
                ],
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => "id",
            'title' => "name",
            'details' => "description",
            'stock' => "quantity",
            'situation' => "status",
            'seller' => "seller_id",
            'picture' => "image",
            'creationDate' => "created_at",
            'lastChange' => "updated_at",
            'deleteDate' => "deleted_at",
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }

    public static function transformedAttribute($index)
    {
        $attributes = [
            "id" => 'identifier',
            "name" => 'title',
            "description" => 'details',
            "quantity" => 'stock',
            "status" => 'situation',
            "seller_id" => 'seller',
            "image" => 'picture',
            "created_at" => 'creationDate',
            "updated_at" => 'lastChange',
            "deleted_at" => 'deleteDate'
        ];
        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
