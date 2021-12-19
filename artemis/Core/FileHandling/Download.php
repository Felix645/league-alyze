<?php


namespace Artemis\Core\FileHandling;


use Artemis\Support\FileSystem;
use Artemis\Core\Http\Traits\hasHeaderHandling;
use Artemis\Core\FileHandling\Exceptions\Download\NoInputException;
use Artemis\Core\FileHandling\Exceptions\Download\FileNotFoundException;


class Download
{
    use hasHeaderHandling;

    /**
     * Path to file location
     *
     * @var string
     */
    private $file_path;

    /**
     * Binary data.
     *
     * @var mixed
     */
    private $binary;

    /**
     * Name of the file
     *
     * @var string
     */
    private $filename;

    /**
     * Mime-Type of the file
     *
     * @var string
     */
    private $file_type;

    /**
     * Identifier if the file should be displayed in the browser.
     *
     * @var bool
     */
    private $view_in_browser = false;

    /**
     * Executes the download
     *
     * @throws FileNotFoundException
     * @throws NoInputException
     *
     * @return void
     */
    public function execute()
    {

        if( isset($this->file_path) && !isset($this->api_response) ) {
            if( !FileSystem::exists($this->file_path) ) {
                throw new FileNotFoundException();
            }

            $this->downloadFilePath();
        }

        if( !isset($this->file_path) && isset($this->api_response) ) {
            $this->downloadApiResponse();
        }

        throw new NoInputException();
    }

    /**
     * Sets the path to the file to be downloaded
     *
     * @param string $file_path
     * @throws FileNotFoundException
     *
     * @return Download
     */
    public function file($file_path, $view_in_browser = false)
    {
        $this->view_in_browser = $view_in_browser;

        if( !FileSystem::exists($file_path) ) {
            throw new FileNotFoundException();
        }

        $this->file_path = $file_path;
        return $this;
    }

    /**
     * Sets the response data from a curl request
     *
     * @param mixed $response
     *
     * @return Download
     */
    public function api_response($response, $view_in_browser = false)
    {
        $this->view_in_browser = $view_in_browser;

        return $this->binary($response);
    }

    /**
     * Sets binary data to be downloaded.
     *
     * @param mixed $binary_data
     *
     * @return $this
     */
    public function binary($binary_data, $view_in_browser = false)
    {
        $this->view_in_browser = $view_in_browser;

        $this->binary = $binary_data;
        return $this;
    }

    /**
     * Sets binary data from an hexadecimal string.
     *
     * @param string $hex
     *
     * @return $this
     */
    public function fromHex($hex, $view_in_browser = false)
    {
        $this->view_in_browser = $view_in_browser;

        $this->binary = hex2bin($hex);
        return $this;
    }

    /**
     * Sets the file name for the download
     *
     * @param string $filename
     *
     * @return Download
     */
    public function as($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * Sets the mime-type of the file
     *
     * @param string $type
     *
     * @return Download
     */
    public function setFileType($type)
    {
        $this->file_type = $type;
        return $this;
    }

    /**
     * Executes the download from given file path
     *
     * @return void
     */
    private function downloadFilePath()
    {
        if( !FileSystem::exists($this->file_path) )
            exit;

        header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Type: ' . $this->file_type);
        header("Content-Transfer-Encoding: Binary");
        header("Content-length: ".$this->getFileSize());

        if( $this->view_in_browser ) {
            header("Content-disposition: inline; filename=\"".$this->filename."\"");
        } else {
            header("Content-disposition: attachment; filename=\"".$this->filename."\"");
        }

        readfile($this->file_path);
        exit;
    }

    /**
     * Executes the download from a given api response
     *
     * @return void
     */
    private function downloadApiResponse()
    {
        header("Content-Type: " . $this->file_type);
        header("Content-Transfer-Encoding: Binary");

        if( $this->view_in_browser ) {
            header("Content-disposition: inline; filename=\"".$this->filename."\"");
        } else {
            header("Content-disposition: attachment; filename=\"".$this->filename."\"");
        }

        echo $this->binary;
        exit;
    }

    /**
     * Gets the file size with the file_path property
     *
     * @return int
     */
    private function getFileSize()
    {
        $file_size = filesize($this->file_path);

        if( false === $file_size )
            exit;

        return $file_size;
    }
}