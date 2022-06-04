<?php

namespace App\Http\Controllers;

use App\Models\Champion;
use App\Models\Role;
use Artemis\Core\Template\View;

class HomeController
{
    public function index() : View
    {
        $top_champions = Champion::getTopPerformingChampions('all');
        $top_roles = Role::getTopRoles('all');

        return view('home.index', compact('top_champions', 'top_roles'));
    }
}