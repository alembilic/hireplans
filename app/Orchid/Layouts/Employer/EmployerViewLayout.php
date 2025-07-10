<?php

namespace App\Orchid\Layouts\Employer;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Picture;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use App\Helpers\CountryHelper;
use App\Enums\EmployerStatus;

class EmployerViewLayout extends Rows
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
                ->readonly()
                ->horizontal(),

            Input::make('employer.address')
                ->type('text')
                ->max(255)
                ->title(__('Address'))
                ->readonly()
                ->horizontal(),

            Input::make('employer.city')
                ->type('text')
                ->max(255)
                ->title(__('City'))
                ->readonly()
                ->horizontal(),

            Select::make('employer.country')
                    ->options(CountryHelper::getCountries())
                    ->title('Country')
                    ->readonly()
                    ->horizontal(),

            Select::make('employer.status')
                    ->options(collect(EmployerStatus::cases())->mapWithKeys(function($status) {
                        return [$status->value => $status->label()];
                    }))
                    ->title('Status')
                    ->readonly()
                    ->horizontal(),

            Input::make('employer.website')
                ->type('url')
                ->max(255)
                ->title(__('Website'))
                ->readonly()
                ->horizontal(),

            Picture::make('employer.logo')
                ->title(__('Employer Logo'))
                ->targetRelativeUrl()
                ->horizontal(),

            TextArea::make('employer.details')
                ->title(__('Details'))
                ->rows(10)
                ->readonly()
                ->horizontal(),
        ];
    }
} 