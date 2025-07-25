<?php

namespace App\Orchid\Layouts\Employer;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\SimpleMDE;
use Illuminate\Support\Facades\Auth;
use App\Helpers\CountryHelper;
use App\Enums\EmployerStatus;

class EmployerEditLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('employer.name')
                ->type('text')
                ->max(255)
                ->title(__('Employer Name'))
                ->placeholder(__('Employer Name'))
                ->horizontal(),

            Input::make('employer.address')
                ->type('text')
                ->max(255)
                ->title(__('Address'))
                ->placeholder(__('Address'))
                ->horizontal(),

            Input::make('employer.city')
                ->type('text')
                ->max(255)
                ->title(__('City'))
                ->placeholder(__('City'))
                ->horizontal(),

            Select::make('employer.country')
                    ->options(CountryHelper::getCountries())
                    ->title('Country')
                    ->empty('Select a country')
                    // ->required()
                    ->horizontal(),

            Select::make('employer.status')
                    ->options(collect(EmployerStatus::cases())->mapWithKeys(function($status) {
                        return [$status->value => $status->label()];
                    }))
                    ->title('Status')
                    ->value(1) // Default to In Progress
                    ->horizontal(),

            Input::make('employer.website')
                ->type('url')
                ->max(255)
                ->title(__('Website'))
                ->placeholder(__('Enter your website URL'))
                ->horizontal(),

            Picture::make('employer.logo')
                ->title(__('Employer Logo'))
                ->targetRelativeUrl()
                ->horizontal(),

            // SimpleMDE::make('employer.details')
            //     ->title(__('Details'))
            //     // ->popover(__('Details'))
            //     // ->help('These notes are visible to the admins only. They are not visible to the candidate.')
            //     ->horizontal(),
            TextArea::make('employer.details')
                ->title(__('Details'))
                ->rows(10)
                ->horizontal(),
        ];
    }
}
