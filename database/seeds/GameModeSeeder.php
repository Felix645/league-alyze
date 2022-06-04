<?php


namespace Database\Seeds;


use App\Models\GameMode;
use App\Services\GameModes;
use Artemis\Core\Database\Seeder;


class GameModeSeeder extends Seeder
{
    /**
     * @inheritDoc
     */
    public function run() : void
    {
        GameMode::query()->insert([
            ['key' => 'NORMAL',         'title' => 'Normal'],
            ['key' => 'RANKED_SOLO',    'title' => 'Ranked Solo'],
            ['key' => 'RANKED_DUO',     'title' => 'Ranked Duo'],
            ['key' => 'FLEX',           'title' => 'Flex'],
            ['key' => 'FLEX5',          'title' => 'Flex 5v5'],
        ]);
    }
}