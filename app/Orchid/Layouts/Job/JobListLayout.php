<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Job;
use Illuminate\Support\Facades\Auth;

class JobListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'jobs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        $out = [
            TD::make('job_ref', 'Job Ref'),
            TD::make('createdBy.name', 'Recruiter')
                ->sort()
                ->cantHide()
                ->render(fn (Job $job) => $job->createdBy->name ?? 'Unknown')
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('title', __('Job Title'))
                ->sort()
                ->cantHide()
                ->render(fn (Job $job) => Link::make($job->title)
                    ->route('platform.jobs.view', $job->id)
                    ->class('text-primary')),
                // ->filter(Input::make())

            TD::make('location', 'Location')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('employer_name', __('Employer'))
                ->sort()
                ->cantHide()
                ->render(fn (Job $job) => $job->employer->name),
                // ->filter(Input::make())

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                // ->align(TD::ALIGN_RIGHT)
                // ->defaultHidden()
                ->sort(),
        ];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $out[] = TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Job $job) {
                    $actions = self::getActionsList($job);
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

    public static function getActionsList(Job $job)
    {
        $actions = [];

        // if (Auth::user()->hasAccess('platform.systems.users')) {
            $actions = [
                Link::make(__('Edit'))
                    ->route('platform.jobs.edit', $job->id)
                    ->icon('bs.pencil'),

                Button::make(__('Delete'))
                    ->icon('bs.trash3')
                    ->confirm(__('Once this job is deleted, all of its resources and data will be permanently deleted. Before deleting this job, please download any data or information that you wish to retain.'))
                    ->method('remove', [
                        'id' => $job->id,
                    ]),
            ];
        // }

        return $actions;
    }
}
