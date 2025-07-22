<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Meeting;
use App\Models\Job;
use App\Models\Candidate;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Orchid\Layouts\Meeting\MeetingFiltersLayout;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Layouts\Modal;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Support\Facades\Auth;
use App\Services\GoogleCalendarService;
use Orchid\Support\Facades\Toast;

class ScheduleScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $meetings = Meeting::with(['candidate.user', 'job', 'createdBy'])
            ->filters(MeetingFiltersLayout::class)
            ->defaultSort('scheduled_at', 'asc')
            ->paginate();

        $jobs = Job::where('is_active', true)->get();
        $candidates = Candidate::with('user')->get();

        return [
            'meetings' => $meetings,
            'jobs' => $jobs,
            'candidates' => $candidates,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Schedule';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Create Meeting')
                ->modal('meetingModal')
                ->method('createMeeting')
                ->icon('plus')
                ->class('btn btn-primary'),
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
            MeetingFiltersLayout::class,
            
            Layout::table('meetings', [
                TD::make('title', 'Title')
                    ->sort()
                    ->filter(Input::make())
                    ->render(function (Meeting $meeting) {
                        return view('partials.meeting-title', ['meeting' => $meeting]);
                    }),

                TD::make('candidate.user.name', 'Candidate')
                    ->sort()
                    ->filter(Input::make())
                    ->render(function (Meeting $meeting) {
                        if ($meeting->candidate) {
                            return '<a href="' . route('platform.candidates.view', $meeting->candidate->id) . '" class="text-primary">'
                                . e($meeting->candidate->user->name) . '</a>';
                        }
                        return '-';
                    }),

                TD::make('type', 'Type')
                    ->sort()
                    ->render(function (Meeting $meeting) {
                        $badgeClass = $meeting->type === 'video' ? 'badge bg-info' : 'badge bg-success';
                        return "<span class='{$badgeClass}'>" . ucfirst($meeting->type) . "</span>";
                    }),

                TD::make('scheduled_at', 'Scheduled')
                    ->sort()
                    ->render(function (Meeting $meeting) {
                        return $meeting->formatted_scheduled_time;
                    }),

                TD::make('duration_minutes', 'Duration')
                    ->sort()
                    ->render(function (Meeting $meeting) {
                        return $meeting->formatted_duration;
                    }),

                TD::make('status', 'Status')
                    ->sort()
                    ->render(function (Meeting $meeting) {
                        $statusClasses = [
                            'scheduled' => 'badge bg-warning',
                            'completed' => 'badge bg-success',
                            'cancelled' => 'badge bg-danger',
                        ];
                        $badgeClass = $statusClasses[$meeting->status] ?? 'badge bg-secondary';
                        return "<span class='{$badgeClass}'>" . ucfirst($meeting->status) . "</span>";
                    }),

                TD::make('job.title', 'Related Job')
                    ->render(function (Meeting $meeting) {
                        if ($meeting->job) {
                            return '<a href="' . route('platform.jobs.edit', $meeting->job->id) . '" class="text-primary" target="_blank">'
                                . e($meeting->job->title) . '</a>';
                        }
                        return '-';
                    }),

                TD::make('actions', 'Actions')
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Meeting $meeting) {
                        return view('partials.meeting-actions', ['meeting' => $meeting]);
                    }),
            ]),

            Layout::modal('meetingModal', Layout::rows([
                Input::make('meeting.title')
                    ->title('Meeting Title')
                    ->placeholder('Enter meeting title')
                    ->required(),

                Group::make([
                    Select::make('meeting.type')
                        ->title('Meeting Type')
                        ->options([
                            'video' => 'Video Call',
                            'phone' => 'Phone Call',
                        ])
                        ->required(),

                    Select::make('meeting.duration_minutes')
                        ->title('Duration (minutes)')
                        ->options([
                            15 => '15 minutes',
                            30 => '30 minutes',
                            45 => '45 minutes',
                            60 => '1 hour',
                            90 => '1.5 hours',
                            120 => '2 hours',
                        ])
                        ->required(),
                ]),

                DateTimer::make('meeting.scheduled_at')
                    ->title('Date & Time')
                    ->enableTime()
                    ->required(),

                Group::make([
                    Select::make('meeting.candidate_id')
                        ->title('Candidate')
                        ->fromQuery(Candidate::with('user'), 'user.name', 'id')
                        ->required(),

                    Select::make('meeting.job_id')
                        ->title('Related Job (Optional)')
                        ->fromQuery(Job::where('is_active', true), 'title', 'id')
                        ->empty('Select a job (optional)'),
                ]),

                Input::make('meeting.meeting_link')
                    ->title('Meeting Link (Optional)')
                    ->placeholder('https://meet.google.com/...')
                    ->type('url'),

                Input::make('meeting.phone_number')
                    ->title('Phone Number (Optional)')
                    ->placeholder('+1 (555) 123-4567')
                    ->type('tel'),

                TextArea::make('meeting.description')
                    ->title('Description (Optional)')
                    ->rows(3)
                    ->placeholder('Add meeting details, agenda, or notes...'),

                CheckBox::make('create_google_event')
                    ->title('Create Google Calendar event')
                    ->value(1)
                    ->help('When enabled, this meeting will be automatically added to your Google Calendar with the candidate as an attendee.'),
            ]))
            ->title('Create New Meeting')
            ->applyButton('Create Meeting')
            ->closeButton('Cancel'),

            Layout::modal('editMeetingModal', Layout::rows([
                Input::make('meeting.id')
                    ->type('hidden'),
                Input::make('meeting.title')
                    ->title('Meeting Title')
                    ->placeholder('Enter meeting title')
                    ->required(),

                Group::make([
                    Select::make('meeting.type')
                        ->title('Meeting Type')
                        ->options([
                            'video' => 'Video Call',
                            'phone' => 'Phone Call',
                        ])
                        ->required(),

                    Select::make('meeting.duration_minutes')
                        ->title('Duration (minutes)')
                        ->options([
                            15 => '15 minutes',
                            30 => '30 minutes',
                            45 => '45 minutes',
                            60 => '1 hour',
                            90 => '1.5 hours',
                            120 => '2 hours',
                        ])
                        ->required(),
                ]),

                DateTimer::make('meeting.scheduled_at')
                    ->title('Date & Time')
                    ->enableTime()
                    ->required(),

                Group::make([
                    Select::make('meeting.candidate_id')
                        ->title('Candidate')
                        ->fromQuery(Candidate::with('user'), 'user.name', 'id')
                        ->required(),

                    Select::make('meeting.job_id')
                        ->title('Related Job (Optional)')
                        ->fromQuery(Job::where('is_active', true), 'title', 'id')
                        ->empty('Select a job (optional)'),
                ]),

                Input::make('meeting.meeting_link')
                    ->title('Meeting Link (Optional)')
                    ->placeholder('https://meet.google.com/...')
                    ->type('url'),

                Input::make('meeting.phone_number')
                    ->title('Phone Number (Optional)')
                    ->placeholder('+1 (555) 123-4567')
                    ->type('tel'),

                TextArea::make('meeting.description')
                    ->title('Description (Optional)')
                    ->placeholder('Add meeting details, agenda, or notes...')
                    ->rows(3),
            ]))
            ->title('Edit Meeting')
            ->method('updateMeeting')
            ->applyButton('Update Meeting')
            ->closeButton('Cancel')
            ->async('asyncGetMeeting'),
        ];
    }

    /**
     * Create a new meeting.
     */
    public function createMeeting(Request $request): void
    {
        $request->validate([
            'meeting.title' => 'required|string|max:255',
            'meeting.type' => 'required|in:video,phone',
            'meeting.scheduled_at' => 'required|date',
            'meeting.duration_minutes' => 'required|integer|min:15|max:480',
            'meeting.description' => 'nullable|string',
            'meeting.job_id' => 'nullable|exists:jobs,id',
            'meeting.candidate_id' => 'required|exists:candidates,id',
            'meeting.meeting_link' => 'nullable|url',
            'meeting.phone_number' => 'nullable|string|max:20',
        ]);

        $meetingData = $request->get('meeting');
        $meetingData['created_by'] = Auth::id();
        $meetingData['job_id'] = $meetingData['job_id'] ?: null;

        $meeting = Meeting::create($meetingData);

        // Load relationships for activity logging
        $meeting->load(['candidate', 'job']);
        
        // Log meeting scheduled activity
        try {
            \App\Services\ActivityService::meetingScheduled($meeting->candidate, $meeting, Auth::id());
        } catch (\Exception $e) {
            \Log::error('Failed to log meeting activity: ' . $e->getMessage(), [
                'meeting_id' => $meeting->id,
                'candidate_id' => $meeting->candidate_id,
                'user_id' => Auth::id(),
                'error_trace' => $e->getTraceAsString()
            ]);
            // Don't fail the meeting creation, just log the error
        }

        // Create Google Calendar event if enabled and user has Google connection
        if ($request->get('create_google_event')) {
            $user = Auth::user();
            if ($user->googleConnection && $user->googleConnection->isValid()) {
                $this->createGoogleCalendarEvent($meeting);
            } else {
                \Log::warning('Google Calendar event not created: User does not have valid Google connection', [
                    'user_id' => $user->id,
                    'has_connection' => $user->googleConnection ? 'yes' : 'no',
                    'is_valid' => $user->googleConnection ? $user->googleConnection->isValid() : 'no connection'
                ]);
            }
        }

        Toast::info('Meeting created successfully!');
    }

    /**
     * Update an existing meeting.
     */
    public function updateMeeting(Request $request): void
    {
        $request->validate([
            'meeting.id' => 'required|exists:meetings,id',
            'meeting.title' => 'required|string|max:255',
            'meeting.type' => 'required|in:video,phone',
            'meeting.scheduled_at' => 'required|date',
            'meeting.duration_minutes' => 'required|integer|min:15|max:480',
            'meeting.description' => 'nullable|string',
            'meeting.job_id' => 'nullable|exists:jobs,id',
            'meeting.candidate_id' => 'required|exists:candidates,id',
            'meeting.meeting_link' => 'nullable|url',
            'meeting.phone_number' => 'nullable|string|max:20',
        ]);

        $meetingData = $request->all()['meeting'];
        $meeting = Meeting::findOrFail($meetingData['id']);
        
        $meetingData['job_id'] = $meetingData['job_id'] ?: null;
        $meeting->update($meetingData);

        // Load relationships for activity logging
        $meeting->load(['candidate', 'job']);
        
        // Log meeting updated activity
        try {
            \App\Services\ActivityService::meetingUpdated($meeting->candidate, $meeting, Auth::id());
        } catch (\Exception $e) {
            \Log::error('Failed to log meeting update activity: ' . $e->getMessage(), [
                'meeting_id' => $meeting->id,
                'candidate_id' => $meeting->candidate_id,
                'user_id' => Auth::id(),
                'error_trace' => $e->getTraceAsString()
            ]);
            // Don't fail the meeting update, just log the error
        }

        // Update Google Calendar event if it exists
        if ($meeting->google_event_id && Auth::user()->googleConnection) {
            $this->updateGoogleCalendarEvent($meeting);
        }

        Toast::info('Meeting updated successfully!');
    }

    /**
     * Delete a meeting.
     */
    public function deleteMeeting(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:meetings,id',
        ]);

        $meeting = Meeting::findOrFail($request->get('id'));
        $meeting->delete();
        
        Toast::info('Meeting deleted successfully!');
        
        return redirect()->route('platform.schedule');
    }

    /**
     * Update meeting status.
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:meetings,id',
            'status' => 'required|in:scheduled,completed,cancelled',
        ]);

        $meeting = Meeting::findOrFail($request->get('id'));
        $oldStatus = $meeting->status;
        $newStatus = $request->get('status');
        
        $meeting->update(['status' => $newStatus]);
        
        // Load relationships for activity logging
        $meeting->load(['candidate', 'job']);
        
        // Log meeting status changed activity
        try {
            \App\Services\ActivityService::meetingStatusChanged($meeting->candidate, $meeting, $oldStatus, $newStatus, Auth::id());
        } catch (\Exception $e) {
            \Log::error('Failed to log meeting status change activity: ' . $e->getMessage(), [
                'meeting_id' => $meeting->id,
                'candidate_id' => $meeting->candidate_id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'user_id' => Auth::id(),
                'error_trace' => $e->getTraceAsString()
            ]);
            // Don't fail the status update, just log the error
        }
        
        Toast::info('Meeting status updated successfully!');
        
        return redirect()->route('platform.schedule');
    }

    /**
     * Async method to load meeting data for the edit modal.
     */
    public function asyncGetMeeting(Meeting $meeting): array
    {
        $meeting->load(['candidate.user', 'job']);
        
        return [
            'meeting' => [
                'id' => $meeting->id,
                'title' => $meeting->title,
                'type' => $meeting->type,
                'scheduled_at' => $meeting->scheduled_at,
                'duration_minutes' => $meeting->duration_minutes,
                'candidate_id' => $meeting->candidate_id,
                'job_id' => $meeting->job_id,
                'meeting_link' => $meeting->meeting_link,
                'phone_number' => $meeting->phone_number,
                'description' => $meeting->description,
            ],
        ];
    }

    /**
     * Create Google Calendar event.
     */
    protected function createGoogleCalendarEvent($meeting): void
    {
        try {
            $user = Auth::user();
            $googleService = new GoogleCalendarService($user);
            
            if (!$googleService->isConnected()) {
                \Log::error('Google Calendar service not connected for user: ' . $user->id);
                return;
            }
            
            $eventData = [
                'summary' => $meeting->title,
                'description' => $this->buildEventDescription($meeting),
                'start' => [
                    'dateTime' => $meeting->scheduled_at->toISOString(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => $meeting->scheduled_at->addMinutes($meeting->duration_minutes)->toISOString(),
                    'timeZone' => config('app.timezone'),
                ],
                'attendees' => [
                    ['email' => $meeting->candidate->user->email],
                ],
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ];

            if ($meeting->type === 'video') {
                $eventData['conferenceData'] = [
                    'createRequest' => [
                        'requestId' => uniqid(),
                        'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    ],
                ];
            }

            \Log::info('Creating Google Calendar event', [
                'meeting_id' => $meeting->id,
                'event_data' => $eventData
            ]);

            $googleEvent = $googleService->createEvent($eventData);
            
            if ($googleEvent) {
                // Update meeting with Google event ID and meeting link
                $updateData = ['google_event_id' => $googleEvent->id];
                
                // If it's a video meeting and Google generated a conference, get the meeting link
                if ($meeting->type === 'video' && isset($googleEvent->conferenceData)) {
                    $conferenceData = $googleEvent->conferenceData;
                    if (isset($conferenceData->entryPoints)) {
                        foreach ($conferenceData->entryPoints as $entryPoint) {
                            if ($entryPoint->entryPointType === 'video') {
                                $updateData['meeting_link'] = $entryPoint->uri;
                                break;
                            }
                        }
                    }
                }
                
                $meeting->update($updateData);
                
                \Log::info('Google Calendar event created successfully', [
                    'meeting_id' => $meeting->id,
                    'google_event_id' => $googleEvent->id,
                    'meeting_link' => $updateData['meeting_link'] ?? 'not set'
                ]);
            } else {
                \Log::error('Google Calendar event creation returned null');
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to create Google Calendar event: ' . $e->getMessage(), [
                'meeting_id' => $meeting->id,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Update Google Calendar event.
     */
    protected function updateGoogleCalendarEvent($meeting): void
    {
        try {
            $googleService = new GoogleCalendarService(Auth::user());
            
            $eventData = [
                'summary' => $meeting->title,
                'description' => $this->buildEventDescription($meeting),
                'start' => [
                    'dateTime' => $meeting->scheduled_at->toISOString(),
                    'timeZone' => config('app.timezone'),
                ],
                'end' => [
                    'dateTime' => $meeting->scheduled_at->addMinutes($meeting->duration_minutes)->toISOString(),
                    'timeZone' => config('app.timezone'),
                ],
                'attendees' => [
                    ['email' => $meeting->candidate->user->email],
                ],
            ];

            // Add conference data for video meetings if not already present
            if ($meeting->type === 'video' && !$meeting->meeting_link) {
                $eventData['conferenceData'] = [
                    'createRequest' => [
                        'requestId' => uniqid(),
                        'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    ],
                ];
            }

            $googleEvent = $googleService->updateEvent($meeting->google_event_id, $eventData);
            
            // If it's a video meeting and we got a response, check for new meeting link
            if ($meeting->type === 'video' && $googleEvent && isset($googleEvent->conferenceData)) {
                $conferenceData = $googleEvent->conferenceData;
                if (isset($conferenceData->entryPoints)) {
                    foreach ($conferenceData->entryPoints as $entryPoint) {
                        if ($entryPoint->entryPointType === 'video') {
                            $meeting->update(['meeting_link' => $entryPoint->uri]);
                            break;
                        }
                    }
                }
            }
            
        } catch (\Exception $e) {
            \Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
        }
    }

    /**
     * Build event description for Google Calendar.
     */
    protected function buildEventDescription($meeting): string
    {
        $description = $meeting->description ?: '';
        
        if ($meeting->job) {
            $description .= "\n\nRelated Job: " . $meeting->job->title;
        }
        
        if ($meeting->type === 'video' && $meeting->meeting_link) {
            $description .= "\n\nMeeting Link: " . $meeting->meeting_link;
        } elseif ($meeting->type === 'phone' && $meeting->phone_number) {
            $description .= "\n\nPhone Number: " . $meeting->phone_number;
        }
        
        // Add note about Google Meet for video meetings without custom link
        if ($meeting->type === 'video' && !$meeting->meeting_link) {
            $description .= "\n\nGoogle Meet link will be generated automatically.";
        }
        
        return $description;
    }
}
