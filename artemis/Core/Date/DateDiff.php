<?php


namespace Artemis\Core\Date;


use DateInterval;
use DateTime;


class DateDiff
{
    /**
     * DateInterval interface
     *
     * @var DateInterval
     */
    private $date_interval;

    /**
     * List of available localizations
     *
     * @var string[]
     */
    private $available_locals = [
        'de',
        'en'
    ];

    /**
     * Identifier for the localization
     *
     * @var string
     */
    private $localization = 'de';

    /**
     * DateDiff constructor.
     *
     * @param DateTime $origin
     * @param DateTime $target
     */
    public function __construct($origin, $target)
    {
        if( !$this->date_interval = $origin->diff($target) ) {
            $origin = new DateTime();
            $target = new DateTime();
            $this->date_interval = $origin->diff($target);
        }
    }

    /**
     * Sets localization for date diff
     * If the localization is not available it defaults to 'de'
     *
     * @param string $localization
     *
     * @return $this
     */
    public function localize(string $localization)
    {
        if( !in_array($localization, $this->available_locals) )
            $localization = 'de';

        $this->localization = $localization;
        return $this;
    }

    /**
     * Gets the difference in words dynamically
     *
     * @return string
     */
    public function inWords()
    {
        $localization = new DateDiffLocalization($this->localization);
        $dictionary = new DateDiffDictionary($localization);
        return $dictionary->get($this->date_interval);
    }

    /**
     * Gets difference in years
     *
     * @return int
     */
    public function years()
    {
        return $this->date_interval->y;
    }

    /**
     * Gets difference in months
     *
     * @return int
     */
    public function months()
    {
        return $this->date_interval->m;
    }

    /**
     * Gets difference in days
     *
     * @return int
     */
    public function days()
    {
        return $this->date_interval->d;
    }

    /**
     * Gets difference in hours
     *
     * @return int
     */
    public function hours()
    {
        return $this->date_interval->h;
    }

    /**
     * Gets difference in minutes
     *
     * @return int
     */
    public function minutes()
    {
        return $this->date_interval->i;
    }

    /**
     * Gets difference in minutes
     *
     * @return int
     */
    public function seconds()
    {
        return $this->date_interval->s;
    }
}