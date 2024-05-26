<?php

namespace App\Orchid\Layouts\Employer;

use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Employer;
use Orchid\Screen\Layouts\Persona;

class EmployerListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'employers';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('employer_name', __('Employer'))
                ->sort()
                ->cantHide()
                ->render(fn (Employer $employer) => $employer->employer_name),
                // ->render(fn (Employer $employer) => route('platform.employers.edit', $employer->id) . ' (' . $employer->employer_name . ')'),
                // ->filter(Input::make())

            TD::make('employer_ref', 'Employer Ref')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('country', 'Country')
                ->sort()
                ->cantHide()
                // ->filter(TD::FILTER_TEXT)
                ,

            TD::make('user_name', __('Contact Name'))
                ->sort()
                ->cantHide()
                ->render(fn (Employer $employer) => $employer->user_name . ' (' . $employer->user_email . ')'),
                // ->filter(Input::make())
                // ->render(fn (Candidate $candidate) => new Persona($candidate->user->presenter())),

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                // ->align(TD::ALIGN_RIGHT)
                // ->defaultHidden()
                ->sort(),

        ];
    }
}
