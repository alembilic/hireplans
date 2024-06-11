<?php

namespace App\Livewire\Pages\Jobs;

use Livewire\Component;
use App\Models\Job;

class JobDetails extends Component
{
    public $job;

    public function mount($id)
    {
        $this->job = Job::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.pages.jobs.job-details', ['job' => $this->job])
            ->layout('layouts.app');
    }
}
