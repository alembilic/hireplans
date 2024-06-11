<?php

namespace App\Livewire\Pages\Jobs;

use Livewire\Component;
use App\Models\Job;

class JobList extends Component
{
    public $search = '';
    public $location = '';
    public $job_type = '';
    public $jobs = [];
    public $jobTypes = [];

    public function mount()
    {
        $this->jobs = Job::with('employer')->where('is_active', 1)->get();
        $this->jobTypes = $this->getJobTypes();
    }

    public function resetFilters()
    {
        $this->reset(['search', 'location', 'job_type']);
        $this->mount(); // Re-fetch all jobs
    }

    public function getJobTypes()
    {
        return Job::select('job_type')->distinct()->pluck('job_type');
    }

    public function searchJobs()
    {
        $this->jobs = Job::with('employer')
            ->when($this->search, function($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('details', 'like', '%' . $this->search . '%')
                        ->orWhere('category', 'like', '%' . $this->search . '%')
                        ->orWhere('job_ref', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->location, function($query) {
                $query->where('location', 'like', '%' . $this->location . '%');
            })
            ->when($this->job_type, function($query) {
                $query->where('job_type', $this->job_type);
            })
            ->where('is_active', 1)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.jobs.job-list', ['jobs' => $this->jobs])
            ->layout('layouts.app');
    }
}

