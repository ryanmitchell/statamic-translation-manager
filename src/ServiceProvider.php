<?php

namespace RyanMitchell\StatamicTranslationManager;

use Illuminate\Support\Facades\Route;
use RyanMitchell\StatamicTranslationManager\Http\Controllers\TranslationController;
use Statamic\CP\Navigation\Nav;
use Statamic\Facades\CP\Nav as NavAPI;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();
            
        $this->loadViewsFrom(__DIR__.'/views', 'statamic-translation-manager');
        
        $this->publishes([
            __DIR__.'/../config/statamic-translations.php' => config_path('statamic-translations.php')
        ], 'config');

        $this->bootNav();
        $this->bootPermissions();
        $this->registerRoutes();
    }
    
    public function register()
    {
        $this->app->singleton('translation-manager', function () {
            return app(TranslationsManager::class);
        });
        
        $this->mergeConfigFrom(__DIR__.'/../config/statamic-translations.php', 'statamic-translations');
    }
    
    private function bootNav()
    {
        NavAPI::extend(fn (Nav $nav) => $nav
            ->content(__('Translations'))
            ->section(__('Tools'))
            ->can('manage translations')
            ->route('translation-manager.index')
            ->icon('content-writing')
        );
    }

    private function bootPermissions()
    {
        Permission::register('manage translations')
            ->label(__('Manage Translations'));
    }

    private function registerRoutes()
    {
        Statamic::pushCpRoutes(fn () => Route::name('translation-manager.')->prefix('translations')->group(function () {
            Route::get('/', [TranslationController::class, 'index'])->name('index');
            Route::get('{locale}/edit', [TranslationController::class, 'edit'])->name('edit');
            Route::post('{locale}/update', [TranslationController::class, 'update'])->name('update');
        }));
    }
}
