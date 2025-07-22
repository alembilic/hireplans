<?php

namespace App\Orchid\Screens\Candidate;

use Orchid\Screen\Screen;
use App\Models\Candidate;
use App\Models\User;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class CandidateViewScreen extends Screen
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

        return [
            'candidate'  => $candidate,
            'user'       => $candidate->user,
            'cv_links' => $cvAttachmentsInfo ? \App\Helpers\HelperFunc::renderAttachmentsLinks($cvAttachmentsInfo) : [],
            'other_documents_links' => $otherDocumentsAttachmentsInfo ? \App\Helpers\HelperFunc::renderAttachmentsLinks($otherDocumentsAttachmentsInfo) : [],
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Candidate: ' . $this->candidate->user->name;
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
        return [
            Link::make('Edit')
                ->route('platform.candidates.edit', $this->candidate)
                ->icon('bs.pencil'),
            Link::make('Back to List')
                ->route('platform.candidates.list')
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
            Layout::view('livewire.candidate-profile-wrapper', ['candidate' => $this->candidate])
        ];
    }
}
