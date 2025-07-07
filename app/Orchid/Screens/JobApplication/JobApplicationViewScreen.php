<?php

namespace App\Orchid\Screens\JobApplication;

use App\Models\Candidate;
use App\Orchid\Screens\Candidate\CandidateViewScreen;
use Orchid\Screen\Screen;
use App\Models\Job;
use App\Models\JobApplication;
use App\Helpers\HelperFunc;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use \Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class JobApplicationViewScreen extends Screen
{
    /**
     * @var JobApplication
     */
    public $jobApplication;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(JobApplication $application): iterable
    {
        $application->load(['job', 'candidate']); // Eager load the employer relationship

        // dd($application);
        $this->jobApplication = $application;

        $user = Auth::user();
        if ($user->id != $this->jobApplication->candidate->user_id
            && !$user->hasAccess('platform.systems.users')) {
            abort(403, 'Unauthorized');
        }

        return [
            'jobApplication' => $application,
            'job' => $application->job ?? null,
            'employer' => $application->job->employer ?? null,
            'candidate' => $application->candidate ?? null,
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
     * Get the permissions required to access this screen.
     *
     * @return iterable|null The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return [];
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
        // dd($this->jobApplication);
        $job = $this->jobApplication->job ?? null;
        $employer = $job->employer ?? null;
        $candidate = $this->jobApplication->candidate ?? null;
        // dd($candidate);

        $candidateLegendItems = [
            Sight::make('user.name', 'Candidate Name')->render(fn (Candidate $candidate) => $candidate->user->name),
            Sight::make('candidate_ref', 'Candidate Reference'),
            Sight::make('user.email', 'Email')->render(fn (Candidate $candidate) => $candidate->user->email),
            // Sight::make('user.email_verified_at', 'Email Verified')->render(fn (Candidate $candidate) => $candidate->user->email_verified_at === null
            //     ? '<i class="text-danger">●</i> False'
            //     : '<i class="text-success">●</i> True'),
            Sight::make('user.phone', 'Phone'),
            // Sight::make('user.address_line_1', 'Address Line 1'),
            Sight::make('user.city', 'City'),
            // Sight::make('user.postcode', 'Postcode'),
            Sight::make('user.country', 'Country'),
            Sight::make('user.nationality', 'Nationality'),
            // Sight::make('user.dob', 'Date of Birth'),
            // Sight::make('user.created_at', 'Created At'),
            // Sight::make('user.updated_at', 'Updated At'),
            Sight::make('gender'),
            Sight::make('languages'),
            Sight::make('skills'),
            Sight::make('current_company', 'Current Company'),
            Sight::make('current_job_title', 'Current Job Title'),
            // Sight::make('', 'CV')->render(fn (Candidate $candidate) => implode('; ', $this->renderAttachmentsLinks($candidate->getCvAttachmentsInfo()))),
            // Sight::make('', 'Other Documents')->render(fn (Candidate $candidate) => implode('; ', $this->renderAttachmentsLinks($candidate->getOtherDocAttachmentsInfo()))),
            // Sight::make('notes'),
            // Sight::make('')
            //     ->render(function () {
            //         return Group::make([
            //             Button::make('Edit')
            //                 ->type(Color::INFO)
            //                 ->icon('bs.pencil')
            //                 ->method('redirectToEditScreen'),
            //             Button::make('Close')
            //                 ->type(Color::DEFAULT)
            //                 ->icon('bs.x-circle')
            //                 ->method('redirectToListScreen'),
            //         ])->autoWidth()->alignCenter();
            //     }),
        ];
        if (Auth::user()->hasAccess('platform.systems.users')) {
            $candidateLegendItems[] = Sight::make('notes');
        }

        $applicationLegendItems = [
            Sight::make('', 'CV')->render(function (jobApplication $application) {
                    if ($application->getCv()) {
                        $cv = $application->getCv();
                        // dd($cv);
                        $url = htmlspecialchars((string) $cv->url);
                        return '<a href="' . $url . '" target="_blank">'.$cv->text.'</a>';
                    }
                    return 'NA';
                }),
            Sight::make('', 'Cover Letter')->render(function (jobApplication $application) {
                    if ($application->getCoverLetter()) {
                        $cl = $application->getCoverLetter();
                        // dd($cv);
                        $url = htmlspecialchars((string) $cl->url);
                        return '<a href="' . $url . '" target="_blank">'.$cl->text.'</a>';
                    }
                    return 'NA';
                }),

            Sight::make('created_at', 'Submitted At')->render(fn(jobApplication $application) => \Carbon\Carbon::parse($application->created_at)->format('d/m/Y H:i:s')),

            Sight::make('status', 'Status')->render(fn(jobApplication $application) => HelperFunc::getApplicationStatus($application)),

        ];

        $jobLegendItems = [
            Sight::make('title', 'Job title'),
            Sight::make('employer.name', 'Employer'),
            Sight::make('location', 'Location'),
            Sight::make('salary', 'Salary'),
            Sight::make('job_type', 'Job Type')->render(fn(Job $job) => HelperFunc::getJobTypes()[$job->job_type] ?? $job->job_type),
            Sight::make('category', 'Category')->render(fn(Job $job) => HelperFunc::getJobCategories()[$job->category] ?? $job->category),
            Sight::make('experience_level', 'Experience Level')->render(fn(Job $job) => HelperFunc::getExperienceLevels()[$job->experience_level] ?? $job->experience_level),
            Sight::make('application_deadline', 'Application Deadline')->render(fn(Job $job) => $job->application_deadline ? \Carbon\Carbon::parse($job->application_deadline)->format('d/m/Y') : ''),
            Sight::make('is_active', 'Is Active')->render(fn(Job $job) => $job->is_active ? 'Yes' : 'No'),
            Sight::make('details', 'Details')->render(fn(Job $job) => nl2br(Str::markdown($job->details))),
        ];

        $out = [
            // Layout::block([JobNavItemslayout::class])->vertical(),
            Layout::legend('candidate', $candidateLegendItems)->title('Candidate Details'),
            Layout::legend('jobApplication', $applicationLegendItems)->title('Application Details'),
            Layout::legend('job', $jobLegendItems)->title('Job Details'),
        ];

        return $out;
    }
}
