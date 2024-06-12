<?php

namespace App\Orchid\Layouts\Employer;

use App\Orchid\Presenters\EmployerPresenter;
use Orchid\Screen\Components\Cells\DateTimeSplit;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
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
                // ->filter(Input::make())
                // ->render(fn (Employer $employer) => Link::make($employer->employer_name)
                //     ->route('platform.employers.edit', $employer->id)
                //     ->class('text-primary')),
                // ->render(fn (Employer $employer) => new EmployerPresenter($employer->presenter())),
                ->render(function (Employer $employer) {
                        $presenter = new EmployerPresenter($employer);
                        return $presenter->nameWithLogo(); // Use the new method
                    }), // Ensure that the output is treated as raw HTML

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
                ->render(fn (Employer $employer) => Link::make($employer->user_name)
                    ->route('platform.employers.edit', $employer->id)
                    ->class('text-primary')
                    . ' (' . $employer->user_email . ')'),
                // ->filter(Input::make())

            TD::make('created_at', __('Created'))
                ->usingComponent(DateTimeSplit::class)
                // ->align(TD::ALIGN_RIGHT)
                // ->defaultHidden()
                ->sort(),

            TD::make(__('Actions'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(fn (Employer $employer) => DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make(__('Edit'))
                            ->route('platform.employers.edit', $employer->id)
                            ->icon('bs.pencil'),

                        Button::make(__('Delete'))
                            ->icon('bs.trash3')
                            ->confirm(__('Once the account is deleted, all of its resources and data will be permanently deleted. Before deleting this account, please download any data or information that you wish to retain.'))
                            ->method('remove', [
                                'id' => $employer->id,
                            ]),
                    ])),

        ];
    }
}
