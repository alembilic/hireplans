<?php

namespace App\Orchid\Layouts\JobApplication;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use App\Models\JobApplication;

class JobApplicationListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'jobApplications';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $out = [
            TD::make('application_ref', 'Application Ref')
                ->sort()
                ->cantHide(),

            TD::make('job_title', 'Job Title')
                ->sort()
                ->cantHide()
                ->render(function (JobApplication $jobApplication) {
                    return Link::make($jobApplication->job_title)
                        ->route('platform.jobs.view', $jobApplication->job->id)
                        ->class('text-primary');
                }),

            TD::make('employer_name', 'Employer')
                ->sort()
                ->cantHide()
                ->render(fn (JobApplication $jobApplication) => Link::make($jobApplication->employer_name)
                    ->route('platform.employers.edit', $jobApplication->job->employer->id)
                    ->class('text-primary')
                ),

            TD::make('candidate_name', 'Candidate')
                ->sort()
                ->cantHide()
                ->render(fn (JobApplication $jobApplication) => Link::make($jobApplication->candidate_name)
                    ->route('platform.candidates.view', $jobApplication->candidate->id)
                    ->class('text-primary')
                ),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                // ->align(TD::ALIGN_RIGHT)
                // ->defaultHidden()
                ->sort(),

            TD::make('updated_at', __('Last edit'))
                ->usingComponent(DateTimeSplit::class)
                // ->align(TD::ALIGN_RIGHT)
                ->defaultHidden()
                ->sort(),
        ];

        return $out;
    }
}
