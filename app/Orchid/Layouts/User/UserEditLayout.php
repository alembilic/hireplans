<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use App\Helpers\CountryHelper;
use Orchid\Screen\Fields\Picture;

class UserEditLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Full Name'))
                ->placeholder(__('Full Name'))
                ->horizontal(),

            // Trying to prevent pre-fill by browser!
            // Input::make('dummy_email')
            //     ->type('text')
            //     ->hidden()
            //     ->autocomplete('off'),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email'))
                ->horizontal()
                ->autocomplete('off'),

            Input::make('user.phone')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Phone'))
                ->placeholder(__('Phone'))
                ->horizontal(),

            Input::make('user.address_line_1')
                ->type('text')
                ->max(255)
                ->title(__('Address'))
                ->placeholder(__('Hourse number and street'))
                ->horizontal(),

            Input::make('user.city')
                ->type('text')
                ->max(255)
                ->title(__('City'))
                ->placeholder(__('City'))
                ->horizontal(),

            Input::make('user.postcode')
                ->type('text')
                ->max(255)
                ->title(__('Postcode'))
                ->placeholder(__('Postcode'))
                ->horizontal(),

            // Select::make('user.country_code')
                // ->fromModel(CountryHelper::getCountries(), 'value')
                // ->title('Country')
                // ->empty('Select a country')
                // ->required(),
            Select::make('user.country')
                    ->options(CountryHelper::getCountries())
                    ->title('Country')
                    ->empty('Select a country')
                    ->horizontal(),

            Input::make('user.nationality')
                ->type('text')
                ->max(255)
                ->title(__('Nationality'))
                ->placeholder(__('Nationality'))
                ->horizontal(),

            Input::make('user.dob')
                ->type('date')
                // ->required()
                ->title(__('Date of Birth'))
                ->placeholder(__('Date of Birth'))
                ->horizontal(),

            Picture::make('user.avatar')
                ->title('Photo')
                ->help('The photo needs to be a clear professional photo, without filters, sunglasses or facemarks and dark backgrounds. Niqab and religious wear are ok.')
                ->horizontal()
                ->targetRelativeUrl(),
        ];
    }
}
