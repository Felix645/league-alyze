<?php

namespace App\Models;

use Artemis\Client\Eloquent;

/**
 * @property int $id
 * @property string $title
 */
class GameMode extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = false;

    protected $fillable = ['id', 'title'];
}