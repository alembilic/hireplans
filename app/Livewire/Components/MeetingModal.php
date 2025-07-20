<?php

namespace App\Livewire\Components;

use App\Models\Meeting;
use App\Models\Job;
use App\Models\Candidate;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class MeetingModal extends Component
{
    public $show = false;
    public $mode = 'create'; // 'create' or 'edit'
    public $meeting = null;
    public $createGoogleEvent = true;

    // Form properties
    public $title = '';
    public $type = 'video';
    public $scheduled_at = '';
    public $duration_minutes = 30;
    public $description = '';
    public $job_id = '';
    public $candidate_id = '';
    public $meeting_link = '';
    public $phone_number = '';

    protected $listeners = [
        'openMeetingModal' => 'openModal',
        'closeMeetingModal' => 'closeModal',
    ];

    public function mount()
    {
        $this->scheduled_at = now()->addHour()->format('Y-m-d\TH:i');
    }

    public function openModal($data = [])
    {
        $this->resetForm();
        
        if (isset($data['mode'])) {
            $this->mode = $data['mode'];
        }
        
        if (isset($data['meeting'])) {
            $this->meeting = Meeting::find($data['meeting']);
            $this->loadMeetingData();
        }
        
        if (isset($data['candidate_id'])) {
            $this->candidate_id = $data['candidate_id'];
        }
        
        if (isset($data['job_id'])) {
            $this->job_id = $data['job_id'];
        }
        
        $this->show = true;
    }

    public function closeModal()
    {
        $this->show = false;
        $this->resetForm();
        $this->meeting = null;
        $this->mode = 'create';
    }

    public function resetForm()
    {
        $this->title = '';
        $this->type = 'video';
        $this->scheduled_at = now()->addHour()->format('Y-m-d\TH:i');
        $this->duration_minutes = 30;
        $this->description = '';
        $this->job_id = '';
        $this->candidate_id = '';
        $this->meeting_link = '';
        $this->phone_number = '';
        $this->createGoogleEvent = true;
    }

    public function loadMeetingData()
    {
        if (!$this->meeting) return;
        
        $this->title = $this->meeting->title;
        $this->type = $this->meeting->type;
        $this->scheduled_at = $this->meeting->scheduled_at->format('Y-m-d\TH:i');
        $this->duration_minutes = $this->meeting->duration_minutes;
        $this->description = $this->meeting->description;
        $this->job_id = $this->meeting->job_id;
        $this->candidate_id = $this->meeting->candidate_id;
        $this->meeting_link = $this->meeting->meeting_link;
        $this->phone_number = $this->meeting->phone_number;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,phone',
            'scheduled_at' => 'required|date',
            'duration_minutes' => 'required|integer|min:15|max:480',
            'description' => 'nullable|string',
            'job_id' => 'nullable|exists:jobs,id',
            'candidate_id' => 'required|exists:candidates,id',
            'meeting_link' => 'nullable|url',
            'phone_number' => 'nullable|string|max:20',
        ]);

        $meetingData = [
            'title' => $this->title,
            'type' => $this->type,
            'scheduled_at' => $this->scheduled_at,
            'duration_minutes' => $this->duration_minutes,
            'description' => $this->description,
            'job_id' => $this->job_id ?: null,
            'candidate_id' => $this->candidate_id,
            'meeting_link' => $this->meeting_link,
            'phone_number' => $this->phone_number,
        ];

        if ($this->mode === 'create') {
            $meetingData['created_by'] = Auth::id();
            $meeting = Meeting::create($meetingData);
            
            // Create Google Calendar event if enabled and user has Google connection
            if ($this->createGoogleEvent && Auth::user()->googleConnection) {
                $this->createGoogleCalendarEvent($meeting);
            }
            
            $this->dispatch('meeting-created', message: 'Meeting created successfully!');
        } else {
            $this->meeting->update($meetingData);
            
            // Update Google Calendar event if it exists
            if ($this->meeting->google_event_id && Auth::user()->googleConnection) {
                $this->updateGoogleCalendarEvent($this->meeting);
            }
            
            $this->dispatch('meeting-updated', message: 'Meeting updated successfully!');
        }

        $this->closeModal();
        $this->dispatch('meeting-saved', meeting: $meeting ?? $this->meeting);
    }

    protected function createGoogleCalendarEvent($meeting)
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
                'reminders' => [
                    'useDefault' => false,
                    'overrides' => [
                        ['method' => 'email', 'minutes' => 24 * 60],
                        ['method' => 'popup', 'minutes' => 30],
                    ],
                ],
            ];

            if ($meeting->type === 'video' && $meeting->meeting_link) {
                $eventData['conferenceData'] = [
                    'createRequest' => [
                        'requestId' => uniqid(),
                        'conferenceSolutionKey' => ['type' => 'hangoutsMeet'],
                    ],
                ];
            }

            $googleEvent = $googleService->createEvent($eventData);
            
            // Update meeting with Google event ID
            $meeting->update(['google_event_id' => $googleEvent->id]);
            
        } catch (\Exception $e) {
            // Log error but don't fail the meeting creation
            \Log::error('Failed to create Google Calendar event: ' . $e->getMessage());
        }
    }

    protected function updateGoogleCalendarEvent($meeting)
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

            $googleService->updateEvent($meeting->google_event_id, $eventData);
            
        } catch (\Exception $e) {
            \Log::error('Failed to update Google Calendar event: ' . $e->getMessage());
        }
    }

    protected function buildEventDescription($meeting)
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
        
        return $description;
    }

    public function render()
    {
        $jobs = Job::where('is_active', true)->get();
        $candidates = Candidate::with('user')->get();
        
        return view('livewire.components.meeting-modal', [
            'jobs' => $jobs,
            'candidates' => $candidates,
        ]);
    }
}
