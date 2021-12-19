<?php


namespace Artemis\Core\Database;


use Artemis\Core\Database\Exceptions\ConfigException as ConfigurationException;
use PDO;


class DBManager
{
    /**
     * Array of PDO connections by their database key
     *
     * @var PDO[]
     */
    private $connections = [];

    /**
     * Array of the database configurations
     *
     * @var array[]
     */
    private $db_configs;

    /**
     * DBManager constructor.
     */
    public function __construct()
    {
        $this->db_configs = require ROOT_PATH . 'config/database.php';
    }

    /**
     * Returns the PDO connection for given database key
     *
     * @param string $db_key
     *
     * @return PDO
     */
    public function get($db_key)
    {
        try {
            return $this->connections[$db_key] ?? $this->newConnection($db_key);
        } catch( \Throwable $e ) {
            report($e);
            exit;
        }
    }

    /**
     * Establishes a new PDO connection
     *
     * @param string $db_key
     * @throws ConfigurationException|\PDOException
     *
     * @return PDO
     */
    private function newConnection($db_key)
    {
        if( !isset($this->db_configs[$db_key]) ) {
            $message = "Unknown database key '$db_key' provided";
            throw new ConfigurationException($message);
        }

        $config = $this->db_configs[$db_key];

        if( isset($config["host"]) && isset($config["user"]) && isset($config["pass"]) && isset($config["name"]) && isset($config["char"]) ) {
            $dsn = "mysql:host=".$config["host"].";dbname=".$config["name"].";charset=".$config["char"];

            $pdo = new PDO($dsn, $config["user"], $config["pass"]);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $this->connections[$db_key] = $pdo;
            return $pdo;
        } else {
            $message = "One or more keys are missing for database config '$db_key'";
            throw new ConfigurationException($message);
        }
    }
}