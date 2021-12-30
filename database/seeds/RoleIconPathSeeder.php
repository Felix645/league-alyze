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
            'icon_path' => 'img/roles/icons/Top.png'
        ]);

        Role::where('name', 'Jungle')->update([
            'icon_path' => 'img/roles/icons/Jungle.png'
        ]);

        Role::where('name', 'Mid')->update([
            'icon_path' => 'img/roles/icons/Mid.png'
        ]);

        Role::where('name', 'Bot')->update([
            'icon_path' => 'img/roles/icons/Bot.png'
        ]);

        Role::where('name', 'Support')->update([
            'icon_path' => 'img/roles/icons/Support.png'
        ]);
    }
}