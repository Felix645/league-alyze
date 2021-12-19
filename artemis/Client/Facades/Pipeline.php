<?php


namespace Artemis\Client\Facades;


use Artemis\Core\Pipeline\Payload;
use Artemis\Core\Pipeline\PipeInterface;


/**
 * Class Pipeline
 * @package Artemis\Client\Facades
 *
 * @method static \Artemis\Core\Pipeline\Pipeline send(string|array $key, mixed|null $cargo = null) Defines a part of the payload. Optionally $key may be provided as an array to provide multiple cargoes at once.
 * @method static \Artemis\Core\Pipeline\Pipeline through(string|array|PipeInterface $input) Adds a Pipe or a collection of pipes to the pipeline.
 * @method static Payload|mixed return(string $key = '') Returns the handled payload. Optionally a key may be provided to get the corresponding cargo from the payload.
 *
 * @uses \Artemis\Core\Pipeline\Pipeline::send()
 * @uses \Artemis\Core\Pipeline\Pipeline::through()
 * @uses \Artemis\Core\Pipeline\Pipeline::return()
 */
class Pipeline extends Facade
{
    /**
     * @inheritDoc
     */
    protected static function getAccessor()
    {
        return 'pipeline';
    }
}