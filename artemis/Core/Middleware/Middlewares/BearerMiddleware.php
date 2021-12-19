<?php


namespace Artemis\Core\Middleware\Middlewares;


use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Middleware\Traits\hasBearerToken;
use Artemis\Core\Middleware\Traits\hasFails;


class BearerMiddleware implements MiddlewareInterface
{
    use hasFails, hasBearerToken;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * Private bearer token
     *
     * @var string
     */
    private $own_token;

    /**
     * AuthDefaultMiddleware constructor.
     *
     * @param string $database Database key
     * @param string $own_token Private bearer token
     */
    public function __construct($database, $own_token)
    {
        $this->database = $database;
        $this->own_token = $own_token;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        if( !$this->requestBearerToken() ) {
            $this->middlewareFails();
        }

        if( $this->own_token !== $this->request_token ) {
            $this->middlewareFails();
        }
    }
}