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
        $champions = Champion::all();
        $roles = Role::all();

        return view('home.index', compact('top_champions', 'champions', 'roles'));
    }
}