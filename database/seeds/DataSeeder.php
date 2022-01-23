<?php


namespace Database\Seeds;


use Artemis\Core\Database\Seeder;


class DataSeeder extends Seeder
{
    /**
     * @inheritDoc
     */
    public function run() : void
    {
        $this->freshSeedReset('db', 'matches');

        ChampionSeeder::seed();
        RoleSeeder::seed();
        RoleIconPathSeeder::seed();
    }
}