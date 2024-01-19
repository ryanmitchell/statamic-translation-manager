<?php

namespace RyanMitchell\StatamicTranslationManager\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Statamic\Statamic;
use RyanMitchell\StatamicTranslationManager\ServiceProvider;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            ServiceProvider::class
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'ryanmitchell/statamic-translation-manager' => [
                'id' => 'ryanmitchell/statamic-translation-manaager',
                'namespace' => 'RyanMitchell\\StatamicTranslationManager',
            ],
        ];

        config(['statamic.users.repository' => 'file']);
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = ['assets', 'cp', 'forms', 'routes', 'static_caching', 'sites', 'stache', 'system', 'users'];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require __DIR__."/../vendor/statamic/cms/config/{$config}.php");
        }

        // Setting the user repository to the default flat file system
        $app['config']->set('statamic.users.repository', 'file');

        // Assume the pro edition within tests
        $app['config']->set('statamic.editions.pro', true);

        $app->useLangPath(__DIR__.'/__fixtures');
    }

    protected function getSampleFilePath($file)
    {
        return realpath(dirname(__FILE__)."/samples/{$file}");
    }
}
