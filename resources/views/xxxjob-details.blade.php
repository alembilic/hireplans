{{-- this was rendered directly from a livewire template --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                @livewire('pages.jobs.job-details', ['id' => $job->id])

                <div class="mt-5 text-center">
                    <a href="{{ route('jobs.listings') }}" class="bg-blue-500 text-white p-2 rounded mt-4 lg:mt-0 ml-0 lg:ml-6">Back to job listing</a>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
