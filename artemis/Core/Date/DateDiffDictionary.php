<?php


namespace Artemis\Core\Date;


use DateInterval;


class DateDiffDictionary
{
    /**
     * Localization object
     *
     * @var DateDiffLocalization
     */
    private $local;

    /**
     * DateDiffDictionary constructor.
     *
     * @param DateDiffLocalization $localization
     */
    public function __construct($localization)
    {
        $this->local = $localization;
    }

    /**
     * Gets date difference in words
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    public function get($date_interval) : string
    {
        if( $date_interval->y !== 0 )
            return $this->getYear($date_interval);

        if( $date_interval->m !== 0 )
            return $this->getMonth($date_interval);

        if( $date_interval->d !== 0 )
            return $this->getDay($date_interval);

        if( $date_interval->h !== 0 )
            return $this->getHour($date_interval);

        if( $date_interval->i !== 0 )
            return $this->getMinute($date_interval);

        //if( $date_interval->s !== 0 )
        //    return $this->getSecond($date_interval);

        return $this->local->getJustNow();
    }

    /**
     * Gets the difference in years
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getYear($date_interval)
    {
        return $this->getString($date_interval->y, $date_interval, $this->local->getYear(), $this->local->getYear(true));
    }

    /**
     * Gets the difference in words
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getMonth($date_interval)
    {
        return $this->getString($date_interval->m, $date_interval, $this->local->getMonth(), $this->local->getMonth(true));
    }

    /**
     * Gets the difference in days
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getDay($date_interval)
    {
        return $this->getString($date_interval->d, $date_interval, $this->local->getDay(), $this->local->getDay(true));
    }

    /**
     * Gets the difference in hours
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getHour($date_interval)
    {
        return $this->getString($date_interval->h, $date_interval, $this->local->getHour(), $this->local->getHour(true));
    }

    /**
     * Gets the difference in minutes
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getMinute($date_interval)
    {
        return $this->getString($date_interval->i, $date_interval, $this->local->getMinute(), $this->local->getMinute(true));
    }

    /**
     * Gets the difference in seconds
     *
     * @param DateInterval $date_interval
     *
     * @return string
     */
    private function getSecond($date_interval)
    {
        return $this->getString($date_interval->s, $date_interval, $this->local->getSecond(), $this->local->getSecond(true));
    }

    /**
     * Gets the differnce string
     *
     * @param int $amount
     * @param DateInterval $date_interval
     * @param string $singular
     * @param string $plural
     *
     * @return string
     */
    private function getString($amount, $date_interval, $singular, $plural)
    {
        if( self::checkPlural($amount) )
            return $this->appendBeforeAfter($date_interval, $date_interval->format($plural));

        return $this->appendBeforeAfter($date_interval, $date_interval->format($singular));
    }

    /**
     * Checks if the difference amount demands a plural expression
     *
     * @param int $amount
     *
     * @return bool
     */
    private function checkPlural($amount)
    {
        return $amount > 1;
    }

    /**
     * Appends past or future tense either before the string or after, based on localization
     *
     * @param DateInterval $date_interval
     * @param string $string
     *
     * @return string
     */
    private function appendBeforeAfter($date_interval, $string)
    {
        if( $date_interval->invert === 1 )
            return $this->local->appended(true) ? $this->local->getFuture() . $string : $string . $this->local->getFuture();

        return $this->local->appended() ? $this->local->getPast() . $string : $string . $this->local->getPast();
    }
}