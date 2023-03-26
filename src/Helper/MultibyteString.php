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
     * @param string $string   The input string to modify
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return string The modified string
     */
    function mb_ucwords(string $string, string $encoding = 'UTF-8'): string
    {
        $result = '';
        $previous_character = ' ';

        $length = mb_strlen($string, $encoding);
        for ($i = 0; $i < $length; ++$i) {
            $current_character = mb_substr($string, $i, 1, $encoding);

            if (' ' === $previous_character) {
                $current_character = mb_strtoupper($current_character, $encoding);
            }

            $result .= $current_character;
            $previous_character = $current_character;
        }

        return $result;
    }
}

if (!function_exists('mb_ucfirst')) {
    /**
     * Make the first character of a string uppercase.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string   The input string
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return string The resulting string
     */
    function mb_ucfirst(string $string, string $encoding = 'UTF-8'): string
    {
        $first_char = mb_substr($string, 0, 1, $encoding);
        $rest = mb_substr($string, 1, null, $encoding);

        $lower_first_char = mb_strtolower($first_char, $encoding);
        if ($first_char === $lower_first_char) {
            $first_char = mb_strtoupper($first_char, $encoding);
        }

        return $first_char . $rest;
    }
}

if (!function_exists('mb_strrev')) {
    /**
     * Reverse a string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string   The string to be reversed
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
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
     * @param string $input      The string to pad
     * @param int    $pad_length The length of the resulting padded string
     * @param string $pad_string [optional] The string to use for padding, defaults to ' '
     *                           The pad_string may be truncated if the required number of padding
     *                           characters can't be evenly divided by the pad_string's length
     * @param int    $pad_type   [optional] The type of padding to apply, defaults to STR_PAD_RIGHT
     *                           Can be STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
     * @param string $encoding   [optional] The character encoding. Defaults to 'UTF-8'
     *
     * @return string The padded string
     *
     * @throws ValueError If $pad_type is not STR_PAD_RIGHT, STR_PAD_LEFT, or STR_PAD_BOTH
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
     * Returns information about characters used in a string.
     *
     * @see https://github.com/MathiasReker/php-mbstring-extension
     *
     * @param string $string   The string to be examined
     * @param int    $mode     Specifies what information to return.
     *                         - 0: Returns an array with the byte-value as key and the frequency of
     *                         every byte as value.
     *                         - 1: Same as 0 but only byte-values with a frequency greater than zero are listed.
     *                         - 2: Same as 0 but only byte-values with a frequency equal to zero are listed.
     *                         - 3: Returns a string containing all unique characters in the string.
     *                         - 4: Returns a string containing all characters in the string that are not used.
     * @param string $encoding [optional] The character encoding. Defaults to 'UTF-8'.
     *
     * @return int[]|string Returns the information requested based on the mode parameter:
     *                      - Mode 0, 1, or 2: returns an array with byte-values as keys and frequencies as values.
     *                      - Mode 3 or 4: returns a string with unique characters or unused characters.
     *
     * @throws ValueError if the mode parameter is not between 0 and 4 (inclusive)
     */
    function mb_count_chars(string $string, int $mode, string $encoding = 'UTF-8'): array|string
    {
        $length = mb_strlen($string, $encoding);
        $char_counts = [];

        for ($i = 0; $i < $length; ++$i) {
            $char = mb_substr($string, $i, 1, $encoding);

            if (!array_key_exists($char, $char_counts)) {
                $char_counts[$char] = 0;
            }

            ++$char_counts[$char];
        }

        return match ($mode) {
            0 => $char_counts,
            1 => array_filter($char_counts, static fn ($count): bool => $count > 0),
            2 => array_filter($char_counts, static fn ($count): bool => 0 === $count),
            3 => implode('', array_unique(mb_str_split($string, 1, $encoding))),
            4 => implode('', array_filter(array_unique(mb_str_split($string, 1, $encoding)), static fn ($char): bool => 0 === $char_counts[$char])),
            default => throw new ValueError('Argument #2 ($mode) must be between 0 and 4 (inclusive)'),
        };
    }
}
