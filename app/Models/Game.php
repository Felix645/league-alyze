<?php

namespace App\Models;

use Artemis\Client\Eloquent;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

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

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'id',
        'game_mode_id',
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

    public static function getPagination(string $mode) : Paginator
    {
        return self::query()
            ->with('champion_played_as', 'champion_played_against', 'role', 'mode')
            ->when($mode !== GameMode::ALL_ID, function($query) use ($mode) {
                return $query->where('game_mode_id', $mode);
            })
            ->latest()
            ->simplePaginate(7);
    }

    public function champion_played_as() : BelongsTo
    {
        return $this->belongsTo(Champion::class, 'played_as');
    }

    public function champion_played_against() : BelongsTo
    {
        return $this->belongsTo(Champion::class, 'played_against');
    }

    public function role() : BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function mode() : BelongsTo
    {
        return $this->belongsTo(GameMode::class, 'game_mode_id');
    }

    /**
     * Formats GETTER for created_at attribute
     *
     * @param $value
     *
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Europe/Berlin')
            ->toDateTimeString();
    }

    /**
     * Formats GETTER for updated_at attribute
     *
     * @param $value
     *
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Europe/Berlin')
            ->toDateTimeString();
    }
}