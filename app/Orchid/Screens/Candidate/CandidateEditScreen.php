<?php

namespace App\Orchid\Screens\Candidate;

use App\Orchid\Layouts\User\UserPasswordLayout;
use Illuminate\Http\Request;
use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use App\Orchid\Layouts\User\UserEditLayout;
use Orchid\Screen\Screen;
// use Orchid\Platform\Models\User;
use App\Models\User;
use App\Models\Candidate;
use Orchid\Platform\Models\Role;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Candidate\CandidateEditLayout;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Illuminate\Validation\Rule;
use Orchid\Support\Facades\Toast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Orchid\Screen\Actions\Link;
use App\Helpers\HelperFunc;

// use Illuminate\Support\Facades\Log;

class CandidateEditScreen extends Screen
{
    /**
     * @var User
     */
    public $user;

    /**
     * @var Candidate
     */
    public $candidate;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Candidate $candidate): iterable
    {
        $candidate->load(['user']); // Eager load the user relationship

        // Load the related user if the candidate exists
        $user = $candidate->exists ? $candidate->user : new User();

        return [
            'candidate'  => $candidate,
            'user'       => $user,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->candidate->exists ? 'Edit candidate' : 'Create candidate';
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

            Layout::block([CandidateNavItemsLayout::class])->vertical(),

            // Layout::view('block-title',['title' => 'Personal Details']),
            // UserEditLayout::class,

            Layout::block([UserEditLayout::class, UserPasswordLayout::class])->vertical()->title('Personal Details'),

            Layout::block([CandidateEditLayout::class])->vertical()->title('Other Information'),

            Layout::rows([
                Group::make([
                    Button::make('Save')
                        ->method('saveCandidate')
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
    public function saveCandidate(Candidate $candidate, Request $request)
    {
        $user = $candidate->user ?? new User();

        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.name' => 'required',
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
        $role2 = Role::where('slug', 'candidate')->first();
        $user->replaceRoles([$role1->id, $role2->id]);

        $candidateData = $request->collect('candidate')->except([])->toArray();
        $candidateData['user_id'] = $user->id;
        $candidateData['candidate_ref'] = HelperFunc::generateReferenceNumber('candidate');

        $candidate->fill($candidateData)->save();

        Toast::info(__('Candidate saved'));

        // return redirect()->route('platform.candidates.list');
        return redirect()->route('platform.candidates.view', $candidate->id);
    }

    /**
     * Cancel the edit operation and return to the list screen.
     *
     * @return void
     */
    public function cancel()
    {
        return redirect()->route('platform.candidates.list');
    }

    // /**
    //  * Generate a random reference number.
    //  *
    //  * @return string
    //  */
    // public static function generateReferenceNumber()
    // {
    //     // $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     $letters = 'ABCDEGKRTVWYZ';
    //     $numbers = '123456789';
    //     $characters = $letters . $numbers;

    //     do {
    //         // Ensure at least one letter and one number
    //         $referenceNumber = 'C-';
    //         // $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
    //         for ($i = 0; $i < 3; $i++) {
    //             $referenceNumber .= $numbers[rand(0, strlen($numbers) - 1)];
    //         }
    //         $referenceNumber .= $letters[rand(0, strlen($letters) - 1)];

    //         // Fill the remaining 4 characters with random letters or numbers
    //         for ($i = 0; $i < 4; $i++) {
    //             $referenceNumber .= $characters[rand(0, strlen($characters) - 1)];
    //         }

    //         // Shuffle the resulting string (excluding the 'C-' prefix)
    //         $referenceNumber = 'C-' . str_shuffle(substr($referenceNumber, 2));
    //     } while (Candidate::where('candidate_ref', $referenceNumber)->exists());

    //     return $referenceNumber;
    // }

    /**
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Candidate $candidate)
    {
        $candidate->delete();

        Toast::info(__('Candidate was removed'));

        return redirect()->route('platform.candidates.list');
    }

}
