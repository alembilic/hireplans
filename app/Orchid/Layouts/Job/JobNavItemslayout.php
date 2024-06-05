<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class JobNavItemslayout extends TabMenu
{
    /**
     * Get the menu elements to be displayed.
     *
     * @return Menu[]
     */
    protected function navigations(): iterable
    {
        return [
            // Menu::make('Home')
            //     ->route(config('platform.index')),

            Menu::make('Job Listings')
                ->route('platform.jobs.list')
                ->icon('bs.list'),

            Menu::make('Create Job')
                ->route('platform.jobs.create')
                ->icon('bs.plus-circle'),
        ];
    }
}
