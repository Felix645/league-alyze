<?php


namespace Artemis\Core\Pipeline;


interface PipeInterface
{
    /**
     * Handles the given payload and returns it.
     *
     * @param Payload $payload
     *
     * @return Payload
     */
    public function handle(Payload $payload) : Payload;
}