<?php


namespace Artemis\Core\Http;


class File
{
    /**
     * Initial file name
     *
     * @var string
     */
    private $name;

    /**
     * File type
     *
     * @var string
     */
    private $type;

    /**
     * File extension
     *
     * @var string
     */
    private $extension;

    /**
     * File size in byte
     *
     * @var int
     */
    private $size;

    /**
     * Temporary file name
     *
     * @var string
     */
    private $tmp_name;

    /**
     * Error type
     *
     * @var int
     */
    private $error;

    /**
     * File constructor.
     *
     * @param array $file
     */
    public function __construct($file)
    {
        $this->name = $file["name"];
        $this->size = $file["size"];
        $this->tmp_name = $file["tmp_name"];
        $this->error = $file["error"];
        $this->type = $file["type"] == "text/csv" ? $file["type"] : mime_content_type($this->tmp_name);
        $this->extension = pathinfo($file["name"])['extension'];
    }

    /**
     * Get the initial file name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the temporary file name
     *
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmp_name;
    }

    /**
     * Get the file type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the file extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Get the file size
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get the file error
     *
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }
}