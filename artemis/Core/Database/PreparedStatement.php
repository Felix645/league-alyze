<?php


namespace Artemis\Core\Database;


class PreparedStatement extends Statement
{
    /**
     * Parameters to be bound to the statement
     *
     * @var array
     */
    private $params = [];

    /**
     * Sets given parameters for binding
     *
     * @param array $params
     *
     * @return PreparedStatement
     */
    public function bind($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Executes the prepared statement
     *
     * @return PreparedStatement
     */
    public function execute()
    {
        $this->stmt->execute($this->params);
        return $this;
    }
}