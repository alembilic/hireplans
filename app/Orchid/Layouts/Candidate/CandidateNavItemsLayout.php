<?php

namespace App\Orchid\Layouts\Candidate;

use Orchid\Screen\Actions\Menu;
use Orchid\Screen\Layouts\TabMenu;

class CandidateNavItemsLayout extends TabMenu
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

            Menu::make('Candidates')
                ->route('platform.candidates.list')
                ->icon('bs.list'),

            Menu::make('Create Candidate')
                ->route('platform.candidates.create')
                ->icon('bs.plus-circle'),
        ];
    }
}
