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

final class MbUcfirstTest extends TestCase
{
    public function testUppercasesFirstLetter(): void
    {
        $this->assertEquals('Hello world', mb_ucfirst('hello world'));
        $this->assertEquals('こんにちは', mb_ucfirst('こんにちは'));
        $this->assertEquals('안녕하세요', mb_ucfirst('안녕하세요'));
    }

    public function testHandlesMultibyteCharacters(): void
    {
        $this->assertEquals('Föö bär', mb_ucfirst('föö bär', 'ISO-8859-1'));
        $this->assertEquals('Σωκράτης', mb_ucfirst('σωκράτης'));
        $this->assertEquals('Åäö', mb_ucfirst('åäö'));
        $this->assertEquals('Åäö öäå', mb_ucfirst('åäö öäå'));
    }
}
