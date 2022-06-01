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
    public function index() : View
    {
        $matches = Game::getPagination();

        return view('matches.index', [
            'matches' => $matches,
            'first_call' => true
        ]);
    }

    public function create(CreateMatchRequest $req) : RedirectionInterface
    {
        $req->validate();

        $played_as = Champion::find($req->played_as);
        $played_against = Champion::find($req->played_against);

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