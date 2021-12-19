<?php


namespace Artemis\Core\Date;


use DateTime;
use Exception;


class Date
{
    /**
     * Format to be used
     *
     * @var string
     */
    private $format = 'Y-m-d H:i:s';

    /**
     * The DateTime object
     *
     * @var DateTime
     */
    private $date_time;

    /**
     * Date constructor.
     *
     * @param string $input
     */
    public function __construct($input = 'now')
    {
        try {
            $this->date_time = new DateTime($input);
        } catch(Exception $e) {
            $this->date_time = new DateTime();
        }
    }

    /**
     * Returns the formatted date time string when object is used as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->get();
    }

    /**
     * Returns the formatted date time as string
     *
     * @return string
     */
    public function get()
    {
        if( !isset($this->date_time) ) {
            $this->now();
        }

        return $this->date_time->format($this->format);
    }

    /**
     * Sets the format for the output
     *
     * @param string $format
     *
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;
        return $this;
    }

    /**
     * Gets the current timestamp
     *
     * @return $this
     */
    public function now()
    {
        $this->date_time = new DateTime();
        return $this;
    }

    /**
     * Modifies the current DateTime
     *
     * @param string $mod
     *
     * @return $this
     */
    public function modify($mod)
    {
        $this->date_time->modify($mod);
        return $this;
    }

    /**
     * Gets a difference object with the current date time and given datetime string
     *
     * @param string $target
     *
     * @return DateDiff
     */
    public function diffWith($target)
    {
        try {
            $target_object = new DateTime($target);
        } catch(Exception $e) {
            $target_object = new DateTime();
        }

        return new DateDiff($this->date_time, $target_object);
    }
}