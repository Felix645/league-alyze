<?php


namespace Artemis\Resource\Providers;


use Artemis\Core\Providers\AuthServiceProvider as ServiceProvider;


class AuthServiceProvider extends ServiceProvider
{
    // ---------------------------------------------------------------------------------------------------------------- //
    // Framework Middlewares                                                                                            //
    // ---------------------------------------------------------------------------------------------------------------- //
    // \Artemis\Core\Middleware\Middlewares\BearerMiddleware::__construct($database, $own_token)                        //
    // \Artemis\Core\Middleware\Middlewares\Auth\AuthDefaultMiddleware::__construct($database)                          //
    // \Artemis\Core\Middleware\Middlewares\Auth\AuthCurlMiddleware::__construct($database)                             //
    // \Artemis\Core\Middleware\Middlewares\Auth\AuthTokenMiddleware::__construct($database)                            //
    // \Artemis\Core\Middleware\Middlewares\Auth\AuthBearerMiddleware::__construct($database, $secret_key, $secret_iv)  //
    // ---------------------------------------------------------------------------------------------------------------- //
    // Annotations                                                                                                      //
    // ---------------------------------------------------------------------------------------------------------------- //
    // - Each middleware provides the addFails($callback) setter method.                                                //
    //   When setting a callback function it will be executed when the middleware fails.                                //
    //                                                                                                                  //
    // - AuthCurlMiddleware: This middleware has the setUserKey($key) and setTokenKey($key) setter methods.             //
    //                       By setting one of these you may specify the corresponding key in the request body.         //
    // ---------------------------------------------------------------------------------------------------------------- //

    /**
     * List of Users with their database as key and their class string as value
     *
     * @var array
     */
    protected $users = [
        // 'database' => \App\Models\MyUser::class
    ];

    /**
     * Define your middlewares here.
     *
     * @inheritDoc
     */
    public function register()
    {
        // $this->middlewares['datbase_key'] => new \MyMiddlewares\Middleware();
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->registerUsers();
        $this->registerMiddlewares();
    }
}