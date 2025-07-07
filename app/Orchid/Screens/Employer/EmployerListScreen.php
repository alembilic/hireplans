<?php

namespace App\Orchid\Screens\Employer;

use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use App\Orchid\Layouts\Employer\EmployerFiltersLayout;
use App\Orchid\Layouts\Employer\EmployerListLayout;
use App\Orchid\Layouts\Employer\EmployerNavItemsLayout;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Screen;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class EmployerListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'employers' => Employer::with('user')
                ->leftJoin('users', 'employers.user_id', '=', 'users.id')
                ->select('employers.*', 'employers.name as employer_name', 'users.name as user_name', 'users.email as user_email')
                ->filters(EmployerFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
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
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([EmployerNavItemsLayout::class])->vertical(),

            EmployerFiltersLayout::class,

            EmployerListLayout::class
        ];
    }
    /**
     * Delete the employer
     *
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        $employer = Employer::findOrFail($request->get('id'));

        User::findOrFail($employer->user->id)->delete();

        $employer->delete();

        Toast::info(__('Employer was removed'));
    }
}
