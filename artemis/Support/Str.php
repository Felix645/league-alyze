<?php


namespace Artemis\Support;


class Str
{
    /**
     * Gets the first match for the given pattern.
     *
     * @param string $pattern Search Pattern.
     * @param string $subject String to be searched.
     *
     * @return false|string False on error or if the pattern was not found. The first match otherwise.
     */
    public static function match(string $pattern, string $subject)
    {
        if( preg_match($pattern, $subject, $matches) ) {
            return $matches[1];
        }

        return false;
    }

    /**
     * Checks if the given string containts a sub-string that starts and end with the given characters.
     * Returns the first matched string that that was found.
     *
     * @param string $subject   String to be searched.
     * @param string $start     Begging of the search string.
     * @param string $end       End of the search string.
     *
     * @return false|string Returns the first matched string (without $start and $end) that is found. False otherwise.
     */
    public static function matchEnclosedPattern(string $subject, string $start, string $end)
    {
        if( preg_match( '#' . $start . '(.+?)' . $end . '#si', $subject, $content ) ) {
            return $content[1];
        }

        return false;
    }

    /**
     * Replaces a given needle with given replacement in the given string.
     *
     * @param string|string[] $needle   String that should be replaced.
     * @param string|string[] $replace  Replacement for the needle.
     * @param string|string[] $haystack String that needs to be searched.
     *
     * @return array|string|string[]
     */
    public static function replace($needle, $replace, $haystack)
    {
        return str_replace($needle, $replace, $haystack);
    }

    /**
     * Replaces the given content that is between the given start and end with the given replacement.
     *
     * @param string $start     Start of the content that is to be replaced.
     * @param string $end       End of the content that is to be replaced.
     * @param string $replace   String that should replace the content.
     * @param string $content   Subject string.
     *
     * @return array|string|string[]|null
     */
    public static function replaceBetween(string $start, string $end, string $replace, string $content)
    {
        return preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$replace.'$3', $content);
    }

    /**
     * Translates the given string with the given character lists.
     *
     * @param string $string    String that is to be translated
     * @param string $from      Character list that is to be transformed from.
     * @param string $to        Character list that is to be translated to.
     *
     * @return string Translated string.
     */
    public static function translate(string $string, string $from, string $to) : string
    {
        return strtr($string, $from, $to);
    }

    /**
     * Transform a string to lower case.
     *
     * @param string $string String to be transformed.
     *
     * @return string Transformed string.
     */
    public static function lower(string $string) : string
    {
        return strtolower($string);
    }

    /**
     * Transforms a string to upper case.
     *
     * @param string $string String to be transformed
     *
     * @return string Transformed string.
     */
    public static function upper(string $string) : string
    {
        return strtoupper($string);
    }

    /**
     * Uppercase the first character of each word in a string.
     *
     * @param string $string        The input string.
     * @param string $separators    The optional separators contains the word separator characters.
     *
     * @return string Transformed string.
     */
    public static function upperWords(string $string, string $separators = " \t\r\n\f\v") : string
    {
        return ucwords($string, $separators);
    }

    /**
     * Return part of a string.
     *
     * @param string $string    Input string.
     * @param int $offset       Offset value.
     * @param int|null $length  String length.
     *
     * @return false|string Extracted part of the string. False on failure.
     */
    public static function sub(string $string, int $offset, ?int $length = null)
    {
        return substr($string, $offset, $length);
    }

    /**
     * Trims the string from left and right until it finds a character that is NOT present in the character's parameter.
     *
     * @param string $string        String to be trimmed.
     * @param string $characters    Characters to be trimmed.
     *
     * @return string Trimmed string
     */
    public static function trim(string $string, string $characters = " \t\n\r\0\x0B") : string
    {
        return trim($string, $characters);
    }

    /**
     * Trims the string from left until it finds a character that is NOT present in the character's parameter.
     *
     * @param string $string        String to be trimmed.
     * @param string $characters    Characters to be trimmed.
     *
     * @return string Trimmed string
     */
    public static function trimLeft(string $string, string $characters = " \t\n\r\0\x0B") : string
    {
        return ltrim($string, $characters);
    }

    /**
     * Trims the string from right until it finds a character that is NOT present in the character's parameter.
     *
     * @param string $string        String to be trimmed.
     * @param string $characters    Characters to be trimmed.
     *
     * @return string Trimmed string
     */
    public static function trimRight(string $string, string $characters = " \t\n\r\0\x0B") : string
    {
        return rtrim($string, $characters);
    }

    /**
     * Checks if the given needle exists within the subject.
     *
     * @param string $subject           String that is searched.
     * @param string|int|float $needle  String to be searched for.
     *
     * @return bool TRUE if the string exists. FALSE if it does not.
     */
    public static function contains(string $subject, $needle) : bool
    {
        return strpos($subject, $needle) !== FALSE;
    }

    /**
     * Checks if the given subject starts with the given needle.
     *
     * @param string $subject           String that is searched.
     * @param string|int|float $needle  String that the subject should start with.
     *
     * @return bool TRUE if the subject starts with the needle provided. FALSE otherwise.
     */
    public static function startsWith(string $subject, $needle) : bool
    {
        return strpos($subject, $needle) === 0;
    }
}