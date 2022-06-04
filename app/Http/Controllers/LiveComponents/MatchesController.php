<?php

namespace App\Http\Controllers\LiveComponents;

use App\Http\Requests\Matches\GetMatchesRequest;
use App\Models\Game;

class MatchesController
{
    public function loadMatches(GetMatchesRequest $req) : string
    {
        $req->validate();

        $matches = Game::getPagination($req->mode);

        $matches_html = '';

        foreach( $matches as $match ) {
            $matches_html .= view('components.matches.match-row', compact('match'))->render();
        }

        $data = [
            'html_matches' => $matches_html,
            'html_button' => view('components.matches.load-button', [
                'matches' => $matches,
                'first_call' => false
            ])->render()
        ];

        return api()->data($data)->ok();
    }
}