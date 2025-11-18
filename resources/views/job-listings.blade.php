<x-home-layout title="Job Listings">
    <div class="py-8" style="background-color: #FAFAFA; min-height: calc(100vh - 73px);">
        <div class="container">
            <!-- Page Header -->
            <div class="text-center mb-8">
                <h1 class="fw-bold" style="font-size: 2.5rem; color: #0A0A0A; margin-bottom: 1rem; line-height: 1.2;">
                    Find Your Perfect Job
                </h1>
                <p style="font-size: 1.125rem; color: #4B4B4B; max-width: 600px; margin: 0 auto;">
                    Discover exciting career opportunities that match your skills and aspirations
                </p>
            </div>
            
            @livewire('pages.jobs.job-list')
        </div>
    </div>
</x-home-layout>
