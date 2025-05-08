<?php
/**
 * This file is part of the php-mbstring-extension package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MathiasReker\PhpMbFunctions;

final class Mbstring implements MbstringInterface
{
    public static function levenshtein(
        string $string1,
        string $string2,
        int $costIns = 1,
        int $costRep = 1,
        int $costDel = 1
    ): int {
        $charMap = [];
        self::convertMbAscii($string1, $charMap);
        self::convertMbAscii($string2, $charMap);

        return levenshtein($string1, $string2, $costIns, $costRep, $costDel);
    }

    /**
     * Convert a UTF-8 encoded string to a single-byte string suitable for
     * functions such as levenshtein.
     *
     * The function simply uses (and updates) a tailored dynamic encoding
     * (in/out map parameter) where non-ascii characters are remapped to
     * the range [128-255] in order of appearance.
     *
     * Thus it supports up to 128 different multibyte code points max over
     * the whole set of strings sharing this encoding.
     *
     * @param string                $string UTF-8 string to be converted to extended ASCII
     * @param array<string, string> $map    reference of the map, associative array where keys are
     *                                      multibyte characters and values are their corresponding
     *                                      single-byte representations
     */
    private static function convertMbAscii(string &$string, array &$map): void
    {
        $matches = [];
        if (false === preg_match_all(
            '/[\xC0-\xF7][\x80-\xBF]+/',
            $string,
            $matches
        ) || empty($matches[0])
        ) {
            return;
        }

        $mapCount = \count($map);
        foreach ($matches[0] as $mbc) {
            if (!isset($map[$mbc])) {
                $map[$mbc] = \chr(128 + $mapCount);
                ++$mapCount;
            }
        }

        $string = strtr($string, $map);
    }

    public static function ucwords(string $string, string $separators = " \t\r\n\f\v", string $encoding = 'UTF-8'): string
    {
        $escaped = preg_quote($separators, '/');
        $pattern = '/(^|[' . $escaped . '])(\p{L})/u';

        return preg_replace_callback($pattern, fn ($matches): string => $matches[1] . mb_strtoupper($matches[2], $encoding), $string) ?? "";
    }

    public static function ucfirst(string $string, string $encoding = 'UTF-8'): string
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);

        $lowerFirstChar = mb_strtolower($firstChar, $encoding);
        if ($firstChar === $lowerFirstChar) {
            $firstChar = mb_strtoupper($firstChar, $encoding);
        }

        return $firstChar . $rest;
    }

    public static function strrev(string $string, string $encoding = 'UTF-8'): string
    {
        $length = mb_strlen($string, $encoding);
        $reversed = '';

        while ($length-- > 0) {
            $reversed .= mb_substr($string, $length, 1, $encoding);
        }

        return $reversed;
    }

    public static function count_chars(
        string $string,
        int $mode,
        string $encoding = 'UTF-8'
    ): array|string {
        $length = mb_strlen($string, $encoding);
        $charCounts = [];

        for ($i = 0; $i < $length; ++$i) {
            $char = mb_substr($string, $i, 1, $encoding);

            if (!\array_key_exists($char, $charCounts)) {
                $charCounts[$char] = 0;
            }

            ++$charCounts[$char];
        }

        return match ($mode) {
            0 => $charCounts,
            1 => array_filter(
                $charCounts,
                static fn ($count): bool => $count > 0
            ),
            2 => array_filter(
                $charCounts,
                static fn ($count): bool => 0 === $count
            ),
            3 => implode(
                '',
                array_unique(mb_str_split($string, 1, $encoding))
            ),
            4 => implode(
                '',
                array_filter(
                    array_unique(
                        mb_str_split($string, 1, $encoding)
                    ),
                    static fn ($char): bool => 0 === $charCounts[$char]
                )
            ),
            default => throw new \ValueError('Argument #2 ($mode) must be between 0 and 4 (inclusive)'),
        };
    }

    public static function trim(
        string $str,
        string $charlist = " \t\n\r\0\x0B"
    ): string {
        $pattern = '/^[' . preg_quote($charlist, '/') . '\p{Zs}\p{Zl}\p{Zp}]+|['
            . preg_quote($charlist, '/') . '\p{Zs}\p{Zl}\p{Zp}]+$/u';

        return (string) preg_replace($pattern, '', $str);
    }
}
