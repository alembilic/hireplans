<div>
    <div class="search-bar mb-4 flex flex-col sm:flex-row items-stretch sm:items-center">
        <input type="text" wire:model.defer="search" wire:keydown.enter="searchJobs" placeholder="Search jobs..." class="border p-2 rounded mb-2 sm:mb-0 sm:mr-2 md:w-1/3">
        <input type="text" wire:model.defer="location" wire:keydown.enter="searchJobs" placeholder="Location..." class="border p-2 rounded mb-2 sm:mb-0 sm:mr-2  md:w-1/4">
        <select wire:model.defer="job_type" wire:change="searchJobs" class="border py-2 rounded mb-2 sm:mb-0 sm:mr-2">
            <option value="">All Types</option>
            @foreach($jobTypes as $jobType)
                <option value="{{ $jobType }}">{{ $jobType }}</option>
            @endforeach
        </select>
        <button wire:click="searchJobs" class="bg-blue-500 text-white p-2 rounded mb-2 sm:mb-0 sm:mr-2">Search</button>
        <button wire:click="resetFilters" class="bg-red-500 text-white p-2 rounded">Reset All</button>
    </div>

    <div class="job-listings grid grid-cols-1 gap-4">
        @foreach($jobs as $job)
            <div class="job-item border p-6 rounded shadow flex flex-col lg:flex-row items-center">
                <img src="{{ $job->employer->logo }}" alt="Company Logo" class="w-20 h-20 mr-0 lg:mr-6 mb-4 lg:mb-0"> <!-- Replace with actual image source -->
                <div class="flex-1 text-center lg:text-left">
                    <h3 class="text-2xl font-bold text-blue-600">{{ $job->title }}</h3>
                    <p class="text-gray-600">{{ $job->employer->name }}</p>
                    <div class="flex flex-col lg:flex-row items-center lg:items-center text-gray-600 mt-2">
                        <div class="flex items-center mr-4 mb-2 lg:mb-0">
                            <span><i class="bi bi-geo-alt-fill text-blue-600"></i><span class="font-bold ml-1">Location:</span> {{ $job->location }}</span>
                        </div>
                        <div class="flex items-center mr-4 mb-2 lg:mb-0">
                            <span><i class="bi bi-cash-coin text-blue-600"></i><span class="font-bold ml-1">Salary:</span> {{ $job->salary }}</span>
                        </div>
                        <div class="flex items-center mr-4 mb-2 lg:mb-0">
                            <span><i class="bi bi-calendar-event text-blue-600"></i><span class="font-bold ml-1">Posted on:</span> {{ $job->created_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
                <a href="{{ route('jobs.details', $job->id) }}" class="bg-blue-500 text-white p-2 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">View job details</a>
            </div>
        @endforeach

        {{ $jobs->links() }}
    </div>
</div>
