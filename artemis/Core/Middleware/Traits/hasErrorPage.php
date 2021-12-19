<?php


namespace Artemis\Core\Middleware\Traits;


use Artemis\Core\Exception\UnauthorizedException;


trait hasErrorPage
{
    /**
     * Display error page.
     *
     * @return void
     */
    private function displayErrorPage()
    {
        report(new UnauthorizedException());
    }
}