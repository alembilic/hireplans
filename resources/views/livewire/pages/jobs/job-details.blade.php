<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Job Details') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            @include('livewire.pages.jobs.job-details-content', ['job' => $job])

            <div class="mt-5 text-center">
                @if (!auth()->check())
                    <a href="{{ route('login') }}" class="bg-yellow-600 text-white p-2 px-4 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">Login/Register to apply</a>
                @elseif ($job->candidateProfileRequired())
                    <a href="{{ route('platform.profile') }}" class="bg-yellow-600 text-white p-2 px-4 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">Complete your profile to apply</a>
                @elseif ($job->canApply())
                    <a href="{{ route('platform.job_application.create', ['job' => $job]) }}" target="_blank" class="bg-yellow-600 text-white p-2 px-4 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">Apply</a>
                @endif
                <a href="{{ route('jobs.listings', ['page' => session('page', 1)]) }}" class="bg-gray-500 text-white p-2 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">Back to job listing</a>
            </div>
        </div>
    </div>
</div>

