<?php


namespace Artemis\Core\ErrorHandling;


use Artemis\Core\Interfaces\ProviderInterface;
use Closure;
use Throwable;


interface HandlerInterface extends ProviderInterface
{
    /**
     * Registers a new reportable exception.
     *
     * @param string $exception_class   Class string of the reportable exception
     * @param Closure $handler          Callback function when the exception is reported
     *
     * @return Reportable The new reportable instance.
     */
    public function reportable($exception_class, Closure $handler);

    /**
     * Registers a new renderable for given exception.
     *
     * @param string $exception_class   Class string of the renderable exception.
     * @param Closure $handler          Callback function when the exception is handled.
     *
     * @return void
     */
    public function renderable($exception_class, Closure $handler);

    /**
     * Handles a given exception.
     *
     * @param Throwable $e
     * @param int $handle_count
     * @param bool $called_from_handler
     *
     * @return bool|void
     */
    public function handle(Throwable $e, $handle_count = 0, $called_from_handler = false);

    /**
     * Overriding PHP's default exception handler.
     *
     * @return void
     */
    public function registerExceptionHandler();

    /**
     * Overriding PHP's default error handler.
     *
     * @return void
     */
    public function registerErrorHandler();

    /**
     * Error handler function.
     *
     * @param int $error
     * @param string $message
     * @param string $file
     * @param int $line
     *
     * @return bool|void
     */
    public function handleRuntimeError($error, $message, $file, $line);

    /**
     * Rechecks the app's debug mode during booting process.
     *
     * @return void
     */
    public function recheckDebugMode();
}