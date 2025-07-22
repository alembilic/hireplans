<?php

namespace App\Livewire;

use App\Enums\JobApplicationStatus;
use App\Models\Job;
use App\Models\JobApplication;
use App\Services\ActivityService;
use Livewire\Component;
use Livewire\WithPagination;

class JobPipeline extends Component
{
    use WithPagination;

    public $selectedJobId = null;
    public $selectedStatus = -1;
    public $jobs = [];
    public $applications = [];
    public $statusCounts = [];

    protected $queryString = [
        'selectedJobId' => ['except' => ''],
        'selectedStatus' => ['except' => -1],
    ];

    public function mount()
    {
        $this->loadJobs();
        $this->loadApplications();
    }

    public function updatedSelectedJobId($value)
    {
        $this->selectedStatus = -1;
        $this->loadApplications();
        $this->resetPage();
    }

    public function loadJobs()
    {
        $this->jobs = Job::with('employer')
            ->where('is_active', 1)
            ->orderBy('title')
            ->get()
            ->map(function ($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'employer' => $job->employer->name ?? 'Unknown',
                ];
            });
    }

    public function loadApplications()
    {
        if (!$this->selectedJobId) {
            $this->applications = collect();
            $this->statusCounts = [];
            return;
        }

        $applications = JobApplication::with(['candidate.user', 'job'])
            ->where('job_id', $this->selectedJobId)
            ->get();

        $this->applications = $applications;
        $this->updateStatusCounts($applications);
    }

    public function updateStatusCounts($applications)
    {
        $this->statusCounts = [];
        foreach (JobApplicationStatus::cases() as $status) {
            $this->statusCounts[$status->value] = $applications->where('status', $status->value)->count();
        }
    }

    public function selectJob($jobId)
    {
        $this->selectedJobId = $jobId;
        $this->selectedStatus = -1;
        $this->loadApplications();
        $this->resetPage();
    }

    public function selectStatus($status)
    {
        $this->selectedStatus = $status;
        $this->resetPage();
    }

    public function updateApplicationStatus($applicationId, $newStatus)
    {
        // Ensure status is int (in case from dropdown it's a string)
        $application = JobApplication::with(['candidate', 'job.employer'])->findOrFail($applicationId);
        $oldStatus = $application->status;
        $newStatusInt = (int)$newStatus;
        
        // Only log if status actually changed
        if ($oldStatus !== $newStatusInt) {
            $application->update(['status' => $newStatusInt]);
            
            // Log activity for the candidate
            ActivityService::applicationStatusChanged(
                $application->candidate,
                $application,
                $oldStatus,
                $newStatusInt,
                auth()->id()
            );
        }
        
        $this->loadApplications();
        
        $this->dispatch('status-updated', [
            'message' => 'Application status updated successfully!',
            'applicationId' => $applicationId,
            'newStatus' => $newStatus
        ]);
    }

    public function getFilteredApplications()
    {
        if (!$this->selectedJobId) {
            return collect();
        }

        $applications = $this->applications;
        
        // -1 means 'All' statuses
        if ($this->selectedStatus != -1) {
            $applications = $applications->where('status', $this->selectedStatus);
        }

        return $applications;
    }

    public function getSelectedJobProperty()
    {
        if (!$this->selectedJobId) {
            return null;
        }

        return Job::with(['employer', 'createdBy'])->find($this->selectedJobId);
    }

    public function render()
    {
        return view('livewire.job-pipeline');
    }
}
