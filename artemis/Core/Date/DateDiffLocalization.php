<?php


namespace Artemis\Core\Date;


class DateDiffLocalization
{
    /**
     * Identfier if the past/future tense should be appended(false) or prepended(true)
     *
     * @var array
     */
    private static $append = [
        'de' => [
            'past'      => true,
            'future'    => true,
        ],
        'en' => [
            'past'      => true,
            'future'    => false,
        ]
    ];

    /**
     * Tense to be used if the origin date is lower
     *
     * @var string[]
     */
    private static $past = [
        'de' => 'in ',
        'en' => 'in '
    ];

    /**
     * Tense to be used if the origin date is higher
     *
     * @var string[]
     */
    private static $future = [
        'de' => 'vor ',
        'en' => ' ago'
    ];

    /**
     * Year localization
     *
     * @var string[][]
     */
    private static $year = [
        'de' => [
            'singular'  => '%y Jahr',
            'plural'    => '%y Jahren'
        ],
        'en' => [
            'singular'  => '%y year',
            'plural'    => '%y years'
        ]
    ];

    /**
     * Month localization
     *
     * @var string[][]
     */
    private static $month = [
        'de' => [
            'singular'  => '%m Monat',
            'plural'    => '%m Monaten'
        ],
        'en' => [
            'singular'  => '%m month',
            'plural'    => '%m months'
        ]
    ];

    /**
     * Day localization
     *
     * @var string[][]
     */
    private static $day = [
        'de' => [
            'singular'  => '%d Tag',
            'plural'    => '%d Tagen'
        ],
        'en' => [
            'singular'  => '%d day',
            'plural'    => '%d days'
        ]
    ];

    /**
     * Hour localization
     *
     * @var string[][]
     */
    private static $hour = [
        'de' => [
            'singular'  => '%h Stunde',
            'plural'    => '%h Stunden'
        ],
        'en' => [
            'singular'  => '%h hour',
            'plural'    => '%h hours'
        ]
    ];

    /**
     * Minute localization
     *
     * @var string[][]
     */
    private static $minute = [
        'de' => [
            'singular'  => '%i Minute',
            'plural'    => '%i Minuten'
        ],
        'en' => [
            'singular'  => '%i minute',
            'plural'    => '%i minutes'
        ]
    ];

    /**
     * Second localization
     *
     * @var string[][]
     */
    private static $second = [
        'de' => [
            'singular'  => '%s Sekunde',
            'plural'    => '%s Sekunden'
        ],
        'en' => [
            'singular'  => '%s second',
            'plural'    => '%s seconds'
        ]
    ];

    /**
     * Localization if the DateTime's are less than a minute apart
     *
     * @var string[]
     */
    private static $just_now = [
        'de' => 'gerade eben',
        'en' => 'just now'
    ];

    /**
     * Localization identifier string.
     * Defined in DateDiff object
     *
     * @var string
     */
    private $local;

    /**
     * DateDiffLocalization constructor.
     *
     * @param string $local
     */
    public function __construct($local)
    {
        $this->local = $local;
    }

    /**
     * Gets the past tense
     *
     * @return string
     */
    public function getPast()
    {
        return self::$past[$this->local];
    }

    /**
     * Gets the future tense
     *
     * @return string
     */
    public function getFuture()
    {
        return self::$future[$this->local];
    }

    /**
     * Checks if the given tense should be appended or prepended
     *
     * @param bool $future
     *
     * @return bool
     */
    public function appended($future = false)
    {
        if( $future )
            return self::$append[$this->local]['future'];

        return self::$append[$this->local]['past'];
    }

    /**
     * Gets the year localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getYear($plural = false)
    {
        if( $plural )
            return self::$year[$this->local]['plural'];

        return self::$year[$this->local]['singular'];
    }

    /**
     * Gets the month localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getMonth($plural = false)
    {
        if( $plural )
            return self::$month[$this->local]['plural'];

        return self::$month[$this->local]['singular'];
    }

    /**
     * Gets the day localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getDay($plural = false)
    {
        if( $plural )
            return self::$day[$this->local]['plural'];

        return self::$day[$this->local]['singular'];
    }

    /**
     * Gets the hour localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getHour($plural = false)
    {
        if( $plural )
            return self::$hour[$this->local]['plural'];

        return self::$hour[$this->local]['singular'];
    }

    /**
     * Gets the minute localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getMinute($plural = false)
    {
        if( $plural )
            return self::$minute[$this->local]['plural'];

        return self::$minute[$this->local]['singular'];
    }

    /**
     * Gets the second localization
     *
     * @param bool $plural
     *
     * @return string
     */
    public function getSecond($plural = false)
    {
        if( $plural )
            return self::$second[$this->local]['plural'];

        return self::$second[$this->local]['singular'];
    }

    /**
     * Gets the localization if the DateTime's are less then a minute apart
     *
     * @return string
     */
    public function getJustNow()
    {
        return self::$just_now[$this->local];
    }
}