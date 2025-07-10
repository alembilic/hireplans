<?php

namespace App\Orchid\Screens\Employer;

use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use App\Orchid\Layouts\Employer\EmployerNavItemsLayout;
use Orchid\Screen\Screen;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Layout;
use App\Models\User;
use App\Models\Employer;
use App\Orchid\Layouts\User\UserEditLayout;
use App\Orchid\Layouts\User\UserPasswordLayout;
use App\Orchid\Layouts\Employer\EmployerEditLayout;
use App\Helpers\HelperFunc;
use Orchid\Support\Facades\Toast;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Orchid\Platform\Models\Role;

class EmployerEditScreen extends Screen
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
        $employer->load(['user']); // Eager load the user relationship

        // Set default status for new employers
        if (!$employer->exists) {
            $employer->status = 1; // In Progress
        }

        // Load the related user if the employer exists
        $user = $employer->exists ? $employer->user : new User();

        return [
            'employer' => $employer,
            'user'       => $user,
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
        return $this->employer->exists ? 'Edit employer' : 'Create employer';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
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

            Layout::block([UserEditLayout::class, UserPasswordLayout::class])->vertical()->title('Personal Details'),

            Layout::block([EmployerEditLayout::class])->vertical()->title('Employer Details'),

            Layout::rows([
                Group::make([
                    Button::make('Save')
                        ->method('saveEmployer')
                        ->type(Color::PRIMARY)
                        ->icon('bs.check-circle'),
                    Button::make('Cancel')
                        ->method('cancel')
                        ->type(Color::SECONDARY)
                        ->icon('bs.x-circle')
                        ->rawClick(),
                    // Link::make('Cancel')
                    //     ->icon('close')
                    //     ->route('platform.candidates.list'),
                ])->autoWidth()->alignCenter(),
            ]),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveEmployer(Employer $employer, Request $request)
    {
        $user = $employer->user ?? new User();

        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.name' => 'required',
            // 'user.avatar' => 'mimes:jpeg,jpg,png,bmp,gif,svg,webp|max:1024',
        ]);

        $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
            $builder->getModel()->password = Hash::make($request->input('user.password'));
        });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            // ->forceFill(['permissions' => $permissions])
            ->save();

        // Find the roles
        $role1 = Role::where('slug', 'authenticated_user')->first();
        $role2 = Role::where('slug', 'employer')->first();
        $user->replaceRoles([$role1->id, $role2->id]);

        $employerData = $request->collect('employer')->except([])->toArray();
        $employerData['user_id'] = $user->id;
        $employerData['employer_ref'] = HelperFunc::generateReferenceNumber('employer');
        
        // Set default status to In Progress (1) if not provided
        if (!isset($employerData['status'])) {
            $employerData['status'] = 1;
        }

        $employer->fill($employerData)->save();

        Toast::info(__('Employer saved'));

        return redirect()->route('platform.employers.view', $employer->id);
    }

    /**
     * Cancel the edit operation and return to the list screen.
     *
     * @return void
     */
    public function cancel()
    {
        return redirect()->route('platform.employers.list');
    }
}
