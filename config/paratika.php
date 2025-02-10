<?php

return [
    'test' => [
        'url' => 'https://entegrasyon.paratika.com.tr/paratika/api/v2'
    ],
    'prod' => [
        'url' => 'https://vpos.paratika.com.tr/paratika/api/v2'
    ],
    'merchant' => env('PARATIKA_MERCHANT_ID'),
    'merchantUser' => env('PARATIKA_API_USER'),
    'merchantPassword' => env('PARATIKA_API_PASSWORD'),
    'testMode' => env('PARATIKA_TEST_MODE', false),
];