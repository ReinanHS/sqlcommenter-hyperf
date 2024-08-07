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

namespace ReinanHS\SqlCommenterHyperf\Factory;

use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use ReinanHS\SqlCommenterHyperf\SwitchManager;

class SwitchManagerFactory
{
    public function __invoke(ContainerInterface $container): SwitchManager
    {
        /** @var Config $config */
        $config = $container->get(ConfigInterface::class);

        $manager = new SwitchManager();

        /** @var array $sqlcommenterConfig */
        $sqlcommenterConfig = $config->get('sqlcommenter.include', []);

        $manager->apply($sqlcommenterConfig);
        return $manager;
    }
}
