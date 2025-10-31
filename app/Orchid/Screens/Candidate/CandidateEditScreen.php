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
use App\Orchid\Layouts\Candidate\CandidateDocumentImportLayout;
use App\Services\ActivityService;
use App\Services\DocumentParsingService;
use Orchid\Screen\Actions\Link;
use Orchid\Attachment\Models\Attachment;
use App\Mail\PasswordSetupMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        // Include parsed document as CV for new candidates
        $cvAttachmentIds = $cvAttachments->pluck('id')->toArray();
        $cvLinksArray = $cvAttachmentsInfo ? HelperFunc::renderAttachmentsLinks($cvAttachmentsInfo) : [];
        
        if (!$candidate->exists && session()->has('parsed_document_attachment_id')) {
            $parsedAttachmentId = session('parsed_document_attachment_id');
            if (!in_array($parsedAttachmentId, $cvAttachmentIds)) {
                $cvAttachmentIds[] = $parsedAttachmentId;
                
                // Also add to cv_links for display in "Existing CVs" section
                $parsedAttachment = \Orchid\Attachment\Models\Attachment::find($parsedAttachmentId);
                if ($parsedAttachment) {
                    $parsedAttachmentInfo = HelperFunc::getAttachmentInfo($parsedAttachment);
                    $parsedCvLinks = HelperFunc::renderAttachmentsLinks([$parsedAttachmentInfo]);
                    $cvLinksArray = array_merge($cvLinksArray, $parsedCvLinks);
                }
            }
        }

        // dd($candidate);
        // dd($candidate->renderAttachmentsLinks());

        // Load the related user if the candidate exists
        $user = $candidate->exists ? $candidate->user : new User();

        // Check if we have document parsed data to populate form fields
        $documentParsedData = session('document_parsed_data', []);
        
        // If we have document data, populate the candidate and user with it
        if (!empty($documentParsedData) && !$candidate->exists) {
            // Populate user fields
            if (isset($documentParsedData['user'])) {
                foreach ($documentParsedData['user'] as $field => $value) {
                    if (!empty($value)) {
                        $user->$field = $value;
                    }
                }
            }
            
            // Populate candidate fields
            if (isset($documentParsedData['candidate'])) {
                foreach ($documentParsedData['candidate'] as $field => $value) {
                    if (!empty($value)) {
                        $candidate->$field = $value;
                    }
                }
            }
            
            // Clear the session data after using it
            session()->forget('document_parsed_data');
        }
        
        // Clear parsed document attachment ID after using it for CV
        if (!$candidate->exists && session()->has('parsed_document_attachment_id')) {
            session()->forget('parsed_document_attachment_id');
        }

        return [
            'candidate'  => $candidate,
            'user'       => $user,
            'cv' => $cvAttachmentIds,
            'other_documents' => $otherDocumentsAttachments->pluck('id')->toArray(),
            'cv_links' => $cvLinksArray,
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
        $actions = [];
        
        if ($this->candidate->exists) {
            $actions[] = Link::make('View Profile')
                ->route('platform.candidates.view', $this->candidate->id)
                ->icon('bs.eye');
        } else {
            // Add Parse Document button for new candidates
            $actions[] = Button::make('Parse Document')
                ->method('parseDocumentFile')
                ->type(Color::INFO)
                ->icon('bs.file-earmark-text')
                ->novalidate();
        }
        
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
            // Document Import section - only show for new candidates
            ...(!$this->candidate->exists ? [
                Layout::block([CandidateDocumentImportLayout::class])->vertical()->title('Quick Import from Document'),
            ] : []),

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
     * Parse document file and populate form fields
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function parseDocumentFile(Request $request)
    {
        try {
            $documentImportData = $request->input('document_import');
            $documentAttachmentId = null;
            
            if (is_array($documentImportData) && !empty($documentImportData)) {
                $documentAttachmentId = $documentImportData[0];
            }
            
            if (!$documentAttachmentId) {
                Toast::error('Please upload a document file first');
                return back();
            }

            $attachment = Attachment::find($documentAttachmentId);
            if (!$attachment) {
                Toast::error('Document file not found. Attachment ID: ' . $documentAttachmentId);
                return back();
            }

            // Get file extension and construct proper file name
            $fileExtension = pathinfo($attachment->original_name, PATHINFO_EXTENSION);
            $fileName = $attachment->name . '.' . $fileExtension;
            $filePath = \Storage::disk($attachment->disk)->path($attachment->path . $fileName);
            
            // Check if file exists with extension
            if (!\Storage::disk($attachment->disk)->exists($attachment->path . $fileName)) {
                Toast::error('Document file not found on disk');
                return back();
            }
            
            if (!in_array(strtolower($fileExtension), ['pdf', 'docx', 'doc'])) {
                // Try getting extension from original_name if available
                $originalExtension = $attachment->original_name ? pathinfo($attachment->original_name, PATHINFO_EXTENSION) : '';
                if (!empty($originalExtension) && in_array(strtolower($originalExtension), ['pdf', 'docx', 'doc'])) {
                    $fileExtension = $originalExtension;
                } else {
                    Toast::error("Unsupported file format '{$fileExtension}' detected. Please upload PDF or DOCX files. (Original: {$attachment->original_name})");
                    return back();
                }
            }

            // Parse document data
            $parseResult = DocumentParsingService::parseCandidateData($filePath, $fileExtension);
            
            if (isset($parseResult['error'])) {
                Toast::error('Document parsing error: ' . $parseResult['error']);
                return back();
            }

            if (empty($parseResult['candidates'])) {
                Toast::warning('No valid candidate data found in document');
                return back();
            }

            // Get the first candidate from the document (for single candidate import)
            $candidateData = $parseResult['candidates'][0];
            $totalCount = $parseResult['count'];

            // Pre-populate form fields with parsed data
            $formData = [
                'user' => [
                    'name' => $candidateData['name'] ?? '',
                    'email' => $candidateData['email'] ?? '',
                    'phone' => $candidateData['phone'] ?? '',
                    'city' => $candidateData['city'] ?? '',
                    'country' => $candidateData['country'] ?? '',
                    'nationality' => $candidateData['nationality'] ?? '',
                    'dob' => $candidateData['date_of_birth'] ?? '',
                ],
                'candidate' => [
                    'gender' => $candidateData['gender'] ?? '',
                    'current_company' => $candidateData['current_company'] ?? '',
                    'current_job_title' => $candidateData['current_job_title'] ?? '',
                    'languages' => $candidateData['languages'] ?? '',
                    'skills' => $candidateData['skills'] ?? '',
                    'work_experiences' => $candidateData['work_experiences'] ?? '',
                ]
            ];

            // Store the parsed data in session to populate form
            session()->flash('document_parsed_data', $formData);
            session()->flash('document_total_count', $totalCount);
            session()->flash('parsed_document_attachment_id', $documentAttachmentId);
            
            Toast::success('Document parsed successfully! Form fields have been populated with extracted candidate data. The CV will be saved when you create the candidate.');

            return back();

        } catch (\Exception $e) {
            Toast::error('Error parsing document: ' . $e->getMessage());
            return back();
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
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

        // Handle password setup based on request
        $isNewUser = !$user->exists;
        $sendPasswordEmail = $request->boolean('send_password_setup_email', false);
        
        if ($isNewUser && $sendPasswordEmail) {
            // For new users with email setup - don't set password yet
            $user->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray());
            $user->password = Hash::make(Str::random(32)); // Set temporary random password
            $user->save();
        } else {
            // For existing users or manual password setup
            $user->when($request->filled('user.password'), function (Builder $builder) use ($request) {
                $builder->getModel()->password = Hash::make($request->input('user.password'));
            });

            $user
                ->fill($request->collect('user')->except(['password', 'permissions', 'roles'])->toArray())
                ->save();
        }

        // Find the roles
        $role1 = Role::where('slug', 'authenticated_user')->first();
        $role2 = Role::where('slug', 'candidate')->first();
        $user->replaceRoles([$role1->id, $role2->id]);

        $candidateData = $request->collect('candidate')->except([])->toArray();
        $candidateData['user_id'] = $user->id;
        
        // Ensure null values are converted to empty strings for database constraints
        $candidateData['skills'] = $candidateData['skills'] ?? '';
        $candidateData['languages'] = $candidateData['languages'] ?? '';
        $candidateData['work_experiences'] = $candidateData['work_experiences'] ?? '';
        $candidateData['gender'] = $candidateData['gender'] ?? '';
        $candidateData['current_company'] = $candidateData['current_company'] ?? '';
        $candidateData['current_job_title'] = $candidateData['current_job_title'] ?? '';
        $candidateData['notes'] = $candidateData['notes'] ?? '';
        
        // Check if this is a new candidate
        $isNewCandidate = !$candidate->exists;
        
        if ($isNewCandidate) {
            $candidateData['candidate_ref'] = HelperFunc::generateReferenceNumber('candidate');
        }

        $candidate->fill($candidateData)->save();

        // Log activity
        if ($isNewCandidate) {
            ActivityService::profileCreated($candidate);
        } else {
            ActivityService::profileUpdated($candidate, auth()->id());
        }

        // Sync attachments for "candidate.cv"
        if ($request->has('candidate.cv')) {
            $cvAttachments = $request->input('candidate.cv', []);
            // $this->candidate->attachment()->syncWithoutDetaching($cvAttachments);
            $currentCvAttachments = $candidate->attachment()->wherePivot('field_name', 'cv')->pluck('attachments.id')->toArray();

            $newCvAttachments = array_diff($cvAttachments, $currentCvAttachments);
            if (!empty($newCvAttachments)) {
                $candidate->attachment()->attach($newCvAttachments, ['field_name' => 'cv']);
                
                // Log document upload activity for each new CV
                foreach ($newCvAttachments as $attachmentId) {
                    $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);
                    if ($attachment) {
                        ActivityService::documentUploaded($candidate, 'CV', $attachment->original_name, auth()->id());
                    }
                }
            }
        }

        // Sync attachments for "candidate.other-documents"
        if ($request->has('candidate.other-documents')) {
            $otherDocumentsAttachments = $request->input('candidate.other-documents', []);
            $currentOtherDocumentsAttachments = $candidate->attachment()
                ->wherePivot('field_name', 'other-documents')
                ->pluck('attachments.id')
                ->toArray();

            $newOtherDocumentsAttachments = array_diff($otherDocumentsAttachments, $currentOtherDocumentsAttachments);
            if (!empty($newOtherDocumentsAttachments)) {
                $candidate->attachment()->attach($newOtherDocumentsAttachments, ['field_name' => 'other-documents']);
                
                // Log document upload activity for each new document
                foreach ($newOtherDocumentsAttachments as $attachmentId) {
                    $attachment = \Orchid\Attachment\Models\Attachment::find($attachmentId);
                    if ($attachment) {
                        ActivityService::documentUploaded($candidate, 'Other Document', $attachment->original_name, auth()->id());
                    }
                }
            }
        }
        // $candidate->attachment()->syncWithoutDetaching(
        //     $request->input('candidate.other-documents', [])
        // );

        // Send password setup email if requested
        if ($isNewUser && $sendPasswordEmail) {
            try {
                // Generate password reset token
                $token = Str::random(64);
                
                // Store the token in password_reset_tokens table
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => $user->email],
                    [
                        'email' => $user->email,
                        'token' => hash('sha256', $token),
                        'created_at' => now()
                    ]
                );
                
                // Send password setup email
                Mail::to($user)->send(new PasswordSetupMail($user, $token));
                
                Toast::success(__('Candidate saved successfully! Password setup email has been sent to ') . $user->email);
            } catch (\Exception $e) {
                Toast::warning(__('Candidate saved, but failed to send password setup email: ') . $e->getMessage());
            }
        } else {
            Toast::info(__('Candidate saved'));
        }

        // return redirect()->route('platform.candidates.list');
        return redirect()->route('platform.candidates.view', $candidate->id);
    }

    /**
     * Cancel the edit operation and return to the list screen.
     *
     * @return \Illuminate\Http\RedirectResponse
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
