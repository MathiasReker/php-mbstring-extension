<?php
/**
 * This file is part of the php-mbstring-extension package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpMbFunctions\Tests\Unit;

use MathiasReker\PhpMbFunctions\Mbstring;
use PHPUnit\Framework\TestCase;

final class MbUcwordsTest extends TestCase
{
    public function testUppercaseFirstCharacter(): void
    {
        $this->assertEquals('Hello World', Mbstring::ucwords('hello world'));
        $this->assertEquals('Åäö', Mbstring::ucwords('åäö'));
        $this->assertEquals('Åäö Öäå', Mbstring::ucwords('åäö öäå'));
    }

    public function testUppercaseAllWords(): void
    {
        $this->assertEquals('The Quick Brown Fox', Mbstring::ucwords('the quick brown fox'));
        $this->assertEquals('The QUiCK BrOwn FoX', Mbstring::ucwords('the qUiCK brOwn foX'));
        $this->assertEquals('The-Quick-Brown-Fox', Mbstring::ucwords('the-quick-brown-fox', '-'));
    }

    public function testHandlesNonASCIICharacters(): void
    {
        $this->assertEquals('Ça Va Bien', Mbstring::ucwords('ça va bien'));
    }

    public function testEmptyString(): void
    {
        $this->assertEquals('', Mbstring::ucwords(''));
    }
}
