<?php

namespace App\Livewire\Pages\Jobs;

use Livewire\Component;
use App\Models\Job;
use Livewire\WithPagination;

class JobList extends Component
{
    use WithPagination;

    public $search = '';
    public $location = '';
    public $job_type = '';
    // public $jobs = [];
    public $jobTypes = [];
    public $page;

    public function mount()
    {
        // $this->jobs = Job::with('employer')->where('is_active', 1)->paginate(10);
        $this->jobTypes = $this->getJobTypes();
        // $this->currentPage = request()->query('page', 1);
        $this->page = session('page', 1); // Retrieve the page number from the session, default to 1
    }

    public function resetFilters()
    {
        $this->reset(['search', 'location', 'job_type']);
        $this->resetPage();
        // $this->mount(); // Re-fetch all jobs
    }

    public function updatingLocation()
    {
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingJobType()
    {
        $this->resetPage();
    }

    public function updatingPage($page)
    {
        session(['page' => $page]); // Store the current page number in the session
    }

    public function getJobTypes()
    {
        return Job::select('job_type')->distinct()->pluck('job_type');
    }

    public function searchJobs()
    {
        $this->resetPage();

        // $this->jobs = Job::with('employer')
        //     ->when($this->search, function($query) {
        //         $query->where(function ($query) {
        //             $query->where('title', 'like', '%' . $this->search . '%')
        //                 ->orWhere('details', 'like', '%' . $this->search . '%')
        //                 ->orWhere('category', 'like', '%' . $this->search . '%')
        //                 ->orWhere('job_ref', 'like', '%' . $this->search . '%');
        //         });
        //     })
        //     ->when($this->location, function($query) {
        //         $query->where('location', 'like', '%' . $this->location . '%');
        //     })
        //     ->when($this->job_type, function($query) {
        //         $query->where('job_type', $this->job_type);
        //     })
        //     ->where('is_active', 1)
        //     ->paginate(10);
    }

    public function render()
    {
        // return view('livewire.pages.jobs.job-list', ['jobs' => $this->jobs])
        //     ->layout('layouts.app');

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
            ->paginate(10);

        return view('livewire.pages.jobs.job-list', ['jobs' => $this->jobs])
            ->layout('layouts.app');
    }
}

