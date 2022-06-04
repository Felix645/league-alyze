<?php

namespace App\Http\Controllers\LiveComponents;

use App\Http\Requests\Matches\GetHomeRequest;
use App\Models\Champion;
use App\Models\Role;

class HomeController
{
    public function loadHome(GetHomeRequest $req) : string
    {
        $req->validate();

        $top_champions = Champion::getTopPerformingChampions($req->mode);
        $top_roles = Role::getTopRoles($req->mode);

        $home_html = view('components.home.content', compact('top_champions', 'top_roles'))->render();

        $data = [
            'html_content' => $home_html
        ];

        return api()->data($data)->ok();
    }
}