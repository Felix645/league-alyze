<?php


namespace Artemis\Client\Facades;


/**
 * Class Settings
 * @package Artemis\Client\Facades
 *
 * @method static string get(string $db_key, string $key)
 *
 * @uses \Artemis\Core\Settings::get()
 */
class Settings extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'settings';
    }
}