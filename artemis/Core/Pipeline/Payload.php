<?php


namespace Artemis\Core\Pipeline;


class Payload
{
    /**
     * Map of defined cargoes.
     *
     * @var array
     */
    private $cargoes = [];

    /**
     * Adds cargo to the payload.
     *
     * @param string $key
     * @param mixed $cargo
     *
     * @return $this
     */
    public function add($key, $cargo)
    {
        $this->cargoes[$key] = $cargo;
        return $this;
    }

    /**
     * Gets cargo from the payload.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->cargoes[$key];
    }
}