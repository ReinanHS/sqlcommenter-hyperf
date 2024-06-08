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

namespace ReinanHS\SqlCommenterHyperf;

use ReinanHS\SqlCommenterHyperf\Factory\SwitchManagerFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
                SwitchManager::class => SwitchManagerFactory::class,
            ],
            'listeners' => [],
            'annotations' => [],
            'aspects' => [],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for SqlCommenter.',
                    'source' => __DIR__ . '/../publish/sqlcommenter.php',
                    'destination' => BASE_PATH . '/config/autoload/sqlcommenter.php',
                ],
            ],
        ];
    }
}
