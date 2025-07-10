<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;
use Orchid\Screen\Fields\Select;
use App\Helpers\CountryHelper;
use Orchid\Screen\Fields\Picture;

class UserViewLayout extends Rows
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
                ->title(__('Full Name'))
                ->readonly()
                ->horizontal(),

            Input::make('user.email')
                ->type('email')
                ->title(__('Email'))
                ->readonly()
                ->horizontal(),

            Input::make('user.phone')
                ->type('text')
                ->max(255)
                ->title(__('Phone'))
                ->readonly()
                ->horizontal(),

            Input::make('user.address_line_1')
                ->type('text')
                ->max(255)
                ->title(__('Address'))
                ->readonly()
                ->horizontal(),

            Input::make('user.city')
                ->type('text')
                ->max(255)
                ->title(__('City'))
                ->readonly()
                ->horizontal(),

            Input::make('user.postcode')
                ->type('text')
                ->max(255)
                ->title(__('Postcode'))
                ->readonly()
                ->horizontal(),

            Select::make('user.country')
                    ->options(CountryHelper::getCountries())
                    ->title('Country')
                    ->readonly()
                    ->horizontal(),

            Input::make('user.nationality')
                ->type('text')
                ->max(255)
                ->title(__('Nationality'))
                ->readonly()
                ->horizontal(),

            Input::make('user.dob')
                ->type('date')
                ->title(__('Date of Birth'))
                ->readonly()
                ->horizontal(),

            Picture::make('user.avatar')
                ->title('Photo')
                ->horizontal()
                ->targetRelativeUrl(),
        ];
    }
} 