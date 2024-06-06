<?php

namespace ReinanHS\SqlCommenterHyperf;

use Hyperf\Tracer\TracerContext;
use OpenTracing\Span;
use const OpenTracing\Formats\TEXT_MAP;

class Opentelemetry
{
    /**
     * Retrieves OpenTelemetry values and converts B3 context to W3C TraceContext.
     *
     * @return array An array containing the W3C TraceContext formatted traceparent.
     */
    public static function getOpentelemetryValues(): array
    {
        $appendContext = [];

        $root = TracerContext::getRoot();
        if ($root instanceof Span) {

            TracerContext::getTracer()->inject(
                spanContext: $root->getContext(),
                format: TEXT_MAP,
                carrier: $appendContext
            );

            $traceparent = self::convertB3ToW3C($appendContext);

            return ['traceparent' => $traceparent];
        }

        return $appendContext;
    }

    /**
     * Converts B3 context headers to W3C TraceContext format.
     *
     * @param array $b3Context An array containing B3 context headers.
     * @return string The W3C TraceContext formatted traceparent.
     */
    private static function convertB3ToW3C(array $b3Context): string
    {
        $traceId = str_pad($b3Context['x-b3-traceid'], 32, '0', STR_PAD_LEFT);
        $spanId = str_pad($b3Context['x-b3-spanid'], 16, '0', STR_PAD_LEFT);
        $sampled = $b3Context['x-b3-sampled'] === '1' ? '01' : '00';

        // W3C Traceparent format
        $version = '00';
        $traceparent = "{$version}-{$traceId}-{$spanId}-{$sampled}";

        return $traceparent;
    }
}