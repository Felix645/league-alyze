<?php


namespace Artemis\Core\Routing;


use Artemis\Support\Arr;

class RouteGroup
{
    /**
     * Merged options
     *
     * @var array
     */
    private static $new_options;

    /**
     * Merges old route group options with the new options
     *
     * @param array $new
     * @param array $old
     *
     * @return array
     */
    public static function merge($new, $old)
    {
        self::$new_options = $old;

        if( isset($new['prefix']) )
            self::mergePrefix($new['prefix'], $old);

        if( isset($new['middleware']))
            self::mergeMiddleware($new['middleware'], $old);

        if( isset($new['name']) )
            self::mergeName($new['name'], $old);

        return self::$new_options;
    }

    /**
     * Merges old 'prefix' option with new 'prefix' option
     *
     * @param string $new_prefix
     * @param array $old
     *
     * @return void
     */
    private static function mergePrefix($new_prefix, $old)
    {
        $new_prefix = rtrim(ltrim($new_prefix, '/'), '/');

        if( !isset($old['prefix']) ) {
            self::$new_options['prefix'] = '/' . $new_prefix;
            return;
        }

        self::$new_options['prefix'] = $old['prefix'] . '/' . $new_prefix;
    }

    /**
     * Merges old 'middleware' option with new 'middleware' option
     *
     * @param array $new_middlewares
     * @param array $old
     *
     * @return void
     */
    private static function mergeMiddleware($new_middlewares, $old)
    {
        if( !isset($old['middleware']) ) {
            self::$new_options['middleware'] = $new_middlewares;
            return;
        }

        self::$new_options['middleware'] = Arr::merge($old['middleware'], $new_middlewares);
    }

    /**
     * Merges old 'name' option with new 'name' option
     *
     * @param string $new_name
     * @param array $old
     *
     * @return void
     */
    private static function mergeName($new_name, $old)
    {
        if( !isset($old['name']) ) {
            self::$new_options['name'] = $new_name;
            return;
        }

        self::$new_options['name'] = $old['name'] . $new_name;
    }
}