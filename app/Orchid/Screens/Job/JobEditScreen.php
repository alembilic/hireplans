<?php

namespace App\Orchid\Screens\Job;

use App\Helpers\HelperFunc;
use App\Orchid\Layouts\Job\JobNavItemslayout;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Employer;
use App\Orchid\Layouts\Job\JobEditLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Support\Facades\Toast;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Actions\Link;

class JobEditScreen extends Screen
{
    /**
     * @var Employer
     */
    public $employer;

    /**
     * @var Job
     */
    public $job;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Job $job): iterable
    {
        $job->load(['employer', 'createdBy']); // Eager load the employer and createdBy relationships

        return [
            'job' => $job,
            'employer' => $job->employer,
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

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        // return 'JobEditScreen';
        return $this->job->exists ? 'Edit job' : 'Create job';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $actions = [];
        if ($this->job && $this->job->id) {
            $actions[] = Link::make('Pipeline')
                ->icon('bs.funnel')
                ->route('platform.jobs.pipeline', ['selectedJobId' => $this->job->id]);
        }

            $actions[] = Link::make('Back to List')
                        ->route('platform.jobs.list')
                        ->icon('bs.arrow-left');
        

        return $actions;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        $out=  [
            Layout::block([JobEditLayout::class])->vertical(),

            Layout::rows([
                Group::make([
                    Button::make('Save')
                        ->method('saveJob')
                        ->type(Color::PRIMARY)
                        ->icon('bs.check-circle'),
                    Button::make('Cancel')
                        ->method('cancel')
                        ->type(Color::SECONDARY)
                        ->icon('bs.x-circle')
                        ->rawClick(),
                ])->autoWidth()->alignCenter(),
            ]),
        ];

        return $out;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveJob(Job $job, Request $request)
    {
        // $employer = $job->employer;
        // dd($request->all());

        $request->validate([
            'job.employer_id' => 'required|exists:employers,id', // Check if the employer exists
            'job.title' => 'required|string|max:255',
            'job.created_by' => 'nullable|exists:users,id', // Validate recruiter exists
        ]);

        $jobData = $request->input('job');
        $jobData['job_ref'] = HelperFunc::generateReferenceNumber('job');
        $jobData['slug'] = HelperFunc::generateUniqueJobSlug($jobData['title']);
        // $jobData['is_active'] = (isset($jobData['is_active']) && $jobData['is_active'] === 'on') ? 1 : 0;
        $jobData['is_active'] = (isset($jobData['is_active']) && $jobData['is_active'] == 1) ? 1 : 0;

        // Set recruiter field for new jobs if not provided
        if (!$job->exists && !isset($jobData['created_by'])) {
            $jobData['created_by'] = Auth::id();
        }

        // ToDo: should not allow empty details. But there is a bug when editing the job details.
        if (!$jobData['details']) {
            $jobData['details'] = '';
        }

        $job->fill($jobData)->save();

        Toast::info(__('Job saved'));

        return redirect()->route('platform.jobs.view', $job->id);
    }

    /**
     * Cancel the edit operation and return to the list screen.
     *
     * @return void
     */
    public function cancel()
    {
        return redirect()->route('platform.jobs.list');
    }
}
