<?php

namespace ReinanHS\Test\Aspect;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Connection;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use PHPUnit\Framework\TestCase;
use ReinanHS\SqlCommenterHyperf\Aspect\SqlCommenterAspect;

class SqlCommenterAspectTest extends TestCase
{

    public function testProcess()
    {
        $mockedConfig = $this->createMock(ConfigInterface::class);

        $mockedPoint = $this->getMockBuilder(ProceedingJoinPoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockedPoint->arguments = [
            'keys' => [
                'query' => 'SELECT CURRENT_TIMESTAMP()'
            ]
        ];

        $mockedConnection = $this->createMock(Connection::class);
        $mockedConnection->expects($this->once())
            ->method('getDriverName')
            ->willReturn('mysql');

        $mockedPoint->expects($this->once())
            ->method('getInstance')
            ->willReturn($mockedConnection);

        $aspect = new SqlCommenterAspect($mockedConfig);
        $aspect->process($mockedPoint);

        $query = $mockedPoint->arguments['keys']['query'];

        $this->assertStringContainsString('SELECT CURRENT_TIMESTAMP()', $query);
        $this->assertStringContainsString("framework='hyperf'", $query);
        $this->assertStringContainsString("controller='Teste'", $query);
        $this->assertStringContainsString("action='index'", $query);
    }
}
