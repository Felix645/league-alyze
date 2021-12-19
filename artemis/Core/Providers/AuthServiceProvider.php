<?php


namespace Artemis\Core\Providers;


use App\Models\User;
use Artemis\Core\Auth\UserRepository;
use Artemis\Core\Interfaces\ProviderInterface;
use Artemis\Core\Middleware\MiddlewareStack;


abstract class AuthServiceProvider implements ProviderInterface
{
    /**
     * UserRepository
     *
     * @var UserRepository
     */
    protected $user_repository;

    /**
     * Middleware Stack
     *
     * @var MiddlewareStack
     */
    private $middleware_stack;

    /**
     * Default user class
     *
     * @var string
     */
    protected $default_user = User::class;

    /**
     * List of Users with their database as key and their class as value
     *
     * @var array
     */
    protected $users = [];

    /**
     * List of Middlewares with their alias as key and their instance as value
     *
     * @var array
     */
    protected $middlewares = [];

    /**
     * AuthServiceProvider constructor.
     */
    public function __construct()
    {
        $this->user_repository = container(UserRepository::class);
        $this->middleware_stack = container(MiddlewareStack::class);
    }

    /**
     * Registers given users.
     *
     * @return void
     */
    final protected function registerUsers()
    {
        $this->registerDefaultUser();

        foreach( $this->users as $database => $user) {
            $this->user_repository->registerUser($database, $user);
        }
    }

    /**
     * Registers given middlewares.
     *
     * @return void
     */
    final protected function registerMiddlewares()
    {
        foreach( $this->middlewares as $alias => $middleware ) {
            $this->middleware_stack->registerMiddleware($alias, $middleware);
        }
    }

    /**
     * Registers the default user.
     *
     * @return void
     */
    private function registerDefaultUser()
    {
        $this->user_repository->registerDefaultUser($this->default_user);
    }
}