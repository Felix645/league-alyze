<?php


namespace Artemis\Core\Cache;


interface CachingInterface
{
    /**
     * Stores new content in the cache or retrieves it, if it was already cached
     *
     * @param string $key
     * @param int $seconds
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function store($key, $seconds, $callback);

    /**
     * Deletes the cache content with the given key.
     *
     * @param $key
     *
     * @return void
     */
    public function forget($key);

    /**
     * Replaces given chache with new data.
     *
     * @param string $key
     * @param int $seconds
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function rewrite($key, $seconds, $callback);
}