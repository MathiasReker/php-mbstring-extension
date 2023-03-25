<?php
/**
 * This file is part of the php-mbstring-extension package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpMbFunctions\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class MbStrPadTest extends TestCase
{
    public function testPadsStringOnTheRightByDefault(): void
    {
        $this->assertEquals('hello     ', mb_str_pad('hello', 10));
        $this->assertEquals('world   ', mb_str_pad('world', 8));
        $this->assertEquals('foo  ', mb_str_pad('foo', 5));
        $this->assertEquals('æøå  ', mb_str_pad('æøå', 5));
    }

    public function testPadsStringOnTheLeft(): void
    {
        $this->assertEquals('-----hello', mb_str_pad('hello', 10, '-', \STR_PAD_LEFT));
        $this->assertEquals('---world', mb_str_pad('world', 8, '-', \STR_PAD_LEFT));
        $this->assertEquals('--foo', mb_str_pad('foo', 5, '-', \STR_PAD_LEFT));
        $this->assertEquals('--æøå', mb_str_pad('æøå', 5, '-', \STR_PAD_LEFT));
    }

    public function testPadsStringOnBothSides(): void
    {
        $this->assertEquals('--hello---', mb_str_pad('hello', 10, '-', \STR_PAD_BOTH));
        $this->assertEquals('-world--', mb_str_pad('world', 8, '-', \STR_PAD_BOTH));
        $this->assertEquals('-foo-', mb_str_pad('foo', 5, '-', \STR_PAD_BOTH));
        $this->assertEquals('-æøå-', mb_str_pad('æøå', 5, '-', \STR_PAD_BOTH));
    }

    public function testHandlesEncoding(): void
    {
        $this->assertEquals('-----hello', mb_str_pad('hello', 10, '-', \STR_PAD_LEFT, 'SJIS'));
    }

    public function testMbStrPadWithSpecialCharacters(): void
    {
        $input = 'øæå';
        $pad_length = 10;
        $pad_string = '-';
        $pad_type = \STR_PAD_BOTH;
        $encoding = 'UTF-8';

        $expected_output = str_pad($input, \strlen($input) - mb_strlen($input, $encoding) + $pad_length, $pad_string, $pad_type);

        $this->assertEquals($expected_output, mb_str_pad($input, $pad_length, $pad_string, $pad_type, $encoding));
    }

    public function testMbStrPadInvalidPadType(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #4 ($pad_type) must be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH');

        $input = 'Hello';
        $padLength = 10;
        $padString = '-';
        $padType = 5;

        mb_str_pad($input, $padLength, $padString, $padType);
    }
}
