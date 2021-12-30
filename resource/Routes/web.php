<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchesController;
use Artemis\Client\Facades\Router;
use App\Http\Controllers\LiveComponents as Live;


/* Define your web routes here */

Router::get('/', [HomeController::class, 'index'])->name('home.index');

Router::get('/matches', [MatchesController::class, 'index'])->name('matches.index');
Router::post('/matches', [MatchesController::class, 'create'])->name('matches.create');

Router::get('/live-components/matches/load', [Live\MatchesController::class, 'loadMatches'])->name('live.matches.load');

/* ------ */
