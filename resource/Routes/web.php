<?php


use Artemis\Client\Facades\Router;
use App\Http\Controllers\ExampleController;


/* Define your web routes here */

Router::get('/example', [ExampleController::class, 'index'])->name('exampleRoute');

/* ------ */
