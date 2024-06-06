<?php

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