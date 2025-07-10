<?php

namespace App\Orchid\Screens\Candidate;

use Orchid\Screen\Screen;
use App\Models\Candidate;
use App\Models\User;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Candidate\CandidateListLayout;
use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
// use App\Orchid\Filters\Candidate\CandidateNameFilter;
use App\Orchid\Layouts\Candidate\CandidateFiltersLayout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Link;

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
        return [
            Link::make('Add New Candidate')
                ->icon('bs.plus-circle')
                ->route('platform.candidates.create'),
        ];
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
            CandidateFiltersLayout::class,

            CandidateListLayout::class,
        ];
    }
    public function remove(Request $request): void
    {
        $candidate = Candidate::findOrFail($request->get('id'));

        User::findOrFail($candidate->user->id)->delete();

        $candidate->delete();

        Toast::info(__('Candidate was removed'));
    }
}
