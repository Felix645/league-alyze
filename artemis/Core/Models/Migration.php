<?php


namespace Artemis\Core\Models;


use Artemis\Client\Eloquent;


class Migration extends Eloquent
{
    protected $table = 'artemis_migrations';
    public $timestamps = false;

    protected $casts = [
        'state' => 'boolean'
    ];

    protected $fillable = ['migration', 'batch', 'state'];
}