<?php

declare(strict_types=1);

namespace App\Orchid\Screens\User;

use App\Orchid\Layouts\User\ProfilePasswordLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Orchid\Access\Impersonation;
use Orchid\Platform\Models\User;
use Orchid\Screen\Action;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Color;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use App\Orchid\Layouts\Candidate\CandidateEditLayout;
use Orchid\Screen\Fields\Group;

class UserPasswordUpdateScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        return [
            'user' => $request->user(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Password Update';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Update your password.';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Back to my account')
                ->novalidate()
                ->canSee(Impersonation::isSwitch())
                ->icon('bs.people')
                ->route('platform.switch.logout'),

            Button::make('Sign out')
                ->novalidate()
                ->icon('bs.box-arrow-left')
                ->route('platform.logout'),
        ];
    }

    /**
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([ProfilePasswordLayout::class])->vertical()->title(__('Update Password')),

            Layout::rows([
                Group::make([
                    Button::make(__('Update Password'))
                        ->method('changePassword')
                        ->type(Color::PRIMARY)
                        ->icon('bs.check-circle'),
                    // Button::make('Cancel')
                    //     ->method('cancel')
                    //     ->type(Color::SECONDARY)
                    //     ->icon('bs.x-circle')
                    //     ->rawClick(),
                    // Link::make('Cancel')
                    //     ->icon('close')
                    //     ->route('platform.candidates.list'),
                ])->autoWidth()->alignCenter(),
            ]),
        ];
    }

    // public function save(Request $request): void
    // {
    //     $request->validate([
    //         'user.name'  => 'required|string',
    //         'user.email' => [
    //             'required',
    //             Rule::unique(User::class, 'email')->ignore($request->user()),
    //         ],
    //     ]);

    //     $request->user()
    //         ->fill($request->get('user'))
    //         ->save();

    //     Toast::info(__('Profile updated.'));
    // }

    public function changePassword(Request $request): void
    {
        $guard = config('platform.guard', 'web');
        $request->validate([
            'old_password' => 'required|current_password:'.$guard,
            'password'     => 'required|confirmed|different:old_password',
        ]);

        tap($request->user(), function ($user) use ($request) {
            $user->password = Hash::make($request->get('password'));
        })->save();

        Toast::info(__('Password changed.'));
    }
}
