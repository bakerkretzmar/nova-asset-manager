<?php

namespace Bakerkretzmar\AssetManager;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Bakerkretzmar\AssetManager\Http\Middleware\Authorize;

class AssetManagerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'nova-asset-manager');

        $this->app->booted(function () {
            $this->routes();
        });

        Nova::serving(function (ServingNova $event) {
            // Nova::script('asset-manager-field', __DIR__.'/../dist/js/field.js');
            // Nova::style('filemanager-field', __DIR__.'/../dist/css/field.css');
        });
    }

    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/nova-asset-manager')
            ->group(__DIR__.'/../routes/api.php');
    }

    public function register()
    {
        //
    }
}
