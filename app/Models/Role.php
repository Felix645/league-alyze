<?php

namespace App\Models;

use Artemis\Client\Eloquent;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property int $id
 * @property string $name
 * @property string $icon_path
 */
class Role extends Eloquent
{
    protected $connection = 'db';

    public $timestamps = false;

    protected $fillable = ['id', 'name', 'icon_path'];

    public static function getTopRoles(string $mode) : Collection
    {
        $top_roles = self::queryTopRoles($mode);
        $missing_roles = self::queryMissingRoles($top_roles);

        /** @var Role $missing_role */
        foreach( $missing_roles as $missing_role ) {
            $game = self::createMissingRoleGameModel($missing_role);
            $top_roles->add($game);
        }

        return $top_roles;
    }

    private static function queryTopRoles(string $mode) : Collection
    {
        return Game::query()
            ->with(['role'])
            ->when($mode !== GameMode::ALL_ID, function($query) use ($mode) {
                return $query->where('game_mode_id', $mode);
            })
            ->selectRaw('
                role_id, 
                COUNT(id) as games_played, 
                ((SUM(is_win) / COUNT(id)) * 100) as winrate, 
                (SUM(kills) / COUNT(id)) as kills_avg, 
                (SUM(deaths) / COUNT(id)) as deaths_avg, 
                (SUM(assists) / COUNT(id)) as assists_avg,
                (SUM(creep_score) / COUNT(id)) as cs_avg,
                ((((SUM(minutes) * 60) + SUM(seconds)) / 60) / COUNT(id)) as game_time_avg
            ')
            ->groupBy('role_id')
            ->orderByDesc('winrate')
            ->get();
    }

    private static function queryMissingRoles(Collection $top_roles) : Collection
    {
        return Role::query()->whereNotIn('id', $top_roles->pluck('role_id'))->get();
    }

    private static function createMissingRoleGameModel(Role $missing_role) : Game
    {
        $game = new Game();

        $game->role_id = $missing_role->id;
        $game->games_played = 0;
        $game->winrate = 'N/A';
        $game->kills_avg = 'N/A';
        $game->deaths_avg = 'N/A';
        $game->assists_avg = 'N/A';
        $game->cs_avg = 'N/A';
        $game->game_time_avg = 'N/A';
        $game->setRelation('role', $missing_role);

        return $game;
    }
}