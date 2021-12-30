<?php

namespace App\Http\Controllers;

use App\Models\Champion;
use App\Models\Role;
use Artemis\Core\Template\View;

class HomeController
{
    public function index() : View
    {
        $top_champions = Champion::getTopPerformingChampions();
        $top_roles = Role::getTopRoles();

        return view('home.index', compact('top_champions', 'top_roles'));
    }
}