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

class SwitchManager
{
    private array $config = [
        'framework' => true,
        'controller' => true,
        'action' => true,
        'route' => true,
        'application' => true,
        'db_driver' => true,
        'traceparent' => true,
    ];

    /**
     * Apply the configuration to SwitchManager.
     */
    public function apply(array $config): void
    {
        $this->config = array_replace($this->config, $config);
    }

    /**
     * Responsible method for checking whether a configuration is active in the include property.
     */
    public function isEnable(string $identifier): bool
    {
        if (! isset($this->config[$identifier])) {
            return false;
        }

        return (bool) $this->config[$identifier];
    }
}
