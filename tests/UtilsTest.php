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
use ReinanHS\SqlCommenterHyperf\Utils;

/**
 * @internal
 * @coversNothing
 */
class UtilsTest extends TestCase
{
    public function testFormatCommentsWithEmptyArray()
    {
        $comments = [];
        $expected = '';
        $this->assertEquals($expected, Utils::formatComments($comments));
    }

    public function testFormatCommentsWithSingleComment()
    {
        $comments = ['key' => 'value'];
        $expected = "/*key='value'*/";
        $this->assertEquals($expected, Utils::formatComments($comments));
    }

    public function testFormatCommentsWithMultipleComments()
    {
        $comments = ['key1' => 'value1', 'key2' => 'value2'];
        $expected = "/*key1='value1',key2='value2'*/";
        $this->assertEquals($expected, Utils::formatComments($comments));
    }

    public function testFormatCommentsWithSpecialCharacters()
    {
        $comments = ['key with spaces' => 'value/with/special characters'];
        $expected = "/*key+with+spaces='value%%2Fwith%%2Fspecial+characters'*/";
        $this->assertEquals($expected, Utils::formatComments($comments));
    }

    public function testFormatCommentsWithPercentCharacter()
    {
        $comments = ['key' => 'value%percent'];
        $expected = "/*key='value%%25percent'*/";
        $this->assertEquals($expected, Utils::formatComments($comments));
    }
}
