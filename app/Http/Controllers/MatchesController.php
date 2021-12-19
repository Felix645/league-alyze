<?php

namespace App\Http\Controllers;

use App\Http\Requests\Matches\CreateMatchRequest;
use App\Models\Champion;
use App\Models\Game;
use App\Models\Role;
use Artemis\Core\Interfaces\RedirectionInterface;
use Artemis\Core\Template\View;

class MatchesController
{
    public function new() : View
    {
        $roles = Role::all();
        $champions = Champion::all();

        return view('matches.new', compact('roles', 'champions'));
    }

    public function create(CreateMatchRequest $req) : RedirectionInterface
    {
        $req->validate();

        /** @var Champion $played_as */
        $played_as = Champion::where('name', $req->played_as)->first();
        /** @var Champion $played_against */
        $played_against = Champion::where('name', $req->played_against)->first();

        if( !$played_as )  {
            return redirect()->back()->withError('played_as', 'Please enter a valid champion.');
        }

        if( !$played_against )  {
            return redirect()->back()->withError('played_against', 'Please enter a valid champion.');
        }

        if( !Role::where('id', $req->role_id)->exists() ) {
            return redirect()->back()->withError('role_id', 'Please enter a role.');
        }

        $body = $req->validated();

        $body['played_as'] = $played_as->id;
        $body['played_against'] = $played_against->id;

        Game::query()->create($body);

        return redirect()->back()->withSuccess('match', 'Match created successfully!');
    }
}