<?php

namespace App\Orchid\Screens\Quil;

use App\Models\QuilMeeting;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Label;
use Orchid\Screen\Sight;
use Illuminate\Http\Request;

class QuilMeetingViewScreen extends Screen
{
    public $quilMeeting;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(QuilMeeting $quilMeeting): iterable
    {
        $quilMeeting->load(['user', 'candidate', 'meeting']);

        return [
            'quilMeeting' => $quilMeeting,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->quilMeeting->meeting_name ?? 'Meeting Details';
    }

    /**
     * The screen's description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'AI-powered meeting transcription and summary';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        $buttons = [
            Link::make('Back to List')
                ->icon('arrow-left')
                ->route('platform.quil.list'),
        ];

        if ($this->quilMeeting->candidate_id) {
            $buttons[] = Link::make('View Candidate Profile')
                ->icon('user')
                ->route('platform.candidates.profile', $this->quilMeeting->candidate_id);
        }

        return $buttons;
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::legend('quilMeeting', [
                Sight::make('meeting_name', 'Meeting Name'),
                
                Sight::make('start_time', 'Start Time')
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->start_time 
                            ? $meeting->start_time->format('F j, Y \a\t g:i A')
                            : 'Not specified';
                    }),

                Sight::make('owner_name', 'Meeting Owner'),

                Sight::make('processing_status', 'Status')
                    ->render(function (QuilMeeting $meeting) {
                        $colors = [
                            'matched' => 'success',
                            'unmatched' => 'warning',
                            'received' => 'info',
                        ];
                        $color = $colors[$meeting->processing_status] ?? 'secondary';
                        return '<span class="badge bg-' . $color . '">' . ucfirst($meeting->processing_status) . '</span>';
                    }),

                Sight::make('user_id', 'Matched User')
                    ->render(function (QuilMeeting $meeting) {
                        if ($meeting->user) {
                            return Link::make($meeting->user->name)
                                ->route('platform.candidates.profile', $meeting->candidate_id ?? $meeting->user_id);
                        }
                        return '<span class="text-warning">No user matched by phone number</span>';
                    }),

                Sight::make('participants', 'Participants')
                    ->render(function (QuilMeeting $meeting) {
                        if (empty($meeting->participants)) {
                            return 'None';
                        }
                        return implode('<br>', $meeting->participants);
                    }),
            ])->title('Meeting Information'),

            Layout::view('orchid.quil.meeting-summary', [
                'quilMeeting' => $this->quilMeeting,
            ]),

            Layout::view('orchid.quil.meeting-assets', [
                'quilMeeting' => $this->quilMeeting,
            ]),

            Layout::legend('quilMeeting', [
                Sight::make('quil_meeting_id', 'Quil Meeting ID'),
                Sight::make('event_id', 'Webhook Event ID'),
                Sight::make('created_at', 'Received At')
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->created_at->format('F j, Y \a\t g:i A');
                    }),
                Sight::make('processing_notes', 'Processing Notes')
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->processing_notes ?: 'None';
                    }),
            ])->title('System Information'),
        ];
    }
}
