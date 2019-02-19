<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param Category $category
     * @return array
     */
    public function transform(Category $category)
    {
        return [
            'identifier' => (int)$category->id,
            'name' => (string)$category->name,
            'details' => (string)$category->email,
            'creationDate' => (string)$category->created_at,
            'lastChange' => (string)$category->updated_at,
            'deleteDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
        ];
    }
}
