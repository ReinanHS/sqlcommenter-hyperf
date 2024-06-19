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

namespace ReinanHS\Test\Aspect;

use FastRoute\Dispatcher;
use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Connection;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Request;
use Hyperf\HttpServer\Router\Dispatched;
use Hyperf\HttpServer\Router\Handler;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Psr\Log\LoggerInterface;
use ReinanHS\SqlCommenterHyperf\Aspect\SqlCommenterAspect;
use ReinanHS\SqlCommenterHyperf\Opentelemetry;
use ReinanHS\SqlCommenterHyperf\SwitchManager;

/**
 * @internal
 * @coversNothing
 */
class SqlCommenterAspectTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    public function testProcess(): void
    {
        $mockedConfig = $this->createMock(ConfigInterface::class);
        $mockedConfig->expects($this->exactly(2))
            ->method('get')
            ->willReturnOnConsecutiveCalls(true, 'TestApp');

        $mockedSwitchManager = $this->createMock(SwitchManager::class);
        $mockedSwitchManager->expects($this->exactly(8))
            ->method('isEnable')
            ->willReturn(true);

        $mockedLogger = $this->createMock(LoggerInterface::class);

        $mockedProceedingJoinPoint = $this->getMockBuilder(ProceedingJoinPoint::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockedRequest = $this->createMock(Request::class);
        $mockedRequest->expects($this->once())
            ->method('getAttribute')
            ->with(Dispatched::class)
            ->willReturn(new Dispatched([
                Dispatcher::FOUND,
                new Handler(['app/Controller/IndexController.php', 'index'], ''),
                [],
            ]));

        $mockedUri = $this->createMock(UriInterface::class);
        $mockedUri->expects($this->once())
            ->method('getPath')
            ->willReturn('/v1/admin/index');

        $mockedRequest->expects($this->once())
            ->method('getUri')
            ->willReturn($mockedUri);

        $mockedContext = Mockery::mock('alias:' . Context::class);
        $mockedContext->shouldReceive('get')
            ->with(ServerRequestInterface::class)
            ->andReturn($mockedRequest);

        $mockedOpentelemetry = Mockery::mock('alias:' . Opentelemetry::class);
        $mockedOpentelemetry->shouldReceive('getOpentelemetryValues')
            ->andReturn([
                'traceparent' => '00-5bd66ef5095369c7b0d1f8f4bd33716a-c532cb4098ac3dd2-01',
            ]);

        $mockedProceedingJoinPoint->arguments = [
            'keys' => [
                'query' => 'SELECT CURRENT_TIMESTAMP()',
            ],
        ];

        $mockedConnection = $this->createMock(Connection::class);
        $mockedConnection->expects($this->once())
            ->method('getDriverName')
            ->willReturn('mysql');

        $mockedProceedingJoinPoint->expects($this->once())
            ->method('getInstance')
            ->willReturn($mockedConnection);

        $mockedProceedingJoinPoint->expects($this->once())
            ->method('process')
            ->willReturn(true);

        $aspect = new SqlCommenterAspect($mockedConfig, $mockedSwitchManager, $mockedLogger);
        $result = $aspect->process($mockedProceedingJoinPoint);

        $query = $mockedProceedingJoinPoint->arguments['keys']['query'];

        $this->assertStringContainsString('SELECT CURRENT_TIMESTAMP()', $query);
        $this->assertStringContainsString("application='TestApp'", $query);
        $this->assertStringContainsString("framework='hyperf'", $query);
        $this->assertStringContainsString("application='TestApp'", $query);
        $this->assertStringContainsString("db_driver='mysql'", $query);
        $this->assertStringContainsString("route='%%2Fv1%%2Fadmin%%2Findex'", $query);
        $this->assertStringContainsString("controller='IndexController'", $query);
        $this->assertStringContainsString("action='index'", $query);
        $this->assertStringContainsString("traceparent='00-5bd66ef5095369c7b0d1f8f4bd33716a-c532cb4098ac3dd2-01'", $query);
        $this->assertTrue($result);
    }
}
