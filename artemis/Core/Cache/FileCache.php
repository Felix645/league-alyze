<?php


namespace Artemis\Core\Cache;

use Artemis\Support\FileSystem;

class FileCache implements CachingInterface
{
    /**
     * Root cache path.
     *
     * @var string
     */
    private $cache_path = ROOT_PATH . 'cache/data';

    /**
     * Cache extension.
     *
     * @var string
     */
    private $extension = '.cache';

    /**
     * Cache file path.
     *
     * @var string
     */
    private $file_path;

    /**
     * Stores new content in the cache or retrieves it, if it was already cached
     *
     * @param string $key
     * @param int $seconds
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function store($key, $seconds, $callback)
    {
        $this->setFilePath($key);

        if( !FileSystem::exists($this->file_path) ) {
            return $this->writeCacheData($callback);
        }

        if( $this->cacheExpired($seconds) ) {
            return $this->writeCacheData($callback);
        }

        return $this->getCacheData();
    }

    /**
     * Deletes the cache content with the given key.
     *
     * @param $key
     *
     * @return void
     */
    public function forget($key)
    {
        $this->setFilePath($key);
        $this->deleteCache();
    }

    /**
     * Replaces given chache with new data.
     *
     * @param string $key
     * @param int $seconds
     * @param \Closure $callback
     *
     * @return mixed
     */
    public function rewrite($key, $seconds, $callback)
    {
        $this->forget($key);
        return $this->store($key, $seconds, $callback);
    }

    /**
     * Deletes the cache.
     *
     * @return void
     */
    private function deleteCache()
    {
        if( !FileSystem::exists($this->file_path) ) {
            return;
        }

        unlink($this->file_path);
    }

    /**
     * Gets the cache data.
     *
     * @return mixed
     */
    private function getCacheData()
    {
        $cache_content = FileSystem::getContents($this->file_path);

        return unserialize($cache_content);
    }

    /**
     * Sets the file path to the cache file.
     *
     * @param $key
     *
     * @return void
     */
    private function setFilePath($key)
    {
        $id = md5($key);

        $file = $id . $this->extension;
        $this->file_path = $this->cache_path . '/' . $file;
    }

    /**
     * Checks if the cache expired or not.
     *
     * @param int $seconds
     *
     * @return bool
     */
    private function cacheExpired($seconds)
    {
        return date('Y-m-d H:i:s', filemtime($this->file_path)) < now('-' . $seconds . ' seconds');
    }

    /**
     * Writes data to the cache.
     *
     * @param \Closure $callback
     *
     * @return mixed
     */
    private function writeCacheData($callback)
    {
        $data = $callback();

        if( !FileSystem::dirExists($this->cache_path) ) {
            FileSystem::createDir($this->cache_path);
        }

        $cache = fopen($this->file_path, 'w');
        fwrite($cache, serialize($data));

        return $data;
    }
}