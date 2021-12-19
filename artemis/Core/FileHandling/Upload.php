<?php


namespace Artemis\Core\FileHandling;


use Artemis\Core\FileHandling\Exceptions\Upload\DirCreationException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidExtensionException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidFileNameException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidFileNameLengthException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidMimeTypeException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidSizeException;
use Artemis\Core\FileHandling\Exceptions\Upload\InvalidUploadedFileException;
use Artemis\Core\FileHandling\Exceptions\Upload\MoveFileException;
use Artemis\Core\FileHandling\Exceptions\Upload\NoDestinationException;
use Artemis\Core\FileHandling\Exceptions\Upload\NoInputException;
use Artemis\Core\FileHandling\Exceptions\Upload\PhpUploadException;
use Artemis\Core\Http\File;
use Artemis\Support\FileSystem;


class Upload
{
    /**
     * Instance of a file to be uploaded
     *
     * @var File
     */
    private $file;

    /**
     * Collection of allowed mime types, if empty anything is allowed
     *
     * @var array
     */
    private $allowed_mime_types = [];

    /**
     * Collection of allowed extensions, if empty any extension is allowed
     *
     * @var array
     */
    private $allowed_extension = [];

    /**
     * Name under which the file is to be saved, if empty the original name is used
     *
     * @var string
     */
    private $file_name;

    /**
     * Maximum file size allowed
     *
     * @var int
     */
    private $max_size = 4097152;

    /**
     * Where the file is to be stored
     *
     * @var string
     */
    private $destination;

    /**
     * Sets the file destination and starts the upload process
     * Returns true on success or false on failure
     *
     * @param string $destination
     *
     * @throws NoInputException
     * @throws MoveFileException
     * @throws PhpUploadException
     * @throws InvalidUploadedFileException
     * @throws InvalidMimeTypeException
     * @throws InvalidExtensionException
     * @throws InvalidSizeException
     * @throws InvalidFileNameException
     * @throws InvalidFileNameLengthException
     * @throws NoDestinationException
     * @throws DirCreationException
     *
     * @return void
     */
    public function save($destination)
    {
        $this->destination = $destination;

        if( !isset($this->file) ) {
            throw new NoInputException();
        }

        $this->checkFile();
        $this->checkDirectory();
        $this->upload();
    }

    /**
     * Sets the file to be uploaded
     *
     * @param File $file
     *
     * @return Upload
     */
    public function input($file)
    {
        $this->file = $file;
        return $this;
    }

    /**
     * Sets the maximum file size in bytes
     *
     * @param int $max_size
     *
     * @return Upload
     */
    public function maxSize($max_size)
    {
        $this->max_size = $max_size;
        return $this;
    }

    /**
     * Sets file name under which the file is to be saved
     *
     * @param string $name
     *
     * @return $this
     */
    public function as($name)
    {
        $this->file_name = $name;
        return $this;
    }

    /**
     * Sets the allowed mime types, provided as single string or an array of mime types
     *
     * @param string|array $input
     *
     * @return Upload
     */
    public function allowMimeType($input)
    {
        if( is_string($input) )
            $this->allowed_mime_types[] = $input;

        if( is_array($input) ) {
            foreach( $input as $mime_type ) {
                $this->allowed_mime_types[] = $mime_type;
            }
        }

        return $this;
    }

    /**
     * Sets the allowed mime types, provided as single string or an array of mime types
     *
     * @param string|array $input
     *
     * @return Upload
     */
    public function allowExtension($input)
    {
        if( is_string($input) )
            $this->allowed_extension[] = $input;

        if( is_array($input) ) {
            foreach( $input as $extension ) {
                $this->allowed_extension[] = $extension;
            }
        }

        return $this;
    }

    /**
     * Checks if file is a valid upload
     *
     * @throws PhpUploadException
     * @throws InvalidUploadedFileException
     * @throws InvalidMimeTypeException
     * @throws InvalidExtensionException
     * @throws InvalidSizeException
     * @throws InvalidFileNameException
     * @throws InvalidFileNameLengthException
     *
     * @return void
     */
    private function checkFile()
    {
        if( UPLOAD_ERR_OK !== $this->file->getError() ) {
            throw new PhpUploadException($this->file->getError());
        }

        if( !is_uploaded_file($this->file->getTmpName()) ) {
            throw new InvalidUploadedFileException();
        }

        $this->checkMimeType();
        $this->checkExtension();
        $this->checkFileSize();
        $this->checkFilename();
        $this->checkFilenameLength();
    }

    /**
     * Checks if the given directory exists and creates it if it does not exist
     *
     * @throws NoDestinationException
     * @throws DirCreationException
     *
     * @return void
     */
    private function checkDirectory()
    {
        if( !isset($this->destination) ) {
           throw new NoDestinationException();
        }

        if( FileSystem::dirExists($this->destination) ) {
            return;
        }

        FileSystem::createDir($this->destination);

        if( !FileSystem::dirExists($this->destination) ) {
            throw new DirCreationException();
        }
    }

    /**
     * Uploads the given file, returns true on success and false on failure
     *
     * @throws MoveFileException
     *
     * @return void
     */
    private function upload()
    {
        $filename = $this->file_name ?? $this->file->getName();
        $path = $this->destination . '/' . $filename;

        if( move_uploaded_file($this->file->getTmpName(), $path) ) {
            return;
        }

        throw new MoveFileException();
    }

    /**
     * Checks the files mime type
     *
     * @throws InvalidMimeTypeException
     *
     * @return void
     */
    private function checkMimeType()
    {
        if( empty($this->allowed_mime_types) )
            return;

        if( in_array($this->file->getType(), $this->allowed_mime_types) ) {
            return;
        }

        throw new InvalidMimeTypeException();
    }

    /**
     * Checks the files extension
     *
     * @throws InvalidExtensionException
     *
     * @return void
     */
    private function checkExtension()
    {
        if( empty($this->allowed_extension) )
            return;

        if( in_array($this->file->getExtension(), $this->allowed_extension) ) {
            return;
        }

        throw new InvalidExtensionException();
    }

    /**
     * Checks the files size
     *
     * @throws InvalidSizeException
     *
     * @return void
     */
    private function checkFileSize()
    {
        if( $this->file->getSize() < $this->max_size ) {
            return;
        }

        throw new InvalidSizeException();
    }

    /**
     * Check if the file name is valid
     *
     * @throws InvalidFileNameException
     *
     * @return void
     */
    private function checkFilename()
    {
        $filename = $this->file->getName();

        if( !empty($filename) && (is_string($filename) || is_numeric($filename)) ) {
            return;
        }

        throw new InvalidFileNameException();
    }

    /**
     * Checks the length of the file name
     *
     * @throws InvalidFileNameLengthException
     *
     * @return void
     */
    private function checkFilenameLength()
    {
        if( mb_strlen($this->file->getName(),"UTF-8") < 255 ) {
            return;
        }

        throw new InvalidFileNameLengthException();
    }
}