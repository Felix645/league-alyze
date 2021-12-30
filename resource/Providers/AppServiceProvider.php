<?php


namespace Artemis\Resource\Providers;


use App\Models\Champion;
use App\Models\Role;
use Artemis\Client\Facades\View;
use Artemis\Core\Providers\AppServiceProvider as ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * @inheritDoc
     */
    public function register()
    {
        //
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        View::share([
            'champions' => Champion::all(),
            'roles' => Role::all()
        ]);
    }
}