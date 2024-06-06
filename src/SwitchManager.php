<?php

namespace ReinanHS\SqlCommenterHyperf;

class SwitchManager
{
    private array $config = [
        'framework' => false,
        'controller' => false,
        'action' => false,
        'route' => false,
        'application' => false,
        'db_driver' => false,
        'traceparent' => false,
    ];

    /**
     * Apply the configuration to SwitchManager.
     * @param array $config
     * @return void
     */
    public function apply(array $config): void
    {
        $this->config = array_replace($this->config, $config);
    }

    /**
     * Responsible method for checking whether a configuration is active in the include property
     * @param string $identifier
     * @return bool
     */
    public function isEnable(string $identifier): bool
    {
        if (!isset($this->config[$identifier])) {
            return false;
        }

        return $this->config[$identifier];
    }
}