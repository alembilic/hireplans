<?php

namespace App\Orchid\Screens\Employer;

use App\Orchid\Layouts\Employer\EmployerNavItemsLayout;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\User;
use App\Models\Employer;
use App\Orchid\Layouts\User\UserViewLayout;
use App\Orchid\Layouts\Employer\EmployerViewLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;

class EmployerViewScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var Employer
     */
    public $employer;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Employer $employer): iterable
    {
        $employer->load(['user', 'jobs']); // Eager load the user and jobs relationships

        // Load the related user if the employer exists
        $user = $employer->exists ? $employer->user : new User();

        return [
            'employer' => $employer,
            'user'     => $user,
        ];
    }

    /**
     * Get the permissions required to access this screen.
     *
     * @return iterable|null The permissions required to access this screen.
     */
    public function permission(): ?iterable
    {
        return [
            'platform.systems.users',
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Employer Details';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Edit')
                ->route('platform.employers.edit', $this->employer)
                ->type(Color::PRIMARY)
                ->icon('bs.pencil'),
            Button::make('Back to List')
                ->route('platform.employers.list')
                ->type(Color::SECONDARY)
                ->icon('bs.arrow-left'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::block([EmployerNavItemsLayout::class])->vertical(),

            Layout::block([UserViewLayout::class])->vertical()->title('Personal Details'),

            Layout::block([EmployerViewLayout::class])->vertical()->title('Employer Details'),
        ];
    }
} 