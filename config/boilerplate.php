<?php

return [
    'validation_rules' => [
        'store_user' => [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ],

        'update_user' => [
            'email' => 'email|unique:users, email,',
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . \App\User::ADMIN_USER . ',' . \App\User::REGULAR_USER,
        ]
    ],
];
