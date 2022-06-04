<?php


namespace Artemis\Resource\Providers;


use App\Models\Champion;
use App\Models\GameMode;
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
            'champions' => Champion::query()->orderBy('name')->get(),
            'roles' => Role::all(),
            'modes' => GameMode::all(),
        ]);
    }
}