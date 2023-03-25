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

final class MbCountCharsTest extends TestCase
{
    public function testCountCharsMode0(): void
    {
        $string = 'Hello, world!';
        $expected = [
            'H' => 1,
            'e' => 1,
            'l' => 3,
            'o' => 2,
            ',' => 1,
            ' ' => 1,
            'w' => 1,
            'r' => 1,
            'd' => 1,
            '!' => 1,
        ];
        $result = mb_count_chars($string, 0);
        $this->assertEquals($expected, $result);
    }

    public function testCountCharsMode1(): void
    {
        $string = 'Hello, world!';
        $expected = [
            'H' => 1,
            'e' => 1,
            'l' => 3,
            'o' => 2,
            ',' => 1,
            ' ' => 1,
            'w' => 1,
            'r' => 1,
            'd' => 1,
            '!' => 1,
        ];
        $result = mb_count_chars($string, 1);
        $this->assertEquals($expected, $result);
    }

    public function testCountCharsMode2(): void
    {
        $string = 'Hello, world!';
        $expected = [];
        $result = mb_count_chars($string, 2);
        $this->assertEquals($expected, $result);
    }

    public function testCountCharsMode3(): void
    {
        $string = 'Hello, world!';
        $expected = 'Helo, wrd!';
        $result = mb_count_chars($string, 3);
        $this->assertEquals($expected, $result);
    }

    public function testCountCharsMode4(): void
    {
        $string = 'Hello, world!';
        $expected = '';
        $result = mb_count_chars($string, 4);
        $this->assertEquals($expected, $result);
    }

    public function testInvalidModeThrowsValueError(): void
    {
        $this->expectException(\ValueError::class);

        $string = 'Hello, world!';
        $mode = 5;
        $encoding = 'UTF-8';

        mb_count_chars($string, $mode, $encoding);
    }
}
