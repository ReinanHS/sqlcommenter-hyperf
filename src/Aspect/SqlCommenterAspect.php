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

namespace ReinanHS\SqlCommenterHyperf\Aspect;

use Hyperf\Context\Context;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Connection;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;
use Hyperf\HttpServer\Router\Dispatched;
use Psr\Http\Message\ServerRequestInterface;
use ReinanHS\SqlCommenterHyperf\Opentelemetry;
use ReinanHS\SqlCommenterHyperf\SwitchManager;
use ReinanHS\SqlCommenterHyperf\Utils;

class SqlCommenterAspect extends AbstractAspect
{
    public array $classes = [
        'Hyperf\Database\Connection::runQueryCallback',
    ];

    public function __construct(private readonly ConfigInterface $config, private readonly SwitchManager $switchManager)
    {
    }

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        if (! $this->config->get('sqlcommenter.enable', true)) {
            return $proceedingJoinPoint->process();
        }

        $query = $proceedingJoinPoint->arguments['keys']['query'];

        /** @var Connection $dbInstance */
        $dbInstance = $proceedingJoinPoint->getInstance();

        $proceedingJoinPoint->arguments['keys']['query'] = $this->appendSqlComments($query, $dbInstance->getDriverName());

        return $proceedingJoinPoint->process();
    }

    private function appendSqlComments(string $query, string $dbDriver): string
    {
        $comments = [];

        if ($this->switchManager->isEnable('framework')) {
            $comments['framework'] = 'hyperf';
        }

        if ($this->switchManager->isEnable('application')) {
            $comments['application'] = $this->config->get('app_name');
        }

        if ($this->switchManager->isEnable('db_driver')) {
            $comments['db_driver'] = $dbDriver;
        }

        $request = Context::get(ServerRequestInterface::class);
        if ($request) {
            if ($this->switchManager->isEnable('route')) {
                $comments['route'] = $request->getUri()->getPath();
            }

            $dispatched = $request->getAttribute(Dispatched::class);
            $callback = $dispatched->handler->callback;

            if ($this->switchManager->isEnable('controller') && is_array($callback)) {
                $mapController = explode('/', $callback[0]);
                $comments['controller'] = end($mapController);
            }

            if ($this->switchManager->isEnable('action') && is_array($callback)) {
                $comments['action'] = $callback[1] ?? '';
            }
        }

        if ($this->switchManager->isEnable('traceparent')) {
            $values = Opentelemetry::getOpentelemetryValues();
            $comments = $comments + $values;
        }

        $query = trim($query);
        $hasSemicolon = $query[-1] === ';';
        $query = rtrim($query, ';');

        return $query . Utils::formatComments(array_filter($comments)) . ($hasSemicolon ? ';' : '');
    }
}
