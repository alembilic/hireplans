<?php

namespace App\Orchid\Screens\Employer;

use Orchid\Screen\Screen;
use Illuminate\Http\RedirectResponse;

class EmployerListScreen extends Screen
{
    /**
     * Redirect to the employer pipeline instead of showing the old list
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
        return 'Employers';
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

    /**
     * Redirect to the pipeline
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Redirect to the pipeline
     */
    public function layout(): iterable
    {
        return [];
    }

    /**
     * Redirect to the employer pipeline
     */
    public function mount()
    {
        // Redirect to the pipeline instead of showing the old list
        redirect()->route('platform.employers.pipeline')->send();
    }
}
