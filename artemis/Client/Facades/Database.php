<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Database\PreparedStatement;
use Artemis\Core\Database\Statement;


/**
 * Class Database
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Core\Database\Database connect(string $db_key) Connects to given database
 * @method static PreparedStatement prepare(string $sql) Prepares a given sql statement
 * @method static Statement unprepared(string $sql) Executes a given sql statement
 *
 * @uses \Artemis\Core\Database\Database::connect()
 * @uses \Artemis\Core\Database\Database::prepare()
 * @uses \Artemis\Core\Database\Database::unprepared()
 */
class Database extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'database';
    }
}