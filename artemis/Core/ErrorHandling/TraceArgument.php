<?php

namespace Artemis\Core\ErrorHandling;

class TraceArgument
{
    /**
     * Original argument array.
     *
     * @var array
     */
    private $args;

    /**
     * Arguments as concatenated string.
     *
     * @var string
     */
    private $arg_string = '';

    /**
     * TraceArgument constructor.
     *
     * @param array $args
     */
    public function __construct($args)
    {
        $this->args = $args;

        foreach( $this->args as $key => $arg ) {
            if( $key === array_key_first($this->args) ) {
                $this->buildRender($arg, true);
                continue;
            }

            if( $key === array_key_last($this->args)) {
                $this->buildRender($arg, false, true);
                continue;
            }

            $this->buildRender($arg);
        }
    }

    /**
     * Gets the original argument array.
     *
     * @return array
     */
    public function args()
    {
        return $this->args;
    }

    /**
     * Gets the concatenated arguement string.
     *
     * @return string
     */
    public function argString()
    {
        return $this->arg_string;
    }

    /**
     * Builds a string part of given argument and concatenates it with the previous string.
     *
     * @param $arg
     * @param false $first
     * @param false $last
     *
     * @return void
     */
    private function buildRender($arg, $first = false, $last = false)
    {
        $string = $this->getRenderString($arg);

        if( $first ) {
            $this->arg_string .= "$string,";
            return;
        }

        if( $last ) {
            $this->arg_string .= " $string";
            return;
        }

        $this->arg_string .= " $string,";
    }

    /**
     * Builds a string based on the given argument type.
     *
     * @param $arg
     *
     * @return string
     */
    private function getRenderString($arg)
    {
        $arg_type = gettype($arg);

        switch($arg_type) {
            case 'boolean':
                $value = $arg ? 'true' : 'false';
                return "bool $value";

            case 'integer':
                return "int $arg";

            case 'double':
                return "double $arg";

            case 'string':
                return "string '$arg'";

            case 'array':
                return "array";

            case 'object':
                $value = get_class($arg);
                return "object '$value'";

            case 'NULL':
                return 'NULL';

            default:
                return 'unknown';
        }
    }
}