<?php


namespace Artemis\Core\Routing;


use Artemis\Support\Arr;
use Artemis\Support\Str;

class RouteValidator
{
    /**
     * Bits of the request uri
     * 
     * @var
     */
    private static $request_bits;

    /**
     * Bits of the route
     * 
     * @var
     */
    private static $route_bits;

    /**
     * Collection of url parameters, will be stored inside the request object if the route is matching
     * 
     * @var array
     */
    private static $url_params = array();

    /**
     * Checks if given requests_bits and route_bits match
     * 
     * @param Route $route
     * @param array $request_bits
     * 
     * @return bool
     */
    public static function match($route, $request_bits)
    {
        self::$request_bits = $request_bits;
        self::$route_bits = $route->getSegments();

        if( empty( self::$request_bits ) ) {
            return self::checkEmptyRoute();
        }

        if( self::checkRoute() ) {
            if( !empty( self::$url_params ) ) {
                foreach( self::$url_params as $key => $url_param ) {
                    container('request')->addURLParam( $key, $url_param );
                }
            }

            return true;
        }

        return false;
    }

    /**
     * Checks if given route is empty
     * 
     * @return bool
     */
    private static function checkEmptyRoute()
    {
        if( empty( self::$route_bits ) )
            return true;

        return false;
    }

    /**
     * Checks if the request matches with given route
     * 
     * @return bool
     */
    private static function checkRoute()
    {
        return Arr::compare(self::$request_bits, self::$route_bits, function($request_segment, $route_segmet) {
            return self::checkSegment($request_segment, $route_segmet);
        });
    }

    /**
     * Checks if the given request segment matches with the route segment
     * 
     * @param string $request_bit
     * @param string $route_bit
     * 
     * @return bool
     */
    private static function checkSegment($request_bit, $route_bit)
    {
        if( $url_variable = Str::matchEnclosedPattern($route_bit, '{', '}') ) {
            self::$url_params[$url_variable] = $request_bit;
            return true;
        }

        return $request_bit === $route_bit;
    }
}