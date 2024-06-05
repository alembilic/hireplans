<?php

namespace App\Orchid\Layouts\Job;

use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Job;

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
        return [
            TD::make('job_ref', 'Job Ref')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('title', __('Job Title'))
                ->sort()
                ->cantHide(),
                // ->render(fn (Employer $employer) => Link::make($employer->employer_name)
                //     ->route('platform.employers.edit', $employer->id)
                //     ->class('text-primary')),
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

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                // ->render(fn (Employer $employer) => DropDown::make()
                //     ->icon('bs.three-dots-vertical')
                //     ->list([
                //         Link::make(__('Edit'))
                //             ->route('platform.employers.edit', $employer->id)
                //             ->icon('bs.pencil'),

                //         Button::make(__('Delete'))
                //             ->icon('bs.trash3')
                //             ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting this account, please download any data or information that you wish to retain.'))
                //             ->method('remove', [
                //                 'id' => $employer->id,
                //             ]),
                //     ])),
        ];
    }
}
