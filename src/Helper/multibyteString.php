<?php
/**
 * This file is part of the php-mbstring-extension package.
 * (c) Mathias Reker <github@reker.dk>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

if (!function_exists('mb_ucwords')) {
    /**
     * Uppercase the first character of each word in a string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string
     *                         The input string
     * @param string $encoding [optional]
     *
     * @return string the modified string
     */
    function mb_ucwords(string $string, string $encoding = 'UTF-8'): string
    {
        $result = '';
        $previousCharacter = ' ';

        for ($i = 0; $i < mb_strlen($string, $encoding); ++$i) {
            $currentCharacter = mb_substr($string, $i, 1, $encoding);

            if (' ' === $previousCharacter) {
                $currentCharacter = mb_strtoupper($currentCharacter, $encoding);
            }

            $result .= $currentCharacter;
            $previousCharacter = $currentCharacter;
        }

        return $result;
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     * Make a string's first character uppercase.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string
     *                         The input string
     * @param string $encoding [optional]
     *
     * @return string the resulting string
     */
    function mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
    {
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $rest;
    }
}

if (!function_exists('mb_strrev')) {
    /**
     * Reverse a string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string
     *                         The string to be reversed
     * @param string $encoding [optional]
     *
     * @return string the reversed string
     */
    function mb_strrev(string $string, string $encoding = 'UTF-8'): string
    {
        $length = mb_strlen($string, $encoding);
        $reversed = '';

        while ($length-- > 0) {
            $reversed .= mb_substr($string, $length, 1, $encoding);
        }

        return $reversed;
    }
}

if (!function_exists('mb_str_pad')) {
    /**
     * Pad a string to a certain length with another string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $pad_string [optional]
     *                           The pad_string may be truncated if the
     *                           required number of padding characters can't be evenly divided by the
     *                           pad_string's length
     * @param int    $pad_type   [optional]
     *                           Optional argument pad_type can be
     *                           STR_PAD_RIGHT, STR_PAD_LEFT,
     *                           or STR_PAD_BOTH. If
     *                           pad_type is not specified it is assumed to be
     *                           STR_PAD_RIGHT.
     * @param string $encoding   [optional]
     *
     * @return string the padded string
     */
    function mb_str_pad(string $input, int $pad_length, string $pad_string = ' ', int $pad_type = \STR_PAD_RIGHT, string $encoding = 'UTF-8'): string
    {
        if (!in_array($pad_type, [\STR_PAD_RIGHT, \STR_PAD_LEFT, \STR_PAD_BOTH], true)) {
            throw new ValueError('Argument #4 ($pad_type) must be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH');
        }

        return str_pad($input, strlen($input) - mb_strlen($input, $encoding) + $pad_length, $pad_string, $pad_type);
    }
}

if (!function_exists('mb_count_chars')) {
    /**
     * Return information about characters used in a string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string
     *                         The examined string
     * @param int    $mode
     *                         See return values
     * @param string $encoding [optional]
     *
     * @return array<int<0, max>>|string Depending on mode
     *                                   count_chars returns one of the following:
     *                                   0 - an array with the byte-value as key and the frequency of
     *                                   every byte as value.
     *                                   1 - same as 0 but only byte-values with a frequency greater
     *                                   than zero are listed.
     *                                   2 - same as 0 but only byte-values with a frequency equal to
     *                                   zero are listed.
     *                                   3 - a string containing all unique characters is returned.
     *                                   4 - a string containing all not used characters is returned.
     */
    function mb_count_chars(string $string, int $mode, string $encoding = 'UTF-8'): array|string
    {
        $length = mb_strlen($string, $encoding);
        $charCounts = [];

        for ($i = 0; $i < $length; ++$i) {
            $char = mb_substr($string, $i, 1, $encoding);

            if (!array_key_exists($char, $charCounts)) {
                $charCounts[$char] = 0;
            }

            ++$charCounts[$char];
        }

        return match ($mode) {
            0 => $charCounts,
            1 => array_filter($charCounts, static fn ($count): bool => $count > 0),
            2 => array_filter($charCounts, static fn ($count): bool => 0 === $count),
            3 => implode('', array_unique(mb_str_split($string, 1, $encoding))),
            4 => implode('', array_filter(array_unique(mb_str_split($string, 1, $encoding)), static fn ($char): bool => 0 === $charCounts[$char])),
            default => throw new ValueError('Argument #2 ($mode) must be between 0 and 4 (inclusive)'),
        };
    }
}
