<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
    'http://localhost:5173',
    'https://dibitech-frontend-4wir.vercel.app',
    'https://dibitech-frontend-4wir-3cui1no1u-anggi1.vercel.app',
],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];
