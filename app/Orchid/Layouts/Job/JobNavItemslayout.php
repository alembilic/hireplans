<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;
use Illuminate\Support\Facades\Auth; // Add this line to import the Auth class

class JobNavItemslayout extends TabMenu
{
    /**
     * Get the menu elements to be displayed.
     *
     * @return Menu[]
     */
    protected function navigations(): iterable
    {
        $out = [
            // Menu::make('Home')
            //     ->route(config('platform.index')),

            Menu::make('Job Listings')
            ->route('platform.jobs.list')
            ->icon('bs.list'),
        ];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $out[] = Menu::make('Create Job')
                        ->route('platform.jobs.create')
                        ->icon('bs.plus-circle');
        }

        return $out;
    }
}
