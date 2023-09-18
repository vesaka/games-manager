<?php

namespace Vesaka\Games\Providers;

use Vesaka\Core\Providers\BaseServiceProvider;
use Vesaka\Core\Traits\Providers\{
    CliProviderTrait,
    ConfigProviderTrait,
    RoutesProviderTrait,
    ViewsProviderTrait,
    EventsProviderTrait
};

use Illuminate\Auth\Events\{Registered, Authenticated};
use Vesaka\Games\Listeners\TransferGuestToPlayer;


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
    use EventsProviderTrait;

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

    protected $events = [
        Registered::class => [
            TransferGuestToPlayer::class,
        ],
        Authenticated::class => [
            TransferGuestToPlayer::class,
        ],
    ];

    protected string $title = 'game';

    public function register(): void {
        $this->configs();
        $this->registerEvents();
        $this->registerViews();
        $this->registerRoutes();
        $this->registerAdminRoutes();
        $this->registerPackageCommands();

        if (! defined('HEADER_GAME_NAME')) {
            define('HEADER_GAME_NAME', 'X-Game-Type');
        }

        if(!defined('GUEST_EMAIL_DOMAIN')) {
            define('GUEST_EMAIL_DOMAIN', '@guest.com');
        }
    }
}
