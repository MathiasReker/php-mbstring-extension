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

final class MbStrRevTest extends TestCase
{
    public function testReversesString(): void
    {
        $this->assertEquals('olleh', mb_strrev('hello'));
        $this->assertEquals('dlrow', mb_strrev('world'));
        $this->assertEquals('', mb_strrev(''));
    }

    public function testHandlesMultibyteCharacters(): void
    {
        $this->assertEquals('はちにんこ', mb_strrev('こんにちは'));
        $this->assertEquals('요세하녕안', mb_strrev('안녕하세요'));
        $this->assertEquals('界世，好你', mb_strrev('你好，世界'));
        $this->assertEquals('öäå', mb_strrev('åäö'));
        $this->assertEquals('öäÅ', mb_strrev('Åäö'));
    }

    public function testHandlesEncoding(): void
    {
        $this->assertEquals('olleh', mb_strrev('hello', 'SJIS'));
        $this->assertEquals('åØÆ', mb_strrev('ÆØå'));
    }
}
