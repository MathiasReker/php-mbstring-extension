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

final class MbUcwordsTest extends TestCase
{
    public function testUppercaseFirstCharacter(): void
    {
        $this->assertEquals('Hello World', mb_ucwords('hello world'));
        $this->assertEquals('Åäö', mb_ucwords('åäö'));
        $this->assertEquals('Åäö Öäå', mb_ucwords('åäö öäå'));
    }

    public function testUppercaseAllWords(): void
    {
        $this->assertEquals('The Quick Brown Fox', mb_ucwords('the quick brown fox'));
    }

    public function testHandlesNonASCIICharacters(): void
    {
        $this->assertEquals('Ça Va Bien', mb_ucwords('ça va bien'));
    }

    public function testEmptyString(): void
    {
        $this->assertEquals('', mb_ucwords(''));
    }
}
