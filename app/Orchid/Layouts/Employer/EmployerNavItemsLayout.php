<?php

namespace App\Orchid\Layouts\Employer;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class EmployerNavItemsLayout extends TabMenu
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

            Menu::make('Employers')
                ->route('platform.employers.pipeline')
                ->icon('bs.funnel'),

            Menu::make('Create Employer')
                ->route('platform.employers.create')
                ->icon('bs.plus-circle'),
        ];
    }
}
