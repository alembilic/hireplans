<?php

namespace App\Orchid\Screens\Job;

use App\Models\Job;
use App\Orchid\Layouts\Job\JobListLayout;
use App\Orchid\Layouts\Job\JobNavItemslayout;
use Orchid\Screen\Screen;
use App\Orchid\Layouts\Job\JobFiltersLayout;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;

class JobListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'jobs' => Job::with(['employer', 'createdBy'])
                ->leftJoin('employers', 'jobs.employer_id', '=', 'employers.id')
                ->leftJoin('users', 'employers.user_id', '=', 'users.id')
                ->select('jobs.*', 'employers.name AS employer_name',)
                ->filters(JobFiltersLayout::class)
                ->defaultSort('jobs.created_at', 'desc')
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
        return 'Job Listings';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $actions = [];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $actions[] = Link::make('Add New Job')
                        ->route('platform.jobs.create')
                        ->icon('bs.plus-circle');
        }

        return $actions;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            JobFiltersLayout::class,
            JobListLayout::class
        ];
    }

    /**
     * Delete the job
     *
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        $job = Job::findOrFail($request->get('id'));

        $job->delete();

        Toast::info(__('Job was removed'));
    }
}
