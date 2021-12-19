<?php

namespace Artemis\Core\ErrorHandling;

class TraceItem
{
    /**
     * File of the trace item.
     *
     * @var string
     */
    private $file;

    /**
     * Line of code of the trace item file.
     *
     * @var int
     */
    private $line;

    /**
     * Called function of the trace item.
     *
     * @var string
     */
    private $function;

    /**
     * Class of the trace item.
     *
     * @var string
     */
    private $class;

    /**
     * Type of the trace item call.
     *
     * @var string
     */
    private $type;

    /**
     * Arguments of the function that was called.
     *
     * @var array
     */
    private $args;

    /**
     * Collection of relevent code lines for debug.
     *
     * @var array
     */
    private $lines_of_code = [];

    /**
     * TraceItem constructor.
     *
     * @param array $trace_info
     */
    public function __construct($trace_info)
    {
        $this->file = $trace_info['file'];
        $this->line = $trace_info['line'];
        $this->function = $trace_info['function'];
        $this->class = $trace_info['class'] ?? '';
        $this->type = $trace_info['type'] ?? '';
        $this->args = new TraceArgument($trace_info['args']);
        $this->buildLinesOfCode();
        $this->checkClassFile();
    }

    private function checkClassFile()
    {
        if( empty($this->class) ) {
            return;
        }

        try {
            $reflection = new \ReflectionClass($this->class);
        } catch(\ReflectionException $e) {
            $this->class = '';
            return;
        }

        if( $reflection->getFileName() !== $this->file ) {
            $this->class = '';
        }
    }

    /**
     * Builds lines of code as arra for easier display.
     *
     * @return void
     */
    private function buildLinesOfCode()
    {
        $file = new \SplFileObject($this->file);

        $line_number = 1;
        while( !$file->eof() ) {
            if( $this->line + 13 < $line_number ) {
                $file->fgets();
                break;
            }

            if( $this->line - 13 > $line_number ) {
                $file->fgets();
                $line_number++;
                continue;
            }

            $this->lines_of_code[$line_number] = $file->fgets();
            $line_number++;

            if( $file->eof() ) {
                $this->lines_of_code[$line_number] = '';
                $line_number++;
            }
        }
    }

    /**
     * Gets the trace item file.
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Gets the trace item line of code.
     *
     * @return int
     */
    public function line()
    {
        return $this->line;
    }

    /**
     * Gets the trace item function.
     *
     * @return string
     */
    public function function()
    {
        return $this->function;
    }

    /**
     * Gets the trace item class.
     *
     * @return string
     */
    public function class()
    {
        return $this->class;
    }

    /**
     * Gets the trace item call type.
     *
     * @return string
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Gets the trace item arguments.
     *
     * @return array
     */
    public function args()
    {
        return $this->args;
    }

    /**
     * Gets the line of code of trace file.
     *
     * @return array
     */
    public function linesOfCode()
    {
        return $this->lines_of_code;
    }
}