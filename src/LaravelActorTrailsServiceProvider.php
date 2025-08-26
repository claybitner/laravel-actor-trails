<?php

namespace DigitalIndoorsmen\LaravelActorTrails;

use DigitalIndoorsmen\LaravelActorTrails\Commands\LaravelActorTrailsCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelActorTrailsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-actor-trails')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('add_actor_trails_columns')
            ->hasCommand(LaravelActorTrailsCommand::class);
    }
}
