<?php


namespace Artemis\Core;


use Artemis\Core\Database\Exceptions\DatabaseException;
use Artemis\Core\Models as models;


class Settings
{
    /**
     * The settings array, taken from the settings table inside database
     * 
     * @var array
     */
    private $settings = array();

    /**
     * Loads settings from the given database
     * 
     * @param string $db_key
     * 
     * @return void
     */
    public function loadSettings($db_key)
    {
        try {
            $SettingsModel = new models\Settings();
            $result = $SettingsModel->fetchSettings($db_key);
        } catch(\PDOException $e) {
            report($e);
            exit;
        }

        if( !empty($result) ) {           
            foreach( $result as $setting ) {
                $this->settings[$db_key][$setting['key']] = $setting['value'];
            }
        }
    }

    /**
     * Gets a specified setting
     *
     * @param string $db_key
     * @param string $key
     *
     * @throws DatabaseException
     *
     * @return string $setting
     */
    public function get($db_key, $key)
    {
        if( !isset($this->settings[$db_key][$key]) ) {
            $message = "Could not fetch setting '$key' from database '$db_key'";
            throw new DatabaseException($message);
        }

        return $this->settings[$db_key][$key];
    }
}