<?php

return [
    'validation_rules' => [
        'store_user' => [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ],

        'update_user' => [
            'email' => 'email|unique:users',
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . \App\User::ADMIN_USER . ',' . \App\User::REGULAR_USER,
        ],
        'store_category' => [
            'name' => 'required|string',
            'description' => 'required|string',
        ],
        'update_category' => [
            'name' => 'string',
            'description' => 'string',
        ],
        'store_product' => [
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ],
        'update_product' => [
            'name' => 'string',
            'description' => 'string',
            'quantity' => 'integer|min:1',
            'image' => 'image'
        ],
        'store_transaction' => [
            'quantity' => 'required|integer|min:1',
        ],
    ],
];
