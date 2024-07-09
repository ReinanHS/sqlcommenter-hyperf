<?php

declare(strict_types=1);
/**
 * This file is part of Sqlcommenter Hyperf.
 *
 * Sqlcommenter Hyperf provides an implementation of Sqlcommenter for the Hyperf framework,
 * allowing you to automatically add comments to your SQL queries to provide better insights
 * and traceability in your application's database interactions.
 *
 * @link     https://github.com/reinanhs/sqlcommenter-hyperf
 * @document https://github.com/reinanhs/sqlcommenter-hyperf/wiki
 * @license  https://github.com/reinanhs/sqlcommenter-hyperf/blob/main/LICENSE
 */
use function Hyperf\Support\env;

return [
    'enable' => env('SQLCOMMENTER_ENABLE', true),
    'include' => [
        'framework' => env('SQLCOMMENTER_ENABLE_FRAMEWORK', true),
        'controller' => env('SQLCOMMENTER_ENABLE_CONTROLLER', true),
        'action' => env('SQLCOMMENTER_ENABLE_ACTION', true),
        'route' => env('SQLCOMMENTER_ENABLE_ROUTE', true),
        'application' => env('SQLCOMMENTER_ENABLE_APPLICATION', true),
        'db_driver' => env('SQLCOMMENTER_ENABLE_DB_DRIVER', true),
    ],
];
