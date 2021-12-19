<?php

namespace App\Models;

use Artemis\Client\Eloquent;

/**
 * @property int $id
 * @property string $name
 * @property string $icon_path
 */
class Champion extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = 'false';
}