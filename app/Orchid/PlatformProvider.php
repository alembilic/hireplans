<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [

            Menu::make(__('My Profile'))
                ->icon('bs.person')
                ->route('platform.profile')
                ->permission('platform.index')
                ->title(__('Navigation')),

            Menu::make(__('My Applications'))
                ->icon('bs.person-workspace')
                ->route('platform.job_applications.my')
                ->permission('job.application.view.my'),

            Menu::make(__('My References'))
                ->icon('bs.person-fill-check')
                ->route('platform.references.my')
                ->permission('reference.view.my'),

            Menu::make(__('Candidates'))
                ->icon('bs.people')
                ->route('platform.candidates.list')
                ->permission('platform.systems.users'),

            Menu::make(__('Employers'))
                ->icon('bs.buildings')
                ->route('platform.employers.pipeline')
                ->permission('platform.systems.users'),

            Menu::make(__('Job Listing'))
                ->icon('bs.person-workspace')
                ->route('platform.jobs.list')
                ->permission('platform.systems.users'),

            Menu::make(__('Job Pipeline'))
                ->icon('bs.funnel')
                ->route('platform.jobs.pipeline')
                ->permission('platform.systems.users'),

            Menu::make(__('Job Applications'))
                ->icon('bs.card-list')
                ->route('platform.job_applications.list')
                ->permission('platform.systems.users'),

            Menu::make(__('Tasks'))
                ->icon('bs.check2-square')
                ->route('platform.tasks')
                ->permission('platform.systems.users'),

            Menu::make(__('Schedule'))
                ->icon('bs.calendar-event')
                ->route('platform.schedule')
                ->permission('platform.systems.users'),

            Menu::make(__('References'))
                ->icon('bs.person-fill-check')
                ->route('platform.references.list')
                ->permission('platform.systems.users'),

            Menu::make(__(''))
                ->divider(),

            // Menu::make(__('Add Candidate'))
            //     ->icon('bs.people')
            //     ->route('platform.candidates.create')
            //     ->permission('platform.systems.users')
            //     ->title(__('Candidates'))
            //     ->divider(),

            // Menu::make('Get Started')
            //     ->icon('bs.book')
            //     ->title('Navigation')
            //     ->route(config('platform.index')),

            // Menu::make('Sample Screen')
            //     ->icon('bs.collection')
            //     ->route('platform.example')
            //     ->badge(fn () => 6),

            // Menu::make('Form Elements')
            //     ->icon('bs.card-list')
            //     ->route('platform.example.fields')
            //     ->active('*/examples/form/*'),

            // Menu::make('Overview Layouts')
            //     ->icon('bs.window-sidebar')
            //     ->route('platform.example.layouts'),

            // Menu::make('Grid System')
            //     ->icon('bs.columns-gap')
            //     ->route('platform.example.grid'),

            // Menu::make('Charts')
            //     ->icon('bs.bar-chart')
            //     ->route('platform.example.charts'),

            // Menu::make('Cards')
            //     ->icon('bs.card-text')
            //     ->route('platform.example.cards')
            //     ->divider(),

            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),

            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),

            // Menu::make('Documentation')
            //     ->title('Docs')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://orchid.software/en/docs')
            //     ->target('_blank'),

            // Menu::make('Changelog')
            //     ->icon('bs.box-arrow-up-right')
            //     ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
            //     ->target('_blank')
            //     ->badge(fn () => Dashboard::version(), Color::DARK),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}
