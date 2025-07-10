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
// use Orchid\Screen\Actions\Link;
use App\Helpers\HelperFunc;
// use Orchid\Attachment\File;
use App\Orchid\Layouts\Candidate\CandidateAttachmentLayout;
use Orchid\Screen\Actions\Link;
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
        $candidate->load('attachment');

        $cvAttachments = $candidate->getCvAttachments();
        $cvAttachmentsInfo = $cvAttachments ? $candidate->getCvAttachmentsInfo() : null;

        $otherDocumentsAttachments = $candidate->getOtherDocAttachments();
        $otherDocumentsAttachmentsInfo = $otherDocumentsAttachments ? $candidate->getOtherDocAttachmentsInfo() : null;

        // dd($candidate);
        // dd($candidate->renderAttachmentsLinks());

        // Load the related user if the candidate exists
        $user = $candidate->exists ? $candidate->user : new User();

        return [
            'candidate'  => $candidate,
            'user'       => $user,
            'cv' => $cvAttachments->pluck('id')->toArray(),
            'other_documents' => $otherDocumentsAttachments->pluck('id')->toArray(),
            'cv_links' => $cvAttachmentsInfo ? HelperFunc::renderAttachmentsLinks($cvAttachmentsInfo) : [],
            'other_documents_links' => $otherDocumentsAttachmentsInfo ? HelperFunc::renderAttachmentsLinks($otherDocumentsAttachmentsInfo) : [],
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
        $actions[] = Link::make('Back to List')
            ->route('platform.candidates.list')
            ->icon('bs.arrow-left');
        return $actions;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            // Layout::view('block-title',['title' => 'Personal Details']),
            // UserEditLayout::class,

            Layout::block([UserEditLayout::class, UserPasswordLayout::class])->vertical()->title('Personal Details'),

            Layout::block([CandidateEditLayout::class])->vertical()->title('Other Information'),

            Layout::block([CandidateAttachmentLayout::class])->vertical()->title('Attachments'),

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
        // dd($request->all());
        $user = $candidate->user ?? new User();

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
        $role2 = Role::where('slug', 'candidate')->first();
        $user->replaceRoles([$role1->id, $role2->id]);

        $candidateData = $request->collect('candidate')->except([])->toArray();
        $candidateData['user_id'] = $user->id;
        $candidateData['candidate_ref'] = HelperFunc::generateReferenceNumber('candidate');

        $candidate->fill($candidateData)->save();

        // Sync attachments for "candidate.cv"
        if ($request->has('candidate.cv')) {
            $cvAttachments = $request->input('candidate.cv', []);
            // $this->candidate->attachment()->syncWithoutDetaching($cvAttachments);
            $currentCvAttachments = $candidate->attachment()->wherePivot('field_name', 'cv')->pluck('attachments.id')->toArray();

            $newCvAttachments = array_diff($cvAttachments, $currentCvAttachments);
            $candidate->attachment()->attach($newCvAttachments, ['field_name' => 'cv']);
        }

        // Sync attachments for "candidate.other-documents"
        if ($request->has('candidate.other-documents')) {
            $otherDocumentsAttachments = $request->input('candidate.other-documents', []);
            $currentOtherDocumentsAttachments = $candidate->attachment()
                ->wherePivot('field_name', 'other-documents')
                ->pluck('attachments.id')
                ->toArray();

            $newOtherDocumentsAttachments = array_diff($otherDocumentsAttachments, $currentOtherDocumentsAttachments);
            $candidate->attachment()->attach($newOtherDocumentsAttachments, ['field_name' => 'other-documents']);
        }
        // $candidate->attachment()->syncWithoutDetaching(
        //     $request->input('candidate.other-documents', [])
        // );

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
