<?php


namespace Database\Seeds;


use App\Models\Role;
use Artemis\Core\Database\Seeder;


class RoleSeeder extends Seeder
{
    /**
     * @inheritDoc
     */
    public function run() : void
    {
        $this->freshSeedReset('db', 'roles');

        Role::query()->insert([
            ['name' => 'Top'],
            ['name' => 'Jungle'],
            ['name' => 'Mid'],
            ['name' => 'Bot'],
            ['name' => 'Support'],
        ]);
    }
}