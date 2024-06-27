<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use App\Models\Candidate;
use App\Models\Employer;
use App\Models\Job;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\User;

class PlatformScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        $metrics = [];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $metrics = [
                'users'     => User::count(),
                'candidates'  => Candidate::count(),
                'employers'    => Employer::count(),
                'jobs'     => Job::count(),
                'applications'     => JobApplication::count(),
            ];
        // } else if (Auth::user()->hasRole('employer')) {
        //     $employer = Employer::where('user_id', Auth::id())->first();
        //     $metrics[] = Layout::metrics([
        //         'Total jobs'    => Job::where('employer_id', $employer->id)->count(),
        //         'Job Applications' => JobApplication::where('employer_id', $employer->id)->count(),
        //     ]);
        } else if (Auth::user()->hasAccess('job.apply')) {
            $candidate = Candidate::where('user_id', Auth::id())->first();
            $metrics = [
                'jobs'     => Job::count(),
                'myApplications' => JobApplication::where('candidate_id', $candidate->id)->count(),
                'myReferences' => $candidate->references->count(),
            ];
        }

        return [
            'metrics' => $metrics,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     */
    public function name(): ?string
    {
        return 'Welcome to Hire Plans';
    }

    /**
     * Display header description.
     */
    public function description(): ?string
    {
        return 'Welcome to your Hire Plans dashboard.';
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
     * @return \Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        $output = [];

        if (Auth::user()->hasAccess('platform.systems.users')) {
            $output[] = Layout::metrics([
                'Total users'    => 'metrics.users',
                'Candidates' => 'metrics.candidates',
                'Employers' => 'metrics.employers',
                'Jobs' => 'metrics.jobs',
                'Job Applications' => 'metrics.applications',
            ]);

            $output[] = Layout::columns([
                Layout::view('partials.dashboard-latest-jobs', [
                    'role' => 'admin',
                    'title' => 'Latest Jobs',
                    'jobs' => Job::latest()->limit(5)->get(),
                ]),
                Layout::view('partials.dashboard-latest-applications', [
                    'role' => 'admin',
                    'title' => 'Latest Applications',
                    'applications' => JobApplication::latest()->limit(5)->get(),
                ]),
            ]);

        } else if (Auth::user()->hasAccess('job.apply')) {
            $candidate = Candidate::where('user_id', Auth::id())->first() ?? new Candidate();
            $candidate_id = $candidate->id ?? 0;

            $output[] = Layout::metrics([
                'Total jobs'    => 'metrics.jobs',
                'My Applications' => 'metrics.myApplications',
                'My References' => 'metrics.myReferences',
            ]);

            $output[] = Layout::columns([
                Layout::view('partials.dashboard-latest-jobs', [
                    'role' => 'candidate',
                    'title' => 'Latest Jobs',
                    'jobs' => Job::latest()->limit(5)->get(),
                ]),
                Layout::view('partials.dashboard-latest-applications', [
                    'role' => 'candidate',
                    'title' => 'My Latest Applications',
                    'applications' => JobApplication::latest()->where('candidate_id', $candidate_id)->limit(5)->get(),
                ]),
            ]);
        }

        return $output;
    }
}
