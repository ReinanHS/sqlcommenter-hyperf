<?php

use function Hyperf\Support\env as env;

return [
    'include' => [
        'framework' => env('SQLCOMMENTER_ENABLE_FRAMEWORK', false),
        'controller' => env('SQLCOMMENTER_ENABLE_CONTROLLER', false),
        'action' => env('SQLCOMMENTER_ENABLE_ACTION', false),
        'route' => env('SQLCOMMENTER_ENABLE_ROUTE', false),
        'application' => env('SQLCOMMENTER_ENABLE_APPLICATION', false),
        'db_driver' => env('SQLCOMMENTER_ENABLE_DB_DRIVER', false),
    ],
];