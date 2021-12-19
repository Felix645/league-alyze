<?php


namespace Artemis\Core\Models;


use Artemis\Client\Facades\Database;


class Settings
{
    /**
     * Fetches the settings data
     *
     * @param string $db_key
     *
     * @return array $result
     */
    public function fetchSettings($db_key)
    {
        $sql = "SELECT * FROM settings";

        return Database::connect($db_key)->unprepared($sql)->result();
    }
}