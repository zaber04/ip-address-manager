<?php

return [
    'auth' => [
        'base_url' => env('AUTH_SERVICE_BASE_URL', 'http://127.0.0.1:8001'),
        'base_url_name' => env('AUTH_SERVICE_BASE_URL_NAME', 'http://authentication'),
    ],
    'ip_handler' => [
        'base_url' => env('IP_HANDLER_SERVICE_BASE_URL', 'http://127.0.0.1:8002'),
        'base_url_name' => env('IP_HANDLER_SERVICE_BASE_URL_NAME', 'http://ip-handler'),
    ],
];
