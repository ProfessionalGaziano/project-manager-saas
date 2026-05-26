<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Task;
use App\Models\Project;
use App\Observers\TaskObserver;
use App\Observers\ProjectObserver;
use App\Models\Invoice;
use App\Observers\InvoiceObserver;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         Task::observe(TaskObserver::class);
         Project::observe(ProjectObserver::class);
         Invoice::observe(InvoiceObserver::class);
    }
}
