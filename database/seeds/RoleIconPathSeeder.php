<?php


namespace Database\Seeds;


use App\Models\Role;
use Artemis\Core\Database\Seeder;


class RoleIconPathSeeder extends Seeder
{
    /**
     * @inheritDoc
     */
    public function run() : void
    {
        Role::where('name', 'Top')->update([
            'icon_path' => 'img/roles/icons/Top.svg'
        ]);

        Role::where('name', 'Jungle')->update([
            'icon_path' => 'img/roles/icons/Jungle.svg'
        ]);

        Role::where('name', 'Mid')->update([
            'icon_path' => 'img/roles/icons/Mid.svg'
        ]);

        Role::where('name', 'Bot')->update([
            'icon_path' => 'img/roles/icons/Bot.svg'
        ]);

        Role::where('name', 'Support')->update([
            'icon_path' => 'img/roles/icons/Support.svg'
        ]);
    }
}