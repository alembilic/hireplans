<?php

namespace App\Orchid\Screens\Reference;

use Orchid\Screen\Screen;
use App\Models\Reference;
use App\Models\Candidate;
use App\Models\User;
use Orchid\Screen\Sight;
use Orchid\Support\Facades\Layout;
use App\Orchid\Layouts\Candidate\CandidateNavItemsLayout;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Color;
use Orchid\Screen\Fields\Group;
use App\Helpers\HelperFunc;

class ReferenceViewScreen extends Screen
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
     * @var Reference
     */

    public $reference;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Reference $reference): iterable
    {
        $reference->load(['candidate', 'candidate.user']); // Eager load the candidate and user relationships

        return [
            'reference' => $reference,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Reference Details';
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
        $referenceLegendItems = [
            Sight::make('name', 'Referee Name'),
            Sight::make('email', 'Referee Email'),
            Sight::make('phone', 'Referee Phone'),
            Sight::make('relationship', 'Relationship'),
            Sight::make('position', 'Referee Position'),
            Sight::make('company', 'Company'),
            Sight::make('company_address', 'Company Address'),
            Sight::make('candidate.user.name', 'Candidate Name'),
            Sight::make('candidate_position', 'Candidate Position'),
            Sight::make('candidate_employed_from', 'Candidate Employed From')->render(fn(Reference $reference) => $reference->candidate_employed_from ? \Carbon\Carbon::parse($reference->candidate_employed_from)->format('d/m/Y') : ''),
            Sight::make('candidate_employed_to', 'Candidate Employed To')->render(fn(Reference $reference) => $reference->candidate_employed_to ? \Carbon\Carbon::parse($reference->candidate_employed_to)->format('d/m/Y') : ''),
            Sight::make('candidate_job_type', 'Candidate Job Type'),
            Sight::make('candidate_service_duration', 'Candidate Service Duration'),
            Sight::make('candidate_leaving_reason', 'Candidate Leaving Reason'),
            Sight::make('created_at', 'Created At')->render(fn(Reference $reference) => \Carbon\Carbon::parse($reference->created_at)->format('d/m/Y H:i:s')),
            Sight::make('completed_at', 'Reference Completed at')->render(fn(Reference $reference) =>
                $reference->completed_at ? \Carbon\Carbon::parse($reference->completed_at)->format('d/m/Y H:i:s') :
                    '<span class="btn-warning px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">Pending</span>'),
        ];

        $out = [
            Layout::legend('reference', $referenceLegendItems)->title('Reference Information'),
        ];

        return $out;
    }
}
