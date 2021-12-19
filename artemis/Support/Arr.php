<?php


namespace Artemis\Support;


use Closure;

class Arr
{
    /**
     * Gets key/value pairs of the haystack which key exist in the given needle list.
     *
     * @param array $haystack   Haystack to be searched.
     * @param string[] $needles List of keys that need to be matched.
     *
     * @return array Resulting array. Returns an empty array if no key matches.
     */
    public static function intersectKeys(array $haystack, array $needles) : array
    {
        $result = [];

        foreach( $needles as $needle ) {
            if( !is_string($needle) ) {
                continue;
            }

            if( !isset($haystack[$needle]) ) {
                continue;
            }

            $result[$needle] = $haystack[$needle];
        }

        return $result;
    }

    /**
     * Removes the given keys from the given haystack.
     *
     * @param array $haystack   Haystack to be altered.
     * @param string[] $needles List of keys to be removed.
     *
     * @return array Resulting array. Returns the original haystack if no key matches.
     */
    public static function removeKeys(array $haystack, array $needles) : array
    {
        $result = $haystack;

        foreach( $haystack as $key => $value ) {
            if( !in_array($key, $needles) ) {
                continue;
            }

            unset($result[$key]);
        }

        return $result;
    }

    /**
     * Merges the given array together.
     *
     * @param mixed ...$arrays List of arrays.
     *
     * @return array Resulting array.
     */
    public static function merge(...$arrays) : array
    {
        return array_merge(...$arrays);
    }

    /**
     * Gets the length of an array.
     *
     * @param array $array Array to get the length from
     *
     * @return int Length of the array.
     */
    public static function length(array $array) : int
    {
        return count($array);
    }

    /**
     * Checks if given key exists within the given array.
     *
     * @param int|string $key
     * @param array $haystack
     *
     * @return bool True if the key exists, false otherwise.
     */
    public static function exists($key, array $haystack) : bool
    {
        return array_key_exists($key, $haystack);
    }

    /**
     * Checks if the given value exists within the given haystack.
     *
     * @param mixed $value      Value to be searched for.
     * @param array $haystack   Haystack to be searched.
     *
     * @return bool TRUE if the value exists, false otherwise.
     */
    public static function hasValue($value, array $haystack) : bool
    {
        return in_array($value, $haystack);
    }

