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

use Hyperf\Tracer\TracerContext;
use OpenTracing\Span;

use const OpenTracing\Formats\TEXT_MAP;

class Opentelemetry
{
    /**
     * Retrieves OpenTelemetry values and converts B3 context to W3C TraceContext.
     *
     * @return array an array containing the W3C TraceContext formatted traceparent
     */
    public static function getOpentelemetryValues(): array
    {
        $root = TracerContext::getRoot();
        if ($root instanceof Span) {
            $appendContext = [];

            TracerContext::getTracer()->inject(
                spanContext: $root->getContext(),
                format: TEXT_MAP,
                carrier: $appendContext
            );

            if ($appendContext && isset($appendContext['x-b3-traceid'])) {
                /** @psalm-suppress PossiblyInvalidArgument */
                $traceparent = self::convertB3ToW3C($appendContext);

                return ['traceparent' => $traceparent];
            }

            if ($appendContext && isset($appendContext['uber-trace-id'])) {
                /** @psalm-suppress PossiblyInvalidArgument */
                $traceparent = self::convertUberTraceIdToTraceparent($appendContext);

                return ['traceparent' => $traceparent];
            }
        }

        return [];
    }

    /**
     * Converts B3 context headers to W3C TraceContext format.
     *
     * @param array $b3Context an array containing B3 context headers
     * @return string the W3C TraceContext formatted traceparent
     */
    private static function convertB3ToW3C(array $b3Context): string
    {
        $traceId = str_pad((string) $b3Context['x-b3-traceid'], 32, '0', STR_PAD_LEFT);
        $spanId = str_pad((string) $b3Context['x-b3-spanid'], 16, '0', STR_PAD_LEFT);
        $sampled = $b3Context['x-b3-sampled'] === '1' ? '01' : '00';

        // W3C Traceparent format
        $version = '00';
        return "{$version}-{$traceId}-{$spanId}-{$sampled}";
    }

    /**
     * Converts an Uber Trace ID to a W3C Traceparent format.
     *
     * @param array $uberContext An associative array containing the Uber Trace ID with the key 'uber-trace-id'.
     *                           Example: ['uber-trace-id' => '7316935077496437249:658af9afaac53a01:0:0']
     *
     * @return string The W3C Traceparent formatted string.
     *                Example: '00-00000000000007316935077496437249-658af9afaac53a01-00'
     */
    private static function convertUberTraceIdToTraceparent(array $uberContext): string
    {
        [$traceId, $spanId, $parentSpanId, $flags] = explode(':', (string) $uberContext['uber-trace-id']);

        $traceId = str_pad($traceId, 32, '0', STR_PAD_LEFT);
        $spanId = str_pad($spanId, 16, '0', STR_PAD_LEFT);
        $sampled = $flags === '1' ? '01' : '00';

        // W3C Traceparent format
        $version = '00';
        return sprintf('%s-%s-%s-%s', $version, $traceId, $spanId, $sampled);
    }
}
