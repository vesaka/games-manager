<?php

namespace Vesaka\Games\Providers;

use Route;
use Illuminate\Support\Str;
use Vesaka\Core\Traits\Providers\ConfigProviderTrait;
use Vesaka\Core\Traits\Providers\ViewsProviderTrait;
use Vesaka\Core\Traits\Providers\RoutesProviderTrait;
use Vesaka\Core\Traits\Providers\CliProviderTrait;
use Vesaka\Core\Providers\BaseServiceProvider;

/**
 * Description of GamesServiceProvider
 *
 * @author vesak
 */
class GamesServiceProvider extends BaseServiceProvider {
    //put your code here
    use ConfigProviderTrait, ViewsProviderTrait, RoutesProviderTrait, CliProviderTrait;
    
    protected $routes = [
        'web' => [
            'middleware' => [],
        ],
        'games' => [
            'prefix' => 'play'
        ],
        'api' => [
            'prefix' => 'api'
        ]
    ];
    
    protected $adminRoutes = [
        'admin' => [
            'prefix' => 'admin'
        ]
    ];


    protected $middlewares = [
        'game:exists' => \Vesaka\Games\Http\Middleware\EnsureGameExists::class
    ];
    
    protected string $title = 'game';
        
    public function register(): void {
        $this->configs();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerAdminRoutes();
        $this->registerPackageCommands();
        
        if (defined('HEADER_GAME_NAME')) {
            define('HEADER_GAME_NAME', 'X-Game-Type');
        }
    }
    

}
