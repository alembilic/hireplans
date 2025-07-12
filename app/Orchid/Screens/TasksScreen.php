<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class TasksScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return null;
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Connect Google Account')
                ->icon('bs.google')
                ->route('platform.tasks.connect-google'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('layouts.tasks.tasks-layout'),
        ];
    }

    /**
     * Get the permissions required to access this screen.
     *
     * @return iterable|null The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }
} 