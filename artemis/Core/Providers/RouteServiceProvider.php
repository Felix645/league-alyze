<?php


namespace Artemis\Core\Providers;


use Artemis\Core\Interfaces\ProviderInterface;
use Artemis\Core\Maintenance\Maintenance;
use Artemis\Core\Routing\Router;


abstract class RouteServiceProvider implements ProviderInterface
{
    public const MAINTENANCE_SECRET_ROUTE = '/artemis/maintenance/secret/{secret}';

    public const MAINTENANCE_SECRET_ROUTE_NAME = 'artemis_maintenance_secret';

    /**
     * Router object
     *
     * @var Router
     */
    private $router;

    /**
     * RouteServiceProvider constructor.
     */
    public function __construct()
    {
        $this->router = container('router');
    }

    /**
     * Boots the routes from web.php routes file
     *
     * @return void
     */
    public function bootWebRoutes()
    {
        if( !app()->fromCLI() ) {
            $this->setMaintenanceSecretRoute();

            $this->router->middleware(['session', 'csrf', 'form_data'])->group(function() {
                require_once ROOT_PATH . 'resource/Routes/web.php';
            });
        }
    }

    /**
     * Boots the routes from api.php routes file
     *
     * @return void
     */
    public function bootApiRoutes()
    {
        if( !app()->fromCLI() ) {
            $this->router->prefix(app()->api_config()['route_prefix'])->group(function() {
                require_once ROOT_PATH . 'resource/Routes/api.php';
            });
        }
    }

    private function setMaintenanceSecretRoute()
    {
        if( !Maintenance::isActive() ) {
            return;
        }

        $route_action = function($secret) {
            $needs_json = request()->needsJson();

            if( Maintenance::secret() !== $secret ) {
                if( !$needs_json ) {
                    return '';
                }

                return [
                    'status' => 503,
                    'message' => 'Application is currently down for maintenance'
                ];
            }

            if( !$needs_json ) {
                setcookie(self::MAINTENANCE_SECRET_ROUTE_NAME, Maintenance::secret(), time() + 86400, '/');

                return 'Maintenance secret validated!';
            }

            return [
                'status' => 200,
                'message' => 'Maintenance secret validated!'
            ];
        };

        $this->router->get(self::MAINTENANCE_SECRET_ROUTE, $route_action)->name(self::MAINTENANCE_SECRET_ROUTE_NAME);
    }
}