<?php


namespace Artemis\Core\Validation\Traits;


use DateTime;
use Exception;


trait hasDateFormats
{
    /**
     * Converts given string to timestamp, throws exception on failure
     *
     * @param string $value
     * @throws Exception
     *
     * @return string
     */
    protected function toTimestamp($value)
    {
        return $this->formatDate($value, 'Y-m-d H:i:s');
    }

    /**
     * Formats given date to a date string
     *
     * @param string $value
     * @throws Exception
     *
     * @return string
     */
    protected function toDate($value)
    {
        return $this->formatDate($value, 'Y-m-d');
    }

    /**
     * Gets date string by given format
     *
     * @param string $value
     * @param string $format
     * @throws Exception
     *
     * @return string
     */
    private function formatDate($value, $format)
    {
        $date = new DateTime($value);
        return $date->format($format);
    }
}