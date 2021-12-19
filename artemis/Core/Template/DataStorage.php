<?php


namespace Artemis\Core\Template;


use Artemis\Support\Arr;

class DataStorage
{
    /**
     * Collection of data
     * 
     * @var array
     */
    private static $data = [];

    /**
     * Adds shared view data
     * 
     * @param array $shared_data
     * 
     * @return void
     */
    public static function addSharedViewData($shared_data)
    {
        self::mergeData('view_data', $shared_data);
    }

    /**
     * Gets the stored data
     * 
     * @return array
     */
    public static function getData() : array 
    {
        return self::$data;
    }

    /**
     * Gets a single data entry
     * 
     * @param string $key
     * 
     * @return mixed
     */
    public static function get($key)
    {
        return self::$data["view_data"][$key];
    }

    /**
     * Adds a data collection to the storage
     * 
     * @param array $data_collection
     * 
     * @return void
     */
    public static function addToData($data_collection)
    {
        self::mergeData('view_data', $data_collection);
    }

    /**
     * Merges a data collection with the storage
     *
     * @param string $key
     * @param array $new_data
     * 
     * @return void
     */
    private static function mergeData($key, $new_data)
    {
        if( !empty($new_data) ) {
            if( Arr::exists($key, self::$data) && !empty(self::$data[$key]) )
                self::$data[$key] = Arr::merge(self::$data[$key], $new_data);
            else
                self::$data[$key] = $new_data;
        }
    }
}