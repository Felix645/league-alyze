<?php

namespace App\Models;

use Artemis\Client\Eloquent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $icon_path
 */
class Champion extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = false;

    public function matchesPlayedAs() : HasMany
    {
        return $this->hasMany(Game::class, 'played_as');
    }

    public static function getTopPerformingChampions() : Collection
    {
        $matches = Game::query()
            ->with(['champion_played_as', 'role'])
            ->selectRaw(
                '
                role_id, 
                played_as, 
                COUNT(id) as games_played, 
                ((SUM(is_win) / COUNT(id)) * 100) as winrate, 
                (SUM(kills) / COUNT(id)) as kills_avg, 
                (SUM(deaths) / COUNT(id)) as deaths_avg, 
                (SUM(assists) / COUNT(id)) as assists_avg,
                (SUM(creep_score) / COUNT(id)) as cs_avg,
                ((((SUM(minutes) * 60) + SUM(seconds)) / 60) / COUNT(id)) as game_time_avg
                '
            )
            ->groupBy('played_as')
            ->groupBy('role_id')
            ->havingRaw('games_played > 1')
            ->orderByDesc('winrate')
            ->orderBy('played_as')
            ->limit(20)
            ->get();

        $champion_ids = [];

        if( $matches->count() <= 3 ) {
            return $matches;
        }

        $count = 0;
        return $matches->filter(function($value) use (&$champion_ids, &$count) {
            if( in_array($value->played_as, $champion_ids) ) {
                return false;
            }

            if( $value->games_played <= 4 ) {
                return false;
            }

            if( $count >= 3 ) {
                return false;
            }

            $champion_ids[] = $value->played_as;
            $count++;
            return true;
        });
    }
}