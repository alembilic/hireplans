<div>
    <h1 class="text-3xl font-bold mb-5 lg:mb-5">{{ $job->title }}</h1>
    <div class="flex items-center mr-4 mb-5 lg:mb-5 text-gray-600">
        <img src="{{ $job->employer->logo }}" alt="Company Logo" class="w-10 h-10 mr-3">
        <span class="text-gray-600 font-bold">{{ $job->employer->name }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <span><i class="bi bi-geo-alt-fill text-blue-600 mr-2"></i><span class="text-gray-600 font-bold ml-1">Location:</span> {{ $job->location }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <span><i class="bi bi-cash-coin text-blue-600 mr-2"></i><span class="text-gray-600 font-bold ml-1">Salary:</span> {{ $job->salary }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <span><i class="bi bi-calendar-event text-blue-600 mr-2"></i><span class="text-gray-600 font-bold ml-1">Job type:</span> {{ $job->job_type }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <span><i class="bi bi-calendar-event text-blue-600 mr-2"></i><span class="text-gray-600 font-bold ml-1">Experience level:</span> {{ $job->experience_level }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <span><i class="bi bi-calendar-event text-blue-600 mr-2"></i><span class="text-gray-600 font-bold ml-1">Posted on:</span> {{ $job->created_at->format('d M Y') }}</span>
    </div>
    <div class="flex mr-4 mb-3 lg:mb-3">
        <p class="text-gray-800">{{ $job->details }}</p>
    </div>
</div>
