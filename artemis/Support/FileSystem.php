<?php


namespace Artemis\Support;


use DirectoryIterator;


class FileSystem
{
    /**
     * Gets contents of given file.
     *
     * @param string $file_path
     *
     * @return false|string
     */
    public static function getContents(string $file_path)
    {
        return file_get_contents($file_path);
    }

    /**
     * Overwrites the given file with the given content. Creates a new file if the given file does not exist.
     *
     * @param string $file_path     Path to the file to be overwritten.
     * @param string $content       Content that is to be written.
     *
     * @return false|int Number of bytes that were written. FALSE on failure.
     */
    public static function overwrite(string $file_path, string $content)
    {
        return file_put_contents($file_path, $content);
    }

    /**
     * Checks if given file exists.
     *
     * @param string $file_path Path to the file that is to be checked.
     *
     * @return bool True if the file exists, false otherwise.
     */
    public static function exists(string $file_path) : bool
    {
        return file_exists($file_path);
    }

    /**
     * Checks if given directory exists.
     *
     * @param string $dir Directory path.
     *
     * @return bool
     */
    public static function dirExists(string $dir) : bool
    {
        return is_dir($dir);
    }

    /**
     * Creates a given directory with the given rights.
     *
     * @param string $dir Directory path to be created.
     * @param int $rights (optional) Rights to be applied to that directory.
     *
     * @return void
     */
    public static function createDir(string $dir, int $rights = 0777)
    {
        $oldmask = self::umask(0);
        mkdir($dir, $rights, true);
        self::umask($oldmask);
    }

    /**
     * Changes the current umask and returns the old value.
     *
     * @param int $mask New umask value.
     *
     * @return int Old umask value.
     */
    public static function umask(int $mask) : int
    {
        return umask($mask);
    }

    /**
     * Deletes ALL files within the given directory.
     *
     * @param string $dir Directory path.
     *
     * @return void
     */
    public static function clearDir(string $dir)
    {
        $dir = new DirectoryIterator($dir);

        // Deleting all the files in the list
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {

                // Delete the given file
                unlink($fileinfo->getPathname());
            }
        }
    }
}