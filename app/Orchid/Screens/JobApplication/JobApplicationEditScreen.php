<?php

namespace App\Orchid\Screens\JobApplication;

use App\Helpers\HelperFunc;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\JobApplication;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\JobApplication\JobApplicationEditLayout;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Support\Arr;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Support\Facades\Toast;

class JobApplicationEditScreen extends Screen
{

    /**
     * @var JobApplication
     */
    public $jobApplication;

    /**
     * @var Job
     */
    public $job;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Job $job, JobApplication $jobApplication): iterable
    {
        $user = Auth::user();
        $user->load(['candidate']);
        // if (!$user->candidate) {
        //     Toast::error(__('You need to create a candidate profile before you can apply for a job.'));
        //     return redirect()->route('platform.candidate.edit');
        // }

        $defaultCvPath = null;
        $defaultCvId = null;

        if ($user->candidate) {
            $user->candidate->load('attachment');
            $defaultCv = $user->candidate->getCvAttachments()->last() ?? null;
            $defaultCvPath = $defaultCv ? $defaultCv->getRelativeUrlAttribute() : null;
            $defaultCvId = $defaultCv->id ?? null;
        }


        // dd($defaultCv);
        return [
            'cv_path' => $defaultCvPath,
            'cv_id' => $defaultCvId,
            'job' => $job,
            // 'cover_letter_path' => 'path/to/cover/letter',
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Job Application';
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
            // Layout::block([JobApplicationEditLayout::class])->vertical()->title($this->job->title),
            Layout::block([JobApplicationEditLayout::class])->vertical(),

            Layout::rows([
                Group::make([
                    Button::make('Submit application')
                        ->method('saveApplication')
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
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveApplication(JobApplication $application, Job $job, Request $request)
    {
        // dd($request->all());

        $request->validate([
            'job_application.cv' => [
                'required',
            ],
        ]);

        // $application->fill($request->get('job_application')->except(['cover_letter'])->toArray())
        //             ->save();

        $applicationData = $request->collect('job_application')->except(['cover_letter'])->toArray();
        $applicationData['application_ref'] = HelperFunc::generateReferenceNumber('application');;
        $applicationData['job_id'] = $job->id;
        $applicationData['candidate_id'] = Auth::user()->candidate->id ?? null;
        $applicationData['cv'] = $request->input('job_application.cv', 0);
        $applicationData['cover_letter'] = Arr::get($request->input('job_application.cover_letter'), 0, null);
        $application->fill($applicationData)->save();

        // dd($applicationData);

        Toast::info(__('Your application was submitted successfully.'));

        return redirect()->route('jobs.details', $job->id);
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
