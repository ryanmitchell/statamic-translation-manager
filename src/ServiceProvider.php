<?php

namespace RyanMitchell\StatamicTranslationManager;

use Illuminate\Support\Facades\Route;
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

        $this->bootNav();
        $this->bootPermissions();
        $this->registerRoutes();
    }
    
    private function bootNav()
    {
        NavAPI::extend(fn (Nav $nav) => $nav
            ->content(__('Translation Manager'))
            ->section(__('Tools'))
            ->can('manage translations')
            ->route('statamic.translation_manager.edit')
            ->icon('content-writing')
        );
    }

    private function bootPermissions()
    {
        Permission::register('manage translations')
            ->label(__('Use Translation Manager'));
    }

    private function registerRoutes()
    {
        Statamic::pushCpRoutes(fn () => Route::name('statamic.translation_manager.')->group(function () {
            Route::get('edit', [TranslationController::class, 'edit'])->name('edit');
            Route::post('update', [TranslationController::class, 'update'])->name('update');
        }));
    }
}
