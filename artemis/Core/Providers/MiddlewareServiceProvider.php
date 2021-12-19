<?php


namespace Artemis\Core\Providers;


use Artemis\Core\DI\Container;
use Artemis\Core\Interfaces\ProviderInterface;
use Artemis\Core\Middleware\MiddlewareStack;
use Artemis\Core\Middleware\Middlewares\CSRFMiddleware;
use Artemis\Core\Middleware\Middlewares\FormDataMiddleware;
use Artemis\Core\Middleware\Middlewares\SessionMiddleware;


abstract class MiddlewareServiceProvider implements ProviderInterface
{
    /**
     * Instance of the middleware stack.
     *
     * @var MiddlewareStack
     */
    protected $middleware_stack;

    /**
     * Middlewares to be registered.
     *
     * @var array
     */
    protected $middlewares;

    /**
     * List of middleware aliases to be added as global middlewares.
     *
     * @var string[]
     */
    protected $global_middlewares;

    /**
     * List of core middlewares.
     *
     * @var string[]
     */
    private $core_middlewares = [
        'session'   => SessionMiddleware::class,
        'csrf'      => CSRFMiddleware::class,
        'form_data' => FormDataMiddleware::class
    ];

    /**
     * MiddlewareServiceProvider constructor.
     */
    public function __construct()
    {
        $this->middleware_stack = container(MiddlewareStack::class);
    }

    /**
     * Binds the core middlewares to the DI-Container.
     *
     * @return void
     */
    final protected function bindCoreMiddlewares()
    {
        /* @var Container $container */
        $container = container();

        $container->bind(SessionMiddleware::class, function() {
            return new SessionMiddleware();
        });

        $container->bind(CSRFMiddleware::class, function() {
            return new CSRFMiddleware();
        });

        $container->bind(FormDataMiddleware::class, function() {
            return new FormDataMiddleware();
        });
    }

    /**
     * Registers middlewares.
     * 
     * @return void
     */
    final protected function registerMiddlewares()
    {
        $middlewares = $this->core_middlewares + $this->middlewares;
        
        foreach( $middlewares as $alias => $middleware_class ) {
            $this->middleware_stack->registerMiddleware($alias, $middleware_class);
        }
    }

    /**
     * Adds given global middlewares.
     *
     * @return void
     */
    final protected function addGlobalMiddlewares()
    {
        foreach( $this->global_middlewares as $alias ) {
            $this->middleware_stack->addGlobalMiddleware($alias);
        }
    }
}