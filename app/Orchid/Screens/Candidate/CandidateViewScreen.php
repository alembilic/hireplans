<?php

namespace App\Orchid\Screens\Candidate;

use Orchid\Screen\Screen;
use App\Models\Candidate;
use App\Models\User;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Group;
use App\Helpers\HelperFunc;
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
        // dd($candidate);

        // $cv = $candidate->getCvAttachments();
        // $otherDocuments = $candidate->getOtherDocAttachments();

        return [
            'candidate'  => $candidate,
            'user'       => $candidate->user,
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
        // dd($this->candidate);
        // dd($this->candidate->getCvAttachmentUrls());

        return [
            Layout::legend(
                'candidate',
                [
                    Sight::make('user.name', 'Candidate Name'),
                    Sight::make('candidate_ref', 'Candidate Reference'),
                    Sight::make('user.email', 'Email'),
                    Sight::make('user.email_verified_at', 'Email Verified')->render(fn (Candidate $candidate) => $candidate->user->email_verified_at === null
                        ? '<i class="text-danger">●</i> False'
                        : '<i class="text-success">●</i> True'),
                    Sight::make('user.phone', 'Phone'),
                    Sight::make('user.address_line_1', 'Address Line 1'),
                    Sight::make('user.city', 'City'),
                    Sight::make('user.postcode', 'Postcode'),
                    Sight::make('user.country', 'Country'),
                    Sight::make('user.nationality', 'Nationality'),
                    Sight::make('user.dob', 'Date of Birth'),
                    Sight::make('user.created_at', 'Created At'),
                    Sight::make('user.updated_at', 'Updated At'),
                    Sight::make('gender'),
                    Sight::make('languages'),
                    Sight::make('skills'),
                    Sight::make('current_company', 'Current Company'),
                    Sight::make('current_job_title', 'Current Job Title'),
                    Sight::make('', 'CV')->render(fn (Candidate $candidate) => implode('; ', $this->renderAttachmentsLinks($candidate->getCvAttachmentsInfo()))),
                    Sight::make('', 'Other Documents')->render(fn (Candidate $candidate) => implode('; ', $this->renderAttachmentsLinks($candidate->getOtherDocAttachmentsInfo()))),
                    Sight::make('notes'),
                    Sight::make('')
                        ->render(function () {
                            return Group::make([
                                Button::make('Edit')
                                    ->type(Color::INFO)
                                    ->icon('bs.pencil')
                                    ->method('redirectToEditScreen'),
                                Button::make('Close')
                                    ->type(Color::DEFAULT)
                                    ->icon('bs.x-circle')
                                    ->method('redirectToListScreen'),
                            ])->autoWidth()->alignCenter();
                        }),
                ]
            )->title('Candidate Details')
            // ->title($this->candidate->user->name)
        ];
    }

    public function redirectToEditScreen($candidate)
    {
        return redirect()->route('platform.candidates.edit', $candidate);
    }
    public function redirectToListScreen()
    {
        return redirect()->route('platform.candidates.list');
    }

    private function renderAttachmentsLinks($attachments): array
    {
        return array_map(function ($attachment) {
            $url = htmlspecialchars((string) $attachment->url);
            return '<a href="'.$url.'" target="_blank">'.$attachment->text.'</a>';
        }, $attachments);
    }
}
