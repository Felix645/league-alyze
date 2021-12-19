<?php


namespace Artemis\Core\ErrorHandling;


use Artemis\Support\Arr;
use Artemis\Core\Exception\Runtime\RuntimeError;
use Throwable;


class Renderable
{
    /**
     * Exception class string.
     *
     * @var string
     */
    private $exception;

    /**
     * Error message.
     *
     * @var string
     */
    private $message;

    /**
     * Error code.
     *
     * @var int
     */
    private $code;

    /**
     * Error file.
     *
     * @var string
     */
    private $file;

    /**
     * Line of code.
     *
     * @var int
     */
    private $line;

    /**
     * Collection of trace items.
     *
     * @var TraceItem[]
     */
    private $trace = [];

    /**
     * Renderable constructor.
     *
     * @param Throwable $throwable
     */
    public function __construct(Throwable $throwable)
    {
        $this->exception = get_class($throwable);
        $this->message = $throwable->getMessage();
        $this->code = $throwable->getCode();
        $this->file = $this->checkForBladeFile($throwable->getFile());
        $this->line = $throwable->getLine();

        $true_trace = $this->buildTrueTrace($throwable->getTrace());

        foreach( $true_trace as $trace_info ) {
            if( empty($trace_info['file'])
                && empty($trace_info['function'])
                && empty($trace_info['class'])
                && empty($trace_info['type'])
                && empty($trace_info['args'])
            ) {
                continue;
            }

            $this->trace[] = new TraceItem($trace_info);
        }

        $this->reArrayTrace();
    }

    /**
     * Rearrays the original debug trace for better display.
     *
     * @param array $trace
     *
     * @return array
     */
    private function buildTrueTrace($trace)
    {
        if( $this->exception === RuntimeError::class || is_subclass_of($this->exception, RuntimeError::class) ) {
            array_shift($trace);
        }

        $true_trace = [];

        foreach( $trace as $key => $trace_info ) {
            $real_key = $key - 1;
            if( $key === array_key_first($trace) ) {
                $true_trace[$key]['file'] = $this->file;
                $true_trace[$key]['line'] = $this->line;
                $true_trace[$key]['function'] = $trace_info['function'] ?? '';
                $true_trace[$key]['class'] = $trace_info['class'] ?? '';
                $true_trace[$key]['type'] = $trace_info['type'] ?? '';
                $true_trace[$key]['args'] = $trace_info['args'] ?? '';

                continue;
            }

            if( $key === array_key_last($trace) ) {
                $true_trace[$key] = $trace_info;
                $true_trace[$key]['file'] = $this->checkForBladeFile($trace[$real_key]['file']);
                $true_trace[$key]['line'] = $trace[$real_key]['line'];

                $next_key = $key + 1;
                $true_trace[$next_key] = $trace_info;
                $true_trace[$next_key]['file'] = $this->checkForBladeFile($trace_info['file']);
                $true_trace[$next_key]['function'] = '';
                $true_trace[$next_key]['class'] = '';
                $true_trace[$next_key]['type'] = '';
                $true_trace[$next_key]['args'] = [];

                continue;
            }

            if( !isset($trace[$real_key]['file']) && !isset($trace[$real_key]['line']) ) {
                continue;
            }

            $true_trace[$key] = $trace_info;
            $true_trace[$key]['function'] = $trace_info['function'] ?? '';
            $true_trace[$key]['class'] = $trace_info['class'] ?? '';
            $true_trace[$key]['type'] = $trace_info['type'] ?? '';
            $true_trace[$key]['args'] = $trace_info['args'] ?? '';
            $true_trace[$key]['file'] = $this->checkForBladeFile($trace[$real_key]['file']) ?? '';
            $true_trace[$key]['line'] = $trace[$real_key]['line'] ?? null;
        }

        return $true_trace;
    }

    private function checkForBladeFile($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);

        if( 'bladec' !== $extension ) {
            return $file;
        }

        $template = explode('___', pathinfo($file, PATHINFO_BASENAME))[0];

        $template_file = implode('/', explode('.', $template)) . '.blade.php';

        return ROOT_PATH . 'app/Views/' . $template_file;
    }

    /**
     * Inverts the numeric key of the stack trace.
     *
     * @return void
     */
    private function reArrayTrace()
    {
        $this->trace = Arr::reverseKeys($this->trace, true);
    }

    /**
     * Gets the exception class string.
     *
     * @return string
     */
    public function exception()
    {
        return $this->exception;
    }

    /**
     * Gets the error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Gets the error code.
     *
     * @return int
     */
    public function code()
    {
        return $this->code;
    }

    /**
     * Gets the error file.
     *
     * @return string
     */
    public function file()
    {
        return $this->file;
    }

    /**
     * Gets the error line of code.
     *
     * @return int
     */
    public function line()
    {
        return $this->line;
    }

    /**
     * Gets the error trace.
     *
     * @return array|TraceItem[]
     */
    public function trace()
    {
        return $this->trace;
    }
}