<?php

namespace App\Models;

use Artemis\Client\Eloquent;

class Role extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = 'false';
}