<?php


namespace Artemis\Core\ErrorHandling;


use Closure;
use Throwable;


class Reportable
{
    /**
     * Class string of the exception that should be handled.
     *
     * @var string
     */
    private $exception_class;

    /**
     * Handler callback function.
     *
     * @var Closure
     */
    private $handler;

    /**
     * Identifier if the execution of script can continue or not.
     *
     * @var bool
     */
    private $should_continue = false;

    /**
     * Reportable constructor.
     *
     * @param string $exception_class
     * @param Closure $handler
     */
    public function __construct($exception_class, $handler)
    {
        $this->exception_class = $exception_class;
        $this->handler = $handler;
    }

    /**
     * Executes the handler callback.
     *
     * @param Throwable $ex
     *
     * @return void
     */
    public function executeHandler(Throwable $ex)
    {
        ($this->handler)($ex);
    }

    /**
     * Gets the class string of the reportable exception.
     *
     * @return string
     */
    public function exceptionClass()
    {
        return $this->exception_class;
    }

    /**
     * Sets the identifier if the execution of the script should continue or not.
     *
     * @return void
     */
    public function continue()
    {
        $this->should_continue = true;
    }

    /**
     * Gets the identifier if the execution of the script should continue or not.
     *
     * @return bool
     */
    public function shouldContinue()
    {
        return $this->should_continue;
    }
}