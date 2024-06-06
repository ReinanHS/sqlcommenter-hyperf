<?php

namespace ReinanHS\SqlCommenterHyperf;

class Utils
{
    /**
     * Method responsible for formatting a list of comments
     * @param array $comments
     * @return string
     */
    public static function formatComments(array $comments): string
    {
        if (empty($comments)) {
            return "";
        }

        return "/*" . implode(
                ',',
                array_map(
                    static fn(string $value, string $key) => Utils::customUrlEncode($key) . "='" . Utils::customUrlEncode($value) . "'", $comments,
                    array_keys($comments)
                ),
            ) . "*/";
    }

    /**
     * Custom URL encoding to escape '%' characters for SQL compatibility.
     * @param string $input
     * @return string
     */
    private static function customUrlEncode(string $input): string
    {
        $encodedString = urlencode($input);

        return str_replace("%", "%%", $encodedString);
    }
}