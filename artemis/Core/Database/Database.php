<?php


namespace Artemis\Core\Database;


use PDO;


class Database
{
    /**
     * PDO connection
     *
     * @var PDO
     */
    private $connection;

    /**
     * Connects to given database
     *
     * @param string $db_key
     *
     * @return Database
     */
    public function connect($db_key)
    {
        $this->connection = container(DBManager::class)->get($db_key);
        return $this;
    }

    /**
     * Prepares a given sql statement
     *
     * @param string $sql
     *
     * @return PreparedStatement
     */
    public function prepare($sql)
    {
        $stmt = $this->connection->prepare($sql);
        return new PreparedStatement($this->connection, $stmt);
    }

    /**
     * Executes a given sql statement
     *
     * @param string $sql
     *
     * @return Statement
     */
    public function unprepared($sql)
    {
        $stmt = $this->connection->query($sql);
        return new Statement($this->connection, $stmt);
    }
}