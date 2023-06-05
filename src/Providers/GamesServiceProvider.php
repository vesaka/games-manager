<?php

namespace Vesaka\Games\Providers;

use Vesaka\Core\Providers\BaseServiceProvider;
use Vesaka\Core\Traits\Providers\CliProviderTrait;
use Vesaka\Core\Traits\Providers\ConfigProviderTrait;
use Vesaka\Core\Traits\Providers\RoutesProviderTrait;
use Vesaka\Core\Traits\Providers\ViewsProviderTrait;

/**
 * Description of GamesServiceProvider
 *
 * @author vesak
 */
class GamesServiceProvider extends BaseServiceProvider {
    //put your code here
    use ConfigProviderTrait;
    use ViewsProviderTrait;
    use RoutesProviderTrait;
    use CliProviderTrait;

    protected $routes = [
        'web' => [
            'middleware' => [],
        ],
        'games' => [
            'prefix' => 'play',
        ],
        'api' => [
            'prefix' => 'api',
        ],
    ];

    protected $adminRoutes = [
        'admin' => [
            'prefix' => 'admin',
        ],
    ];

    protected $middlewares = [
        'game:exists' => \Vesaka\Games\Http\Middleware\EnsureGameExists::class,
    ];

    protected string $title = 'game';

    public function register(): void {
        $this->configs();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerAdminRoutes();
        $this->registerPackageCommands();

        if (! defined('HEADER_GAME_NAME')) {
            define('HEADER_GAME_NAME', 'X-Game-Type');
        }
    }
}
