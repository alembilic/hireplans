<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Job;
use App\Helpers\HelperFunc;

class HomePage extends Component
{
    public $searchQuery = '';
    public $location = '';
    public $workType = '';

    public function searchJobs()
    {
        $params = [];
        
        if (!empty($this->searchQuery)) {
            $params['search'] = $this->searchQuery;
        }
        
        if (!empty($this->location)) {
            $params['location'] = $this->location;
        }
        
        if (!empty($this->workType)) {
            $params['type'] = $this->workType;
        }
        
        $queryString = http_build_query($params);
        
        return redirect()->to('/jobs/listings' . ($queryString ? '?' . $queryString : ''));
    }

    public function getFeaturedJobs()
    {
        $jobTypes = HelperFunc::getJobTypes();
        $experienceLevels = HelperFunc::getExperienceLevels();
        
        return Job::with('employer')
            ->where('is_active', true)
            ->latest()
            ->limit(6)
            ->get()
            ->map(function ($job) use ($jobTypes, $experienceLevels) {
                $tags = [];
                
                // Add job type if available
                if ($job->job_type) {
                    $tags[] = $jobTypes[$job->job_type] ?? $job->job_type;
                }
                
                // Add experience level if available
                if ($job->experience_level) {
                    $tags[] = $experienceLevels[$job->experience_level] ?? $job->experience_level;
                }
                
                // If no tags, add default
                if (empty($tags)) {
                    $tags = ['Full-time', 'Mid Level'];
                }
                
                return [
                    'title' => $job->title,
                    'company' => $job->employer->name ?? 'Company Name',
                    'location' => $job->location,
                    'salary' => $job->salary,
                    'tags' => $tags,
                    'link' => route('jobs.details', $job->id)
                ];
            });
    }

    public function getJobTypesWithCounts()
    {
        $jobTypes = HelperFunc::getJobTypes();
        $typesWithCounts = [];
        
        foreach ($jobTypes as $key => $label) {
            $count = Job::where('is_active', true)
                ->where('job_type', $key)
                ->count();
            
            $typesWithCounts[] = [
                'name' => $label,
                'count' => $count . ' ' . ($count == 1 ? 'job' : 'jobs'),
                'link' => route('jobs.listings', ['type' => $key])
            ];
        }
        
        return $typesWithCounts;
    }

    public function getTopLocations()
    {
        // Get top 8 locations with job counts
        $locations = Job::where('is_active', true)
            ->selectRaw('location, COUNT(*) as job_count')
            ->groupBy('location')
            ->orderBy('job_count', 'desc')
            ->limit(8)
            ->get();
        
        return $locations->map(function ($location) {
            return [
                'name' => $location->location,
                'count' => $location->job_count . ' ' . ($location->job_count == 1 ? 'job' : 'jobs'),
                'link' => route('jobs.listings', ['location' => $location->location])
            ];
        });
    }

    public function render()
    {
        return view('livewire.home-page', [
            'featuredJobs' => $this->getFeaturedJobs(),
            'jobTypes' => $this->getJobTypesWithCounts(),
            'locations' => $this->getTopLocations()
        ])->layout('layouts.home-layout');
    }
}
