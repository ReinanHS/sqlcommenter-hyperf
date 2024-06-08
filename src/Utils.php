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

class Utils
{
    /**
     * Method responsible for formatting a list of comments.
     */
    public static function formatComments(array $comments): string
    {
        if (empty($comments)) {
            return '';
        }

        return '/*' . implode(
            ',',
            array_map(
                static fn (string $value, string $key) => Utils::customUrlEncode($key) . "='" . Utils::customUrlEncode($value) . "'",
                $comments,
                array_keys($comments)
            ),
        ) . '*/';
    }

    /**
     * Custom URL encoding to escape '%' characters for SQL compatibility.
     */
    private static function customUrlEncode(string $input): string
    {
        $encodedString = urlencode($input);

        return str_replace('%', '%%', $encodedString);
    }
}
