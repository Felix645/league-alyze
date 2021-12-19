<?php


namespace Artemis\Core\Pipeline;


use Artemis\Core\Pipeline\Exceptions\InvalidPipeException;
use Artemis\Core\Pipeline\Exceptions\NoPayloadException;
use Artemis\Core\Pipeline\Exceptions\NoPipeException;
use Exception;


class Pipeline
{
    /**
     * Payload instance.
     *
     * @var null|Payload
     */
    private $payload = null;

    /**
     * Collection of pipes to send the payload through.
     *
     * @var null|PipeInterface[]
     */
    private $pipes = null;

    /**
     * Defines a part of the payload.
     * Optionally $key may be provided as an array to provide multiple cargoes at once.
     *
     * @param string|array $key
     * @param mixed|null $cargo
     *
     * @return $this
     */
    public function send($key, $cargo = null)
    {
        if( is_array($key) ) {
            foreach( $key as $array_key => $array_cargo ) {
                $this->addToPayload($array_key, $array_cargo);
            }

            return $this;
        }

        $this->addToPayload($key, $cargo);
        return $this;
    }

    /**
     * Adds a Pipe or a collection of pipes to the pipeline.
     *
     * @param string|array|PipeInterface $input
     *
     * @return $this
     */
    public function through($input)
    {
        if( $this->isPipe($input) ) {
            return $this->addPipe($input);
        }

        if( is_string($input) ) {
            $pipe = container($input);

            if( $this->isPipe($pipe) ) {
                return $this->addPipe($pipe);
            }

            $this->throwException(new InvalidPipeException());
        }

        if( is_array($input) ) {
            foreach( $input as $pipe ) {
                $this->through($pipe);
            }

            return $this;
        }

        $this->throwException(new InvalidPipeException());
        exit;
    }

    /**
     * Returns the handled payload.
     * Optionally a key may be provided to get the corresponding cargo from the payload.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function return($key = '')
    {
        if( is_null($this->payload) ) {
            $this->throwException(new NoPayloadException());
        }

        if( is_null($this->pipes) ) {
            $this->throwException(new NoPipeException());
        }

        $payload = $this->payload;

        foreach( $this->pipes as $pipe ) {
            $payload = $pipe->handle($payload);
        }

        if( empty($key) ) {
            return $payload;
        }

        return $payload->get($key);
    }

    /**
     * Checks if given variable is an instance of the PipeInterface.
     *
     * @param $pipe
     *
     * @return bool
     */
    private function isPipe($pipe)
    {
        return $pipe instanceof PipeInterface;
    }

    /**
     * Adds a pipe to the pipe collection.
     *
     * @param PipeInterface $pipe
     *
     * @return $this
     */
    private function addPipe(PipeInterface $pipe)
    {
        $this->pipes[] = $pipe;
        return $this;
    }

    /**
     * Adds given cargo to the payload instance.
     *
     * @param $key
     * @param $cargo
     *
     * @return void
     */
    private function addToPayload($key, $cargo)
    {
        if( !$this->payload instanceof Payload ) {
            $this->payload = new Payload();
        }

        if( is_string($cargo) && class_exists($cargo) ) {
            $cargo = container($cargo);
        }

        $this->payload->add($key, $cargo);
    }

    /**
     * Gives a given exception to the exception handler.
     *
     * @param Exception $e
     *
     * @return void
     */
    private function throwException(Exception $e)
    {
        report($e);
    }
}