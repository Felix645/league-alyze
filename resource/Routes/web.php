<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchesController;
use Artemis\Client\Facades\Router;


/* Define your web routes here */

Router::get('/', [HomeController::class, 'index']);

Router::get('/matches/new', [MatchesController::class, 'new'])->name('matches.index');
Router::post('/matches', [MatchesController::class, 'create'])->name('matches.create');

/* ------ */
