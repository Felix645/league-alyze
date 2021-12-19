<?php


namespace Artemis\Core\Database;


use PDO;
use PDOStatement;


class Statement
{
    /**
     * Database connection
     *
     * @var PDO
     */
    protected $connection;

    /**
     * PDO Statement
     *
     * @var PDOStatement
     */
    protected $stmt;

    /**
     * Statement constructor.
     *
     * @param PDO $connection
     * @param PDOStatement $stmt
     */
    public function __construct($connection, $stmt)
    {
        $this->connection = $connection;
        $this->stmt = $stmt;
    }

    /**
     * Gets all results as a multidimensional associative array
     *
     * @return array
     */
    public function result()
    {
        return $this->stmt->fetchAll();
    }

    /**
     * Gets a single entry as a associative array
     *
     * @return array
     */
    public function entry()
    {
        $return = $this->stmt->fetch();
        if( FALSE === $return )
            return [];
        else
            return $return;
    }

    /**
     * Returns a single column from the next row of a result set
     *
     * @return mixed
     */
    public function getColumn()
    {
        return $this->stmt->fetchColumn();
    }

    /**
     * Gets the last inserted ID of the current connection
     *
     * @return int ID
     */
    public function lastInsertID()
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Gets the number of affected rows of an update/insert/delete statement
     * IMPORTANT: DOESN'T WORK ON SELECT STATEMENTS with all database drivers
     * instead check if return value of getEntry() or getResult() is empty!
     *
     * @return int affectedRows
     */
    public function affectedRows()
    {
        return $this->stmt->rowCount();
    }
}