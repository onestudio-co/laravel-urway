<?php

return [
    'auth' => [
        'terminal_id' => env('URWAY_TERMINAL_ID'),
        'password' => env('URWAY_PASSWORD'),
        'merchant_key' => env('URWAY_MERCHANT_KEY'),
    ],
    'dev_mode' => env('URWAY_DEV_MODE', true),
];