<?php
/**
 * Copyright (c) Since 2024 InnoShop - All Rights Reserved
 *
 * @link       https://www.innoshop.com
 * @author     InnoShop <team@innoshop.com>
 * @license    https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace InnoShop\Panel;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use InnoShop\Common\Middleware\ContentFilterHook;
use InnoShop\Common\Middleware\EventActionHook;
use InnoShop\Common\Models\Admin;
use InnoShop\Panel\Console\Commands\ChangeRootPassword;
use InnoShop\Panel\Middleware\AdminAuthenticate;
use InnoShop\Panel\Middleware\GlobalPanelData;
use InnoShop\Panel\Middleware\SetPanelLocale;
use Illuminate\Support\Facades\View;
use App\Models\Submission;

class PanelServiceProvider extends ServiceProvider
{
    /**
     * Boot panel service provider.
     *
     * @return void
     */
    public function boot(): void
    {
        if (! has_install_lock()) {
            return;
        }

        load_settings();
        $this->registerWebRoutes();
        $this->registerGuard();
        $this->registerUploadFileSystem();
        $this->registerCommands();
        $this->loadTranslations();
        $this->loadViewTemplates();
        $this->loadViewComponents();

        View::composer('panel::components.layout.sidebar', function ($view) {
            $pending_count = Submission::where('status', 'pending')->count();
            $view->with('pending_submissions_count', $pending_count);
        });
    }

    /**
     * @return void
     */
    public function register(): void
    {
        app('router')->aliasMiddleware('admin_auth', AdminAuthenticate::class);
    }

    /**
     * Register admin user guard.
     */
    private function registerGuard(): void
    {
        Config::set('auth.providers.admin', [
            'driver' => 'eloquent',
            'model'  => Admin::class,
        ]);

        Config::set('auth.guards.admin', [
            'driver'   => 'session',
            'provider' => 'admin',
        ]);
    }

    /**
     * @return void
     */
    protected function registerUploadFileSystem(): void
    {
        Config::set('filesystems.disks.media', [
            'driver'      => 'local',
            'root'        => public_path('static/media'),
            'url'         => env('APP_URL').'/media',
            'visibility'  => 'public',
            'throw'       => true,
            'permissions' => [
                'file' => [
                    'public'  => 0755,
                    'private' => 0755,
                ],
                'dir' => [
                    'public'  => 0755,
                    'private' => 0755,
                ],
            ],
        ]);
    }

    /**
     * @return void
     */
    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ChangeRootPassword::class,
            ]);
        }
    }

    /**
     * Register admin panel routes.
     *
     * @return void
     */
    private function registerWebRoutes(): void
    {
        $router      = $this->app['router'];
        $middlewares = [EventActionHook::class, ContentFilterHook::class, GlobalPanelData::class, SetPanelLocale::class];
        foreach ($middlewares as $middleware) {
            $router->pushMiddlewareToGroup('panel', $middleware);
        }

        $adminName = panel_name();
        Route::prefix($adminName)
            ->middleware('panel')
            ->name("$adminName.")
            ->group(function () {
                $this->loadRoutesFrom(realpath(__DIR__.'/../routes/web.php'));
            });
    }

    /**
     * Register panel language
     * @return void
     */
    private function loadTranslations(): void
    {
        if (! is_dir(__DIR__.'/../lang')) {
            return;
        }

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'panel');
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/panel'),
        ], 'lang');
    }

    /**
     * Load view components.
     *
     * @return void
     */
    private function loadViewComponents(): void
    {
        $this->loadViewComponentsAs('panel', [
            'data-criteria'          => Components\Data\Criteria::class,
            'data-sorter'            => Components\Data\Sorter::class,
            'data-info'              => Components\Data\DataInfo::class,
            'layout-sidebar'         => Components\Layout\Sidebar::class,
            'chart-line'             => Components\Charts\Line::class,
            'chart-pie'              => Components\Charts\Pie::class,
            'form-codemirror'        => Components\Forms\Codemirror::class,
            'form-autocomplete-list' => Components\Forms\AutocompleteList::class,
        ]);
    }

    /**
     * Load templates
     *
     * @return void
     */
    private function loadViewTemplates(): void
    {
        $originViewPath = inno_path('panel/resources/views');
        $customViewPath = resource_path('views/vendor/panel');

        $this->publishes([
            $originViewPath => $customViewPath,
        ], 'views');

        $this->loadViewsFrom($originViewPath, 'panel');
    }
}
