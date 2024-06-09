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

namespace ReinanHS\Test;

use PHPUnit\Framework\TestCase;
use ReinanHS\SqlCommenterHyperf\SwitchManager;

/**
 * @internal
 * @coversNothing
 */
class SwitchManagerTest extends TestCase
{
    public function testDefaultConfig()
    {
        $switchManager = new SwitchManager();
        $this->assertTrue($switchManager->isEnable('framework'));
        $this->assertTrue($switchManager->isEnable('controller'));
        $this->assertTrue($switchManager->isEnable('action'));
        $this->assertTrue($switchManager->isEnable('route'));
        $this->assertTrue($switchManager->isEnable('application'));
        $this->assertTrue($switchManager->isEnable('db_driver'));
        $this->assertTrue($switchManager->isEnable('traceparent'));
    }

    public function testApplyConfig()
    {
        $switchManager = new SwitchManager();
        $config = [
            'framework' => false,
            'controller' => false,
        ];
        $switchManager->apply($config);

        $this->assertFalse($switchManager->isEnable('framework'));
        $this->assertFalse($switchManager->isEnable('controller'));
        $this->assertTrue($switchManager->isEnable('action'));
        $this->assertTrue($switchManager->isEnable('route'));
        $this->assertTrue($switchManager->isEnable('application'));
        $this->assertTrue($switchManager->isEnable('db_driver'));
        $this->assertTrue($switchManager->isEnable('traceparent'));
    }

    public function testApplyPartialConfig()
    {
        $switchManager = new SwitchManager();
        $config = [
            'action' => false,
            'route' => true,
        ];
        $switchManager->apply($config);

        $this->assertTrue($switchManager->isEnable('framework'));
        $this->assertTrue($switchManager->isEnable('controller'));
        $this->assertFalse($switchManager->isEnable('action'));
        $this->assertTrue($switchManager->isEnable('route'));
        $this->assertTrue($switchManager->isEnable('application'));
        $this->assertTrue($switchManager->isEnable('db_driver'));
        $this->assertTrue($switchManager->isEnable('traceparent'));
    }

    public function testIsEnableWithInvalidIdentifier()
    {
        $switchManager = new SwitchManager();
        $this->assertFalse($switchManager->isEnable('invalid'));
    }
}
