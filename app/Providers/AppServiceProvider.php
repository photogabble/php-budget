<?php

namespace App\Providers;

use App\Classifier;
use App\Repositories\SuggestionEngineMappingsRepository;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Classifier::class, function(Application $app){
            return new Classifier('categories_classification', $app->make('cache'), $app->make(SuggestionEngineMappingsRepository::class));
        });
    }
}
