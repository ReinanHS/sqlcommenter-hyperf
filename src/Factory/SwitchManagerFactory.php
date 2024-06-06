<?php

namespace ReinanHS\SqlCommenterHyperf\Factory;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;
use ReinanHS\SqlCommenterHyperf\SwitchManager;

class SwitchManagerFactory
{
    public function __invoke(ContainerInterface $container): SwitchManager
    {
        $config = $container->get(ConfigInterface::class);
        $manager = new SwitchManager();
        $manager->apply($config->get('sqlcommenter.include', []));

        return $manager;
    }
}