<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use ConsoleTVs\Charts\Registrar as Charts;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('path.public', function() {
            return base_path().'/public_html';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Charts $charts)
    {
        Paginator::useBootstrap();

        $charts->register([
            \App\Charts\SampleChart::class,
            \App\Charts\DailySwabChart::class,
            \App\Charts\SituationalDailyConfirmedActiveChart::class,
            \App\Charts\SituationalGenderDistributionChart::class,
            \App\Charts\SituationalAgeDistributionChart::class,
            \App\Charts\SituationalActiveCasesBreakdownChart::class,
        ]);
    }
}
