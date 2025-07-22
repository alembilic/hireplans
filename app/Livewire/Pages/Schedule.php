<?php

namespace App\Livewire\Pages;

use App\Models\Meeting;
use App\Models\Job;
use App\Models\Candidate;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class Schedule extends Component
{
    use WithPagination;

    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $editingMeeting = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'filterType' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function mount()
    {
        // Component initialization
    }

    public function render()
    {
        $meetings = Meeting::query()
            ->with(['candidate.user', 'job', 'createdBy'])
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhereHas('candidate.user', function ($q) {
                        $q->where('name', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('job', function ($q) {
                        $q->where('title', 'like', '%' . $this->search . '%');
                    });
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('scheduled_at', 'asc')
            ->paginate(10);

        $jobs = Job::where('is_active', true)->get();
        $candidates = Candidate::with('user')->get();

        return view('livewire.pages.schedule', [
            'meetings' => $meetings,
            'jobs' => $jobs,
            'candidates' => $candidates,
        ]);
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterType()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->dispatch('openMeetingModal', ['mode' => 'create']);
    }

    public function openEditModal($meetingId)
    {
        $this->dispatch('openMeetingModal', ['mode' => 'edit', 'meeting' => $meetingId]);
    }

    // Meeting creation and updates are handled by the MeetingModal component

    public function deleteMeeting($meetingId)
    {
        $meeting = Meeting::findOrFail($meetingId);
        $meeting->delete();
        
        $this->dispatch('meeting-deleted', message: 'Meeting deleted successfully!');
    }

    public function updateStatus($meetingId, $status)
    {
        $meeting = Meeting::with(['candidate', 'job'])->findOrFail($meetingId);
        $oldStatus = $meeting->status;
        $meeting->update(['status' => $status]);
        
        // Log meeting status change activity if status actually changed
        if ($oldStatus !== $status) {
            if ($status === 'completed') {
                // Use the specific completed activity type
                ActivityService::log(
                    $meeting->candidate,
                    'meeting_completed',
                    'Meeting Completed',
                    "Meeting '{$meeting->title}' was marked as completed",
                    [
                        'meeting_id' => $meeting->id,
                        'meeting_title' => $meeting->title,
                        'meeting_type' => $meeting->type,
                        'scheduled_at' => $meeting->scheduled_at->toISOString(),
                        'job_id' => $meeting->job_id,
                        'job_title' => $meeting->job->title ?? null,
                        'old_status' => $oldStatus,
                        'new_status' => $status,
                    ],
                    Auth::id()
                );
            } else {
                // Use the general meeting updated activity type for other status changes
                ActivityService::meetingUpdated($meeting->candidate, $meeting, Auth::id());
            }
        }
        
        $this->dispatch('meeting-status-updated', message: 'Meeting status updated successfully!');
    }
}
