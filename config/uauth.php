<?php

return [
  'api' => [
    'base_url' => env('UAUTH_API_BASE_URL', null),
    'ssl_verify' => env('UAUTH_API_SSL_VERIFY', true),
    'timeout' => env('UAUTH_API_TIMEOUT', 30),
    'connect_timeout' => env('UAUTH_API_CONNECT_TIMEOUT', 10),
    'provider_id' => env('UAUTH_API_PROVIDER_ID', null),
  ],
];
