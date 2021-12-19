<?php


namespace Artemis\Core\ErrorHandling;


use Artemis\Support\Arr;
use Artemis\Core\Exception\Runtime as RuntimeError;
use Artemis\Core\Http\ResponseHandler;
use Throwable;
use Closure;


abstract class ErrorHandler implements HandlerInterface
{
    /**
     * Identifier if the app is in debug mode or not.
     *
     * @var bool
     */
    private $in_production;

    /**
     * Map of runtime errors for easier handling.
     *
     * @var array
     */
    private $runtime_error_map;

    /**
     * Collection of reportables registered.
     *
     * @var array
     */
    private $reportables = [];

    /**
     * Collection of renderables registered.
     *
     * @var array
     */
    private $renderables = [];

    /**
     * ErrorHandler constructor.
     */
    public function __construct()
    {
        $this->in_production = !app()->debug();
        $this->setRuntimeErrorMap();
    }

    /**
     * Reports an exception to the handler.
     *
     * @param Throwable $e
     *
     * @return void
     */
    public function report(Throwable $e)
    {
        $this->handle($e);
    }

    /**
     * @inheritDoc
     *
     * @return Reportable The new reportable instance.
     */
    public function reportable($exception_class, Closure $handler)
    {
        $reportable = new Reportable($exception_class, $handler);
        $this->reportables[$exception_class][] = $reportable;

        return $reportable;
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function renderable($exception_class, Closure $handler)
    {
        $this->renderables[$exception_class] = $handler;
    }

    /**
     * @inheritDoc
     *
     * @return bool|void
     */
    public function handle(Throwable $e, $handle_count = 0, $called_from_handler = false)
    {
        $reportables = $this->reportables[get_class($e)] ?? [];

        if( $e instanceof RuntimeError\RuntimeError && empty($reportables) ) {
            if( !$this->runtime_error_map[$e->getCode()]['stops'] && !$called_from_handler ) {
                return true;
            }
        }

        if( $this->checkReportables($reportables, $e) && !$called_from_handler ) {
            return true;
        }

        return $this->renderError($e, $handle_count);
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function registerExceptionHandler()
    {
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function registerErrorHandler()
    {
        set_error_handler([$this, 'handleRuntimeError']);
    }

    /**
     * Exception handler function.
     *
     * @param Throwable $e
     *
     * @return void
     */
    public function handleException(Throwable $e)
    {
        $this->handle($e, 0, true);
        exit;
    }

    /**
     * @inheritDoc
     *
     * @return bool|void
     */
    public function handleRuntimeError($error, $message, $file, $line)
    {
        $error_string_map = [
            E_ERROR                 => 'Error',
            E_WARNING               => 'Warning',
            E_PARSE                 => 'Parse-Error',
            E_NOTICE                => 'Notice',
            E_CORE_ERROR            => 'Core-Error',
            E_CORE_WARNING          => 'Core-Warning',
            E_COMPILE_ERROR         => 'Compilation-Error',
            E_COMPILE_WARNING       => 'Compilation-Warning',
            E_USER_ERROR            => 'User-Error',
            E_USER_WARNING          => 'User-Warning',
            E_USER_NOTICE           => 'User-Notice',
            E_STRICT                => 'Strict-Notice',
            E_RECOVERABLE_ERROR     => 'Recoverable-Error',
            E_DEPRECATED            => 'Deprecated-Error',
            E_USER_DEPRECATED       => 'User-Deprecated-Error'
        ];

        error_log("$error_string_map[$error]: $message in $file on line $line", 0);

        if( !Arr::exists($error, $this->runtime_error_map) ) {
            die("Unknown runtime error: $error");
        }

        $ex = new $this->runtime_error_map[$error]['class']($error, $message, $file, $line);

        return $this->handle($ex);
    }
    
    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->registerHandling();
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        //
    }

    /**
     * @inheritDoc
     *
     * @return void
     */
    public function recheckDebugMode()
    {
        $this->in_production = !app()->debug();
        $this->setRuntimeErrorMap();
    }

    /**
     * Renders the given exception to the user.
     *
     * @param Throwable $e
     * @param int $handle_count
     *
     * @return false|void
     */
    private function renderError($e, $handle_count)
    {
        try {
            if( $renderable = $this->renderables[get_class($e)] ?? null ) {
                ResponseHandler::new($renderable());
                exit;
            }

            $renderer = new ExceptionRenderer(new Renderable($e), request()->needsJson(), app()->fromCLI());

            ResponseHandler::new($renderer->render());
        } catch(Throwable $ex) {
            if( $handle_count >= 5 ) {
                die("There appears to be a problem with the exception handler: possibly infinite loop detected: {$ex->getMessage()}");
            }

            $this->handle($ex, $handle_count + 1);
        }

        return false;
    }

    /**
     * Checks if there are additional handlers for the given exception.
     *
     * @param Reportable[] $reportables
     * @param Throwable $e
     *
     * @return bool True if the execution can continue or false if execution should be stopped
     */
    private function checkReportables($reportables, $e)
    {
        $can_contine = false;
        foreach( $reportables as $reportable ) {
            if( !$can_contine = $this->handleReportable($reportable, $e) ) {
                break;
            }
        }

        return $can_contine;
    }

    /**
     * Executes a given registered exception handler.
     *
     * @param Reportable $reportable
     * @param Throwable $exception
     *
     * @return bool
     */
    private function handleReportable($reportable, $exception)
    {
        $reportable->executeHandler($exception);

        if( $exception instanceof RuntimeError\RuntimeError ) {
            return !$this->runtime_error_map[$exception->getCode()]['stops'];
        }

        return $reportable->shouldContinue();
    }

    /**
     * Sets the mapping array for runtime errors.
     *
     * @return void
     */
    private function setRuntimeErrorMap()
    {
        $this->runtime_error_map = [
            E_WARNING => [
                'class' => RuntimeError\Warning::class,
                'stops' => !$this->in_production
            ],
            E_NOTICE => [
                'class' => RuntimeError\Notice::class,
                'stops' => !$this->in_production
            ],
            E_USER_ERROR => [
                'class' => RuntimeError\UserError::class,
                'stops' => true
            ],
            E_USER_WARNING => [
                'class' => RuntimeError\UserWarning::class,
                'stops' => !$this->in_production
            ],
            E_USER_NOTICE => [
                'class' => RuntimeError\UserNotice::class,
                'stops' => !$this->in_production
            ],
            E_STRICT => [
                'class' => RuntimeError\Strict::class,
                'stops' => !$this->in_production
            ],
            E_RECOVERABLE_ERROR => [
                'class' => RuntimeError\RecoverableError::class,
                'stops' => true
            ],
            E_DEPRECATED => [
                'class' => RuntimeError\Deprecated::class,
                'stops' => !$this->in_production
            ],
            E_USER_DEPRECATED => [
                'class' => RuntimeError\UserDeprecated::class,
                'stops' => !$this->in_production
            ]
        ];
    }

    /**
     * Used for registering exception handlers.
     *
     * @return void
     */
    abstract protected function registerHandling();
}