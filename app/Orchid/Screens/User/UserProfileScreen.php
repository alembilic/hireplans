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
use App\Services\DocumentParsingService;
use Orchid\Attachment\Models\Attachment;
use Illuminate\Support\Facades\Storage;

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

        // Check for parsed CV data in session and merge with existing candidate data
        $candidate = $user->candidate ?? new Candidate();
        $parsedData = session()->get('parsed_cv_data');
        
        if ($parsedData) {
            // Merge parsed data with existing candidate data (parsed data takes priority for empty fields)
            $candidateArray = $candidate->toArray();
            
            // Only populate empty fields with parsed data
            foreach ($parsedData as $key => $value) {
                if (empty($candidateArray[$key]) && !empty($value)) {
                    $candidate->$key = $value;
                }
            }
            
            // Also merge user data if name is empty
            if (empty($user->name) && !empty($parsedData['name'])) {
                $user->name = $parsedData['name'];
            }
            
            // Clear the session data after use
            session()->forget('parsed_cv_data');
        }

        return [
            'user' => $user,
            'candidate' => $candidate,
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
        return 'Update your account details such as name, email address and password. Upload your CV and use the "Parse CV" button to automatically populate your profile fields with extracted data.';
    }

    /**
     * The screen's action buttons.
     *
     * @return Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Parse CV')
                ->icon('bs.file-earmark-text')
                ->method('parseCV')
                ->type(Color::SUCCESS)
                ->canSee($this->hasUploadedCV()),

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
     * @return \Illuminate\Http\RedirectResponse
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

        return redirect()->back();
    }

    /**
     * Check if user has uploaded CV attachments
     *
     * @return bool
     */
    private function hasUploadedCV(): bool
    {
        $user = Auth::user()->load('candidate');
        
        if (!$user->candidate) {
            return false;
        }

        $cvAttachments = $user->candidate->getCvAttachments();
        
        return $cvAttachments && $cvAttachments->count() > 0;
    }

    /**
     * Parse CV and populate form fields with extracted data
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function parseCV(Request $request)
    {
        try {
            $user = Auth::user()->load('candidate');
            
            if (!$user->candidate) {
                Toast::error('No candidate profile found. Please save your profile first.');
                return redirect()->back();
            }

            // Get the most recent CV attachment
            $cvAttachments = $user->candidate->getCvAttachments();
            
            if (!$cvAttachments || $cvAttachments->count() === 0) {
                Toast::error('No CV found to parse. Please upload a CV first.');
                return redirect()->back();
            }

            $latestCV = $cvAttachments->sortByDesc('created_at')->first();
            
            // Get file extension and construct proper file name
            $fileExtension = pathinfo($latestCV->original_name, PATHINFO_EXTENSION);
            $fileName = $latestCV->name . '.' . $fileExtension;
            $filePath = \Storage::disk($latestCV->disk)->path($latestCV->path . $fileName);
            
            // Check if file exists
            if (!\Storage::disk($latestCV->disk)->exists($latestCV->path . $fileName)) {
                Toast::error('CV file not found on disk. Please re-upload your CV.');
                return redirect()->back();
            }
            
            // Parse the document
            $parseResult = DocumentParsingService::parseCandidateData($filePath, $fileExtension);
            
            if (isset($parseResult['error'])) {
                Toast::error('Error parsing CV: ' . $parseResult['error']);
                return redirect()->back();
            }
            
            if (!isset($parseResult['candidates']) || empty($parseResult['candidates'])) {
                Toast::error('No data could be extracted from the CV.');
                return redirect()->back();
            }

            // Get the parsed candidate data
            $parsedData = $parseResult['candidates'][0];
            
            // Store parsed data in session for form population
            session()->put('parsed_cv_data', $parsedData);
            
            Toast::success('CV parsed successfully! Form fields have been populated with extracted data.');
            
        } catch (\Exception $e) {
            Toast::error('An error occurred while parsing the CV: ' . $e->getMessage());
        }
        
        return redirect()->back();
    }

}
