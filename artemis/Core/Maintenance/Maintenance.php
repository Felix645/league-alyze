<?php


namespace Artemis\Core\Maintenance;


use Artemis\Client\Facades\Hash;
use Artemis\Support\Exceptions\Json\InvalidJsonException;
use Artemis\Support\FileSystem;
use Artemis\Support\Json;

class Maintenance
{
    /**
     * Path to the maintenance config file.
     *
     * @var string
     */
    private const MAINTENANCE_CONF = ROOT_PATH . 'config/maintenance.json';

    /**
     * maintenance config data.
     *
     * @var null|array
     */
    private static $conf_data = null;

    /**
     * Enables the maintenance mode.
     *
     * @return void
     */
    public static function enable()
    {
        $data = self::buildData();

        Json::writeJsonFile($data, self::MAINTENANCE_CONF, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Disables the maintenance mode.
     *
     * @return void
     */
    public static function disable()
    {
        $data = self::buildData(false);

        Json::writeJsonFile($data, self::MAINTENANCE_CONF, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Checks if maintenance mode is active.
     *
     * @return bool
     */
    public static function isActive()
    {
        self::checkFile();

        return self::$conf_data['maintenance_mode'];
    }

    /**
     * Gets the maintenance secret key.
     *
     * @return null|string
     */
    public static function secret()
    {
        self::checkFile();

        return self::$conf_data['secret'];
    }

    /**
     * Checks the maintenance config file.
     *
     * @return void
     */
    private static function checkFile()
    {
        if( !FileSystem::exists(self::MAINTENANCE_CONF) ) {
            self::disable();
        }

        try {
            $data = Json::jsonFileContent(self::MAINTENANCE_CONF);
        } catch(InvalidJsonException $e) {
            self::disable();
            $data = self::buildData(false);
        }

        self::$conf_data = $data;
    }

    private static function buildData($is_down = true)
    {
        return [
            'maintenance_mode' => $is_down,
            'secret' => $is_down ? self::buildSecret() : null
        ];
    }

    private static function buildSecret()
    {
        return Hash::hexToken(2)
            . '-' . Hash::hexToken(2)
            . '-' . Hash::hexToken(2)
            . '-' . Hash::hexToken(2);
    }
}