<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\Dashboard;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(Dashboard $dashboard): void
    {
        $permissions = ItemPermission::group('Portal')
            // ->addPermission('monitor', 'Access to the system monitor')
            ->addPermission('reference.view.my', 'View my references')
            ->addPermission('job.apply', 'Apply for a job')
            ->addPermission('job.application.view.my', 'View my job applications');

        $dashboard->registerPermissions($permissions);
    }
}
