<?php

namespace App\Models;

use Artemis\Client\Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property bool $is_win
 * @property int $role_id
 * @property int $played_as
 * @property int $played_against
 * @property int $kills
 * @property int $deaths
 * @property int $assists
 * @property int $creep_score
 * @property int $minutes
 * @property int $seconds
 * @property string $created_at
 * @property string $updated_at
 */
class Game extends Eloquent
{
    protected $connection = 'db';

    protected $table = 'matches';

    protected $fillable = [
        'id',
        'is_win',
        'role_id',
        'played_as',
        'played_against',
        'kills',
        'deaths',
        'assists',
        'creep_score',
        'minutes',
        'seconds',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_win' => 'boolean',
    ];

    public function champion_played_as() : BelongsTo
    {
        return $this->belongsTo(Champion::class, 'played_as');
    }

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}