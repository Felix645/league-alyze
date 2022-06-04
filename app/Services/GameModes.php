<?php

namespace App\Services;

use App\Models\GameMode;

class GameModes
{
    public const NORMAL = 'NORMAL';

    public const RANKED_SOLO = 'RANKED_SOLO';

    public const RANKED_DUO = 'RANKED_DUO';

    public const FLEX = 'FLEX';

    public const FLEX5 = 'FLEX5';

    private int $normal_id;

    private int $ranked_solo_id;

    private int $ranked_duo_id;

    private int $flex_id;

    private int $flex5_id;

    private static ?GameModes $instance = null;

    private function __construct()
    {
        $modes = GameMode::all();

        $this->normal_id = $modes->where('key', 'NORMAL')->first()->id;
        $this->ranked_solo_id = $modes->where('key', 'RANKED_SOLO')->first()->id;
        $this->ranked_duo_id = $modes->where('key', 'RANKED_DUO')->first()->id;
        $this->flex_id = $modes->where('key', 'FLEX')->first()->id;
        $this->flex5_id = $modes->where('key', 'FLEX5')->first()->id;
    }

    public static function getId(string $key) : int
    {
        $instance = self::getInstance();

        return match (true) {
            $key === self::NORMAL       => $instance->normal(),
            $key === self::RANKED_SOLO  => $instance->rankedSolo(),
            $key === self::RANKED_DUO   => $instance->rankedDuo(),
            $key === self::FLEX         => $instance->flex(),
            $key === self::FLEX5        => $instance->flex5(),
            default                     => throw new \Exception('Invalid Game-Mode key given'),
        };
    }

    public static function getInstance() : static
    {
        if( self::$instance instanceof static ) {
            return self::$instance;
        }

        $instance = new self();

        self::$instance = $instance;

        return $instance;
    }

    public function normal() : int
    {
        return $this->normal_id;
    }

    public function rankedSolo() : int
    {
        return $this->ranked_solo_id;
    }

    public function rankedDuo() : int
    {
        return $this->ranked_duo_id;
    }

    public function flex() : int
    {
        return $this->flex_id;
    }

    public function flex5() : int
    {
        return $this->flex5_id;
    }
}