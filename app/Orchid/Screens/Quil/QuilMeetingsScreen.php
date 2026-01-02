<?php

namespace App\Orchid\Screens\Quil;

use App\Models\QuilMeeting;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

class QuilMeetingsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'quilMeetings' => QuilMeeting::with(['user', 'candidate'])
                ->orderBy('created_at', 'desc')
                ->paginate(20),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Quil Meeting Notes';
    }

    /**
     * The screen's description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'AI-powered meeting transcriptions and summaries from Quil';
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
            Layout::table('quilMeetings', [
                TD::make('meeting_name', 'Meeting Name')
                    ->sort()
                    ->filter(TD::FILTER_TEXT)
                    ->render(function (QuilMeeting $meeting) {
                        return Link::make($meeting->meeting_name)
                            ->route('platform.quil.view', $meeting->id);
                    }),

                TD::make('start_time', 'Date')
                    ->sort()
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->start_time 
                            ? $meeting->start_time->format('M d, Y g:i A')
                            : 'N/A';
                    }),

                TD::make('owner_name', 'Owner')
                    ->sort()
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->owner_name ?? 'N/A';
                    }),

                TD::make('user_id', 'Matched User')
                    ->render(function (QuilMeeting $meeting) {
                        if ($meeting->user) {
                            return Link::make($meeting->user->name)
                                ->route('platform.candidates.profile', $meeting->candidate_id ?? $meeting->user_id);
                        }
                        return '<span class="badge bg-warning">No match</span>';
                    }),

                TD::make('participants', 'Participants')
                    ->render(function (QuilMeeting $meeting) {
                        if (empty($meeting->participants)) {
                            return 'N/A';
                        }
                        $count = count($meeting->participants);
                        return '<span class="badge bg-info">' . $count . ' participant' . ($count > 1 ? 's' : '') . '</span>';
                    }),

                TD::make('processing_status', 'Status')
                    ->sort()
                    ->render(function (QuilMeeting $meeting) {
                        $colors = [
                            'matched' => 'success',
                            'unmatched' => 'warning',
                            'received' => 'info',
                        ];
                        $color = $colors[$meeting->processing_status] ?? 'secondary';
                        return '<span class="badge bg-' . $color . '">' . ucfirst($meeting->processing_status) . '</span>';
                    }),

                TD::make('assets', 'Available')
                    ->render(function (QuilMeeting $meeting) {
                        $assets = [];
                        if ($meeting->transcription_url) $assets[] = '<i class="bi bi-file-text" title="Transcript"></i>';
                        if ($meeting->recording_url) $assets[] = '<i class="bi bi-camera-video" title="Recording"></i>';
                        if ($meeting->database_notes) $assets[] = '<i class="bi bi-journal-text" title="Notes"></i>';
                        
                        return implode(' ', $assets) ?: 'N/A';
                    }),

                TD::make('created_at', 'Received')
                    ->sort()
                    ->render(function (QuilMeeting $meeting) {
                        return $meeting->created_at->diffForHumans();
                    }),

                TD::make('actions', 'Actions')
                    ->align(TD::ALIGN_RIGHT)
                    ->render(function (QuilMeeting $meeting) {
                        return Link::make('View Details')
                            ->icon('eye')
                            ->class('btn btn-sm btn-primary')
                            ->route('platform.quil.view', $meeting->id);
                    }),
            ]),
        ];
    }
}
