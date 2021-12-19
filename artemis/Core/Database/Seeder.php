<?php


namespace Artemis\Core\Database;


use Artemis\Support\Arr;

abstract class Seeder
{
    /**
     * Runs the seed implementation.
     *
     * @return void
     */
    public static function seed()
    {
        $instance = new static();
        $instance->run();
    }

    /**
     * Cleans the given table.
     *
     * @param string $database
     * @param string $table
     *
     * @return void
     */
    protected function freshSeed($database, $table)
    {
        $sql = "DELETE FROM $table; DBCC CHECKIDENT ('$table', RESEED, 0);";
        \Artemis\Client\Facades\Database::connect($database)->unprepared($sql);
    }

    /**
     * Cleans given table and resets its auto increment value.
     *
     * @param $database
     * @param $table
     *
     * @return void
     */
    protected function freshSeedReset($database, $table)
    {
        $this->freshSeed($database, $table);

        $sql = "ALTER TABLE $table AUTO_INCREMENT = 1";
        \Artemis\Client\Facades\Database::connect($database)->unprepared($sql);
    }

    /**
     * Returns a random lorem ipsum string with given amount of words.
     *
     * @param int $length
     *
     * @return string
     */
    protected function lorem($length = 10)
    {
        $lorem = ['lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'praesent', 'interdum', 'dictum', 'mi', 'non', 'egestas', 'nulla', 'in', 'lacus', 'sed', 'sapien', 'placerat', 'malesuada', 'at', 'erat', 'etiam', 'id', 'velit', 'finibus', 'viverra', 'maecenas', 'mattis', 'volutpat', 'justo', 'vitae', 'vestibulum', 'metus', 'lobortis', 'mauris', 'luctus', 'leo', 'feugiat', 'nibh', 'tincidunt', 'a', 'integer', 'facilisis', 'lacinia', 'ligula', 'ac', 'suspendisse', 'eleifend', 'nunc', 'nec', 'pulvinar', 'quisque', 'ut', 'semper', 'auctor', 'tortor', 'mollis', 'est', 'tempor', 'scelerisque', 'venenatis', 'quis', 'ultrices', 'tellus', 'nisi', 'phasellus', 'aliquam', 'molestie', 'purus', 'convallis', 'cursus', 'ex', 'massa', 'fusce', 'felis', 'fringilla', 'faucibus', 'varius', 'ante', 'primis', 'orci', 'et', 'posuere', 'cubilia', 'curae', 'proin', 'ultricies', 'hendrerit', 'ornare', 'augue', 'pharetra', 'dapibus', 'nullam', 'sollicitudin', 'euismod', 'eget', 'pretium', 'vulputate', 'urna', 'arcu', 'porttitor', 'quam', 'condimentum', 'consequat', 'tempus', 'hac', 'habitasse', 'platea', 'dictumst', 'sagittis', 'gravida', 'eu', 'commodo', 'dui', 'lectus', 'vivamus', 'libero', 'vel', 'maximus', 'pellentesque', 'efficitur', 'class', 'aptent', 'taciti', 'sociosqu', 'ad', 'litora', 'torquent', 'per', 'conubia', 'nostra', 'inceptos', 'himenaeos', 'fermentum', 'turpis', 'donec', 'magna', 'porta', 'enim', 'curabitur', 'odio', 'rhoncus', 'blandit', 'potenti', 'sodales', 'accumsan', 'congue', 'neque', 'duis', 'bibendum', 'laoreet', 'elementum', 'suscipit', 'diam', 'vehicula', 'eros', 'nam', 'imperdiet', 'sem', 'ullamcorper', 'dignissim', 'risus', 'aliquet', 'habitant', 'morbi', 'tristique', 'senectus', 'netus', 'fames', 'nisl', 'iaculis', 'cras', 'aenean'];
        $lorem_count = Arr::length($lorem);

        $random_lorem = '';
        for ($i = 0; $i < $length; $i++) {
            $random_lorem .= Arr::random($lorem, $lorem_count);

            if( $i < $length - 1 ) {
                $random_lorem .= ' ';
            }
        }
        return $random_lorem;
    }

    /**
     * Runs the seed method.
     *
     * @return void
     */
    abstract public function run() : void;
}