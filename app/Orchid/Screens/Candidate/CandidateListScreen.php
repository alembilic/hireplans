<?php

namespace App\Orchid\Screens\Candidate;

use Orchid\Screen\Screen;
use App\Models\Candidate;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Candidate\CandidateListLayout;
use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use App\Orchid\Filters\Candidate\CandidateNameFilter;
use App\Orchid\Layouts\Candidate\CandidateFiltersLayout;

class CandidateListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'candidates' => Candidate::with('user')
                ->leftJoin('users', 'candidates.user_id', '=', 'users.id')
                ->select('candidates.*', 'users.name as user_name')
                ->filters(CandidateFiltersLayout::class)
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
        return 'Candidates';
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
        // dd($this->query());
        return [
            Layout::block([CandidateNavItemsLayout::class])->vertical(),

            CandidateFiltersLayout::class,

            CandidateListLayout::class,
        ];
    }
}
