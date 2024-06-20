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
use App\Orchid\Layouts\Candidate\CandidateAttachmentLayout;
use Orchid\Screen\Fields\Group;
use Illuminate\Support\Facades\Auth;
use App\Models\Candidate;
use App\Helpers\HelperFunc;

class UserProfileScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     *
     * @return array
     */
    public function query(Request $request): iterable
    {
        $user = $request->user()->load('candidate');

        $cvAttachments = null;
        $cvAttachmentsInfo = null;
        $otherDocumentsAttachments = null;
        $otherDocumentsAttachmentsInfo = null;
        $cv = null;
        $otherDocuments = null;

        if ($user->candidate) {
            $user->candidate->load('attachment');

            $cvAttachments = $user->candidate->getCvAttachments();
            $cvAttachmentsInfo = $cvAttachments ? $user->candidate->getCvAttachmentsInfo() : null;

            $otherDocumentsAttachments = $user->candidate->getOtherDocAttachments();
            $otherDocumentsAttachmentsInfo = $otherDocumentsAttachments ? $user->candidate->getOtherDocAttachmentsInfo() : null;

            $cv = $cvAttachments->pluck('id')->toArray();
            $otherDocuments = $otherDocumentsAttachments->pluck('id')->toArray();
        }


        return [
            'user' => $request->user(),
            'candidate' => $user->candidate ?? new Candidate(),
            'cv' => $cv,
            'other_documents' => $otherDocuments,
            'cv_links' => $cvAttachmentsInfo ? HelperFunc::renderAttachmentsLinks($cvAttachmentsInfo) : [],
            'other_documents_links' => $otherDocumentsAttachmentsInfo ? HelperFunc::renderAttachmentsLinks($otherDocumentsAttachmentsInfo) : [],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'My Account';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Update your account details such as name, email address and password';
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
            Layout::block([UserEditLayout::class])->vertical()->title('Personal Details'),

            Layout::block([CandidateEditLayout::class])->vertical()->title('Other Information'),

            Layout::block([CandidateAttachmentLayout::class])->vertical()->title('Attachments'),

            Layout::rows([
                Group::make([
                    Button::make('Save')
                        ->method('saveProfile')
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

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function saveProfile(Request $request)
    {
        // dd($request->all());
        $user = Auth::user()->load('candidate');
        $candidate = $user->candidate ?? new Candidate();

        $request->validate([
            'user.email' => [
                'required',
                Rule::unique(User::class, 'email')->ignore($user),
            ],
            'user.name' => 'required',
        ]);

        // $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
        //     $builder->getModel()->password = Hash::make($request->input('user.password'));
        // });

        $user
            ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
            // ->forceFill(['permissions' => $permissions])
            ->save();

        // Find the roles
        // $role1 = Role::where('slug', 'authenticated_user')->first();
        // $role2 = Role::where('slug', 'candidate')->first();
        // $user->replaceRoles([$role1->id, $role2->id]);

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

        Toast::info(__('Profile saved'));

        return redirect()->route('platform.main');
    }

}
