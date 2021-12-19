<?php


namespace Artemis\Core\Database\Migration;


use Artemis\Client\Facades\Database;


abstract class Migration
{
    /**
     * Database key.
     *
     * @var string
     */
    protected $database;

    /**
     * Run the migrations.
     *
     * @return void
     */
    abstract public function up() : void;

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    abstract public function down() : void;

    /**
     * Executes given SQL.
     *
     * @param string $sql
     * @param array $params
     *
     * @return void
     */
    protected function execute(string $sql, array $params = [])
    {
        Database::connect($this->database)
            ->prepare($sql)
            ->bind($params)
            ->execute();
    }
}