<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\MorbidityWeekCalendar;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        Paginator::useBootstrap();

        View::composer('*', function ($view) {
            $mw_settings = cache()->remember(
                'mw_settings',
                3600,
                fn () => MorbidityWeekCalendar::whereDate('start_date', '<=', date('Y-m-d'))
                ->whereDate('end_date', '>=', date('Y-m-d'))
                ->first(),
            );

            $view->with([
                'mws_present_mw' => $mw_settings->mw,
                'mws_present_year' => $mw_settings->year,

                'mws_current_mw' => $mw_settings->getPreviousWeek()->mw,
                'mws_current_year' => $mw_settings->getPreviousWeek()->year,
                'mws_current_mw_start' => Carbon::parse($mw_settings->getPreviousWeek()->start_date),
                'mws_current_mw_end' => Carbon::parse($mw_settings->getPreviousWeek()->end_date),
            ]);
        });
    }
}
