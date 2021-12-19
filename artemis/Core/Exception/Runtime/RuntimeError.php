<?php


namespace Artemis\Core\Exception\Runtime;


use Exception;


abstract class RuntimeError extends Exception
{
    /**
     * RuntimeException constructor.
     *
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function __construct($code, $message, $file, $line)
    {
        parent::__construct($message, $code);
        $this->file = $file;
        $this->line = $line;
    }
}