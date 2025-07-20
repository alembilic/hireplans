<?php

namespace App\Livewire\Pages\Jobs;

use Livewire\Component;
use App\Models\Job;
use App\Helpers\HelperFunc;

class JobDetails extends Component
{
    public $job;

    public function mount($id)
    {
        // $this->job = Job::findOrFail($id);

        $job = Job::findOrFail($id);

        $jobTypes = HelperFunc::getJobTypes();
        $job->job_type = $jobTypes[$job->job_type] ?? $job->job_type;

        $experienceLevels = HelperFunc::getExperienceLevels();
        $job->experience_level = $experienceLevels[$job->experience_level] ?? $job->experience_level;

        $categories = HelperFunc::getJobCategories();
        $job->category = $categories[$job->category] ?? $job->category;

        $this->job = $job;
    }

    public function render()
    {
        return view('livewire.pages.jobs.job-details', ['job' => $this->job])
            ->layout('layouts.app');
    }
}
