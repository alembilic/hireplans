<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Layouts\Rows;

class UserPasswordLayout extends Rows
{
    /**
     * The screen's layout elements.
     *
     * @return Field[]
     */
    public function fields(): array
    {
        /** @var User $user */
        $user = $this->query->get('user');

        $placeholder = $user->exists
            ? __('Leave empty to keep current password')
            : __('Enter the password to be set');

        return [
            // Trying to prevent pre-fill by browser!
            // Password::make('dummy_password')
            //     ->type('password')
            //     ->horizontal()
            //     ->hidden()
            //     ->autocomplete('off'),

            Password::make('user.password')
                ->placeholder($placeholder)
                ->title(__('Password'))
                ->horizontal()
                ->autocomplete('new-password')
                // ->addAttributes([
                //     'onfocus' => "this.removeAttribute('readonly');",
                //     'readonly' => 'readonly',
                // ]),
        ];
    }
}
