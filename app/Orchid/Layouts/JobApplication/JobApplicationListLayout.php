<?php

namespace App\Orchid\Layouts\JobApplication;

use Illuminate\Support\Facades\Auth;
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

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $out[] = TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (JobApplication $jobApplication) {
                    $actions = self::getActionsList($jobApplication);
                    if (!empty($actions)) {
                        return DropDown::make()
                            ->icon('bs.three-dots-vertical')
                            ->list($actions);
                    }
                    return '';
                });
        }

        return $out;
    }

    public static function getActionsList(JobApplication $jobApplication)
    {
        // $actions = [];

        $actions = [
            Link::make(__('View details'))
                ->route('platform.job_application.view', $jobApplication->id)
                ->icon('bs.pencil'),

            // Button::make(__('Delete'))
            //     ->icon('bs.trash3')
            //     ->confirm(__('Once this job is deleted, all of its resources and data will be permanently deleted. Before deleting this job, please download any data or information that you wish to retain.'))
            //     ->method('remove', [
            //         'id' => $job->id,
            //     ]),
        ];

        return $actions;
    }
}
