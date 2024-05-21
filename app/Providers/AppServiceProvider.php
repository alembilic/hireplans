<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\Dashboard;
use App\Models\Candidate;
use App\Observers\CandidateObserver;
use App\Models\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register CountryHelper
        $this->app->singleton('CountryHelper', function ($app) {
            return new \App\Helpers\CountryHelper();
        });

        $this->app->singleton('HelperFunc', function ($app) {
            return new \App\Helpers\HelperFunc();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ====================== https://orchid.software/en/docs/configuration/#model-classes
        // Dashboard::useModel(
        //     \Orchid\Platform\Models\User::class,
        //     \App\Models\User::class
        // );

        // Dashboard::configure([
        //     'models' => [
        //         User::class => MyCustomClass::class,
        //     ],
        // ]);
        // ======================

        Candidate::observe(CandidateObserver::class);
        User::observe(UserObserver::class);
    }
}
