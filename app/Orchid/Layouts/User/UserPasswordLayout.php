<?php

declare(strict_types=1);

namespace App\Orchid\Layouts\User;

use Orchid\Platform\Models\User;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Password;
use Orchid\Screen\Fields\CheckBox;
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

        // For existing users, show password field
        if ($user->exists) {
            $placeholder = __('Leave empty to keep current password');
            
            return [
                Password::make('user.password')
                    ->placeholder($placeholder)
                    ->title(__('Password'))
                    ->horizontal()
                    ->autocomplete('new-password')
            ];
        }
        
        // For new users, show email setup option
        return [
            CheckBox::make('send_password_setup_email')
                ->title(__('Password Setup'))
                ->placeholder(__('Send password setup email to user'))
                ->help(__('When checked, the user will receive an email with a secure link to set up their own password. Recommended for security.'))
                ->horizontal()
                ->value(true) // Default to checked
                ->sendTrueOrFalse(),
        ];
    }
}