    /**
     * Compares the two given arrays based on the given callback.
     * The callback function receives the current item of each array and must return either true or false.
     *
     * @param array $first_array    First array to be compared.
     * @param array $second_array   Second array to be compared.
     * @param Closure $callback    Callback function that checks the given items of each array.
     *
     * @return bool True if the callback returned true on every iterated item.
     * False if the array lengths don't match, the keys don't match or the callback returned false at some point.
     */
    public static function compare(array $first_array, array $second_array, Closure $callback) : bool
    {
        if( self::length($first_array) !== self::length($second_array) ) {
            return false;
        }

        foreach( $first_array as $index => $value_first ) {
            if( !self::exists($index, $second_array) ) {
                return false;
            }

            if( !$callback($value_first, $second_array[$index], $index) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Gets a random value from the given array list.
     *
     * @param array $array              Array to get random value from.
     * @param int|null $array_length    Length of the array (more performant in loop calls).
     *
     * @return mixed
     */
    public static function random(array $array, ?int $array_length = null)
    {
        if( is_null($array_length) ) {
            $array_length = self::length($array);
        }

        return $array[rand(0, $array_length - 1)];
    }

    /**
     * Performs the operation in the given callback and uses its result for new mapped array.
     * The callback receives the current item and index as parameters.
     *
     * @param array $array          Array to be mapped.
     * @param Closure $callback    Callback function that performs the mapping.
     *
     * @return array Resulting array.
     */
    public static function map(array $array, Closure $callback) : array
    {
        $new = [];

        foreach( $array as $index => $value ) {
            $new[$index] = $callback($value, $index);
        }

        return $new;
    }

    /**
     * Filters the given array with the given callback function.
     * The callback receives the current item and index as parameters and should return either TRUE or FALSE.
     *
     * @param array $array          Array to be filtered.
     * @param Closure $callback    Callback function with the filtering logic.
     *
     * @return array Filtered array.
     */
    public static function filter(array $array, Closure $callback) : array
    {
        $new = [];

        foreach( $array as $index => $value ) {
            if( !$callback($value, $index) ) {
                continue;
            }

            $new[$index] = $value;
        }

        return $new;
    }

    /**
     * Finds the first element on which the given callback logic return TRUE.
     * The callback receives the current item and index as parameters.
     *
     * @param array $array          Array to be searched.
     * @param Closure $callback    Callback function that implements the find logic.
     *
     * @return false|mixed Returns the first value that matches the callback logic. FALSE otherwise.
     */
    public static function find(array $array, Closure $callback)
    {
        foreach( $array as $index => $value ) {
            if( $callback($value, $index) ) {
                return $value;
            }
        }

        return false;
    }

    /**
     * Finds the first index on which the given callback logic return TRUE.
     * The callback receives the current item and index as parameters.
     *
     * @param array $array          Array to be searched.
     * @param Closure $callback    Callback function that implements the find logic.
     *
     * @return false|int|string Returns the first value that matches the callback logic. FALSE otherwise.
     */
    public static function findIndex(array $array, Closure $callback)
    {
        foreach( $array as $index => $value ) {
            if( $callback($value, $index) ) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Finds the first index on which the given value matches the current iteration value.
     *
     * @param array $array          Array to be searched.
     * @param mixed $search_value   Value to be searched.
     *
     * @return false|int|string Returns the first value that matches the callback logic. FALSE otherwise.
     */
    public static function indexOf(array $array, $search_value)
    {
        foreach( $array as $index => $value ) {
            if( $value === $search_value ) {
                return $index;
            }
        }

        return false;
    }

    /**
     * Returns all keys of the given array.
     *
     * @param array $array Subject array.
     *
     * @return int[]|string[]
     */
    public static function keys(array $array) : array
    {
        return array_keys($array);
    }

    /**
     * Reverses the given array.
     *
     * @param array $array          Array to be reversed.
     * @param bool $preserve_keys   Preserves the keys if this is set to TRUE.
     *
     * @return array Returns the reversed Array.
     */
    public static function reverse(array $array, bool $preserve_keys = false) : array
    {
        return array_reverse($array, $preserve_keys);
    }

    /**
     * Reverses all the keys of the FIRST level of the given array.
     *
     * @param array $array  Array which keys are to be reversed.
     * @param bool $is_list Identifier if the array is a list or not.
     *
     * @return array Returns the given array but with reversed keys.
     */
    public static function reverseKeys(array $array, bool $is_list = false) : array
    {
        $new_array = [];

        if( $is_list ) {
            $array_length = self::length($array);

            foreach( $array as $value ) {
                $new_array[$array_length] = $value;
                $array_length--;
            }

            return $new_array;
        }

        $keys = self::keys($array);

        $reversed_keys = self::reverse($keys);

        $current_index = 0;
        foreach( $array as $value ) {
            $new_array[$reversed_keys[$current_index]] = $value;

            $current_index++;
        }

        return $new_array;
    }

    /**
     * Builds a query string from the given data array.
     *
     * @param array $data Source array.
     *
     * @return string Finished query string.
     */
    public static function queryString(array $data) : string
    {
        $query_string = '';

        $first_key = array_key_first($data);

        foreach( $data as $key => $value ) {
            if( $first_key === $key ) {
                if( is_array($value) ) {
                    $query_string .= self::queryStringArray($key, $value, true);
                    continue;
                }

                $query_string .= "?$key=$value";
                continue;
            }

            if( is_array($value) ) {
                $query_string .= self::queryStringArray($key, $value);
                continue;
            }

            $query_string .= "&$key=$value";
        }

        return $query_string;
    }

    /**
     * Builds query string if a data value was an array.
     *
     * @param string $parent_key    Key of the parent array.
     * @param array $data           Data to be converted to a query string.
     * @param bool $first_iteration Identifier if it is the first iteration.
     *
     * @return string Query string.
     */
    public static function queryStringArray(string $parent_key, array $data, bool $first_iteration = false) : string
    {
        $query_string = '';
        $first_key = array_key_first($data);

        foreach( $data as $key => $value ) {
            $new_key = "$parent_key" . "[" . $key . "]";
            $symbol = '&';

            if( $first_key === $key ) {
                if( $first_iteration ) {
                    $symbol = '?';
                }

                if( is_array($value) ) {
                    $query_string .= self::queryStringArray($new_key, $value, $first_iteration);
                    continue;
                }

                $query_string .= "$symbol$new_key=$value";
                continue;
            }

            if( is_array($value) ) {
                $query_string .= self::queryStringArray($new_key, $value);
                continue;
            }

            $query_string .= "$symbol$new_key=$value";
        }

        return $query_string;
    }
}