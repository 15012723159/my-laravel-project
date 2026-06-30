<?php

return [
    'test' => [ //测试站
        'store_url' => env('SHOPIFY_TEST_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_TEST_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'us' => [ //全球站
        'store_url' => env('SHOPIFY_US_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_US_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'de' => [ //德国站
        'store_url' => env('SHOPIFY_DE_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_DE_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'es' => [ //西班牙站
        'store_url' => env('SHOPIFY_ES_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_ES_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'uk' => [ //英国站
        'store_url' => env('SHOPIFY_UK_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_UK_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'fr' => [ //法国站
        'store_url' => env('SHOPIFY_FR_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_FR_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'it' => [ //
        'store_url' => env('SHOPIFY_IT_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_IT_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'pl' => [
        'store_url' => env('SHOPIFY_PL_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_PL_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'ru' => [
        'store_url' => env('SHOPIFY_RU_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_RU_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'eu' => [
        'store_url' => env('SHOPIFY_EU_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_EU_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'au' => [
        'store_url' => env('SHOPIFY_AU_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_AU_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'ca' => [
        'store_url' => env('SHOPIFY_CA_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_CA_X_SHOPIFY_ACCESS_TOKEN', '')
    ],
    'usa_test' => [
        'store_url' => env('SHOPIFY_USA_TEST_STORE_URL', ''),
        'x_shopify_access_token' => env('SHOPIFY_USA_TEST_X_SHOPIFY_ACCESS_TOKEN', '')
    ]
];
