<?php

namespace App\Orchid\Screens\JobApplication;

use App\Models\JobApplication;
use App\Orchid\Layouts\JobApplication\JobApplicationFiltersLayout;
use App\Orchid\Layouts\JobApplication\JobApplicationListLayout;
use App\Orchid\Layouts\JobApplication\MyJobApplicationListLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Toast;

class MyJobApplicationListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $user = Auth::user();

        return [
            'jobApplications' => JobApplication::with('job', 'candidate')
                ->leftJoin('jobs', 'job_applications.job_id', '=', 'jobs.id')
                ->leftJoin('employers', 'jobs.employer_id', '=', 'employers.id')
                ->leftJoin('candidates', 'job_applications.candidate_id', '=', 'candidates.id')
                ->leftJoin('users', 'candidates.user_id', '=', 'users.id')
                ->where('users.id', $user->id)
                ->select('job_applications.*',
                        'jobs.title AS job_title',
                        'users.name AS candidate_name',
                        'employers.name AS employer_name'
                        )
                ->filters(JobApplicationFiltersLayout::class)
                ->defaultSort('job_applications.created_at', 'desc')
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
        return 'My Job Applications';
    }

    public function permission(): array
    {
        return [
            'job.application.view.my',
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
            // Layout::block([JobNavItemslayout::class])->vertical(),
            // JobApplicationFiltersLayout::class,
            MyJobApplicationListLayout::class,
        ];
    }

    /**
     * Delete the job
     *
     * @param Request $request
     */
    public function remove(Request $request): void
    {
        $application = JobApplication::findOrFail($request->get('id'));

        $application->delete();

        Toast::info(__('Job Application was removed'));
    }
}
