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
        ChampionSeeder::seed();
        RoleSeeder::seed();
    }
}