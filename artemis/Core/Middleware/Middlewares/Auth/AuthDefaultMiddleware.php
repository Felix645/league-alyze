<?php


namespace Artemis\Core\Middleware\Middlewares\Auth;


use Artemis\Core\Interfaces\MiddlewareInterface;
use Artemis\Core\Middleware\Traits\hasFails;
use Artemis\Core\Middleware\Traits\hasUser;


class AuthDefaultMiddleware implements MiddlewareInterface
{
    use hasUser, hasFails;

    /**
     * Database key
     *
     * @var string
     */
    private $database;

    /**
     * AuthDefaultMiddleware constructor.
     *
     * @param string $database Database key
     */
    public function __construct($database)
    {
        $this->database = $database;
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $user_id = container('session')->getUserID($this->database);

        if( !isset($user_id) ) {
            $this->executeFails();
            $this->displayErrorPage();
        }

        if( !$this->findUser($this->database, $user_id) ) {
            $this->executeFails();
            $this->displayErrorPage();
        }

        auth($this->database)->setUser($this->user)->valid = true;
    }
}