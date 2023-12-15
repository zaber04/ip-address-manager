<?php

return [
    'auth' => [
        'base_url' => env('AUTH_SERVICE_BASE_URL', 'http://127.0.0.1:8001'),
    ],
    'ip_handler' => [
        'base_url' => env('IP_HANDLER_SERVICE_BASE_URL', 'http://127.0.0.1:8002'),
    ],
];
