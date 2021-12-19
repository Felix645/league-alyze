<?php

namespace App\Models;

use Artemis\Client\Eloquent;

class Champion extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = 'false';
}