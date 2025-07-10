<div class="mt-6">
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div x-data="{ open: false }">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 border-b border-gray-200 gap-4">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900">Job Pipeline</h2>
                    @if($selectedJobId && $this->selectedJob)
                        <a href="{{ route('platform.jobs.view', $this->selectedJob->id) }}"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md hover:bg-yellow-700 hover:text-white transition-colors duration-200">
                            <i class="bi bi-eye mr-1.5"></i>
                            View Job
                        </a>
                        <button type="button" @click="open = !open"
                            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-200">
                            <span x-text="open ? 'Hide Details' : 'Show Details'"></span>
                            <i class="bi bi-chevron-down ml-1.5 transition-transform duration-200"
                                :class="{ 'rotate-180': open }"></i>
                        </button>
                    @endif
                </div>
                <div class="w-full md:w-1/2">
                    <select id="jobSelect" wire:model.live="selectedJobId"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Choose a job...</option>
                        @foreach($jobs as $job)
                            <option value="{{ $job['id'] }}">{{ $job['title'] }} - {{ $job['employer'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Job Details Section -->
            @if($selectedJobId && $this->selectedJob)
                <div class="bg-white shadow-sm">
                    <div class="px-6 py-4" x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform -translate-y-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Job Title and Employer -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Job Title</h4>
                                <p class="text-base font-semibold text-gray-900">{{ $this->selectedJob->title }}</p>
                            </div>

                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Employer</h4>
                                <p class="text-base text-gray-900">{{ $this->selectedJob->employer->name }}</p>
                            </div>

                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</h4>
                                <p class="text-base text-gray-900">{{ $this->selectedJob->location ?? 'Not specified' }}</p>
                            </div>

                            <!-- Recruiter -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Recruiter</h4>
                                <p class="text-base text-gray-900">{{ $this->selectedJob->createdBy->name ?? 'Not specified' }}</p>
                            </div>

                            <!-- Salary Range -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Salary Range</h4>
                                <p class="text-base text-gray-900">{{ $this->selectedJob->salary ?? 'Not specified' }}</p>

                            </div>

                            <!-- Job Type -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Job Type</h4>
                                <p class="text-base text-gray-900">
                                    {{ \App\Helpers\HelperFunc::getJobTypes()[$this->selectedJob->job_type] ?? $this->selectedJob->job_type ?? 'Not specified' }}
                                </p>
                            </div>

                            <!-- Experience Level -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Experience Level</h4>
                                <p class="text-base text-gray-900">
                                    {{ \App\Helpers\HelperFunc::getExperienceLevels()[$this->selectedJob->experience_level] ?? $this->selectedJob->experience_level ?? 'Not specified' }}
                                </p>
                            </div>

                            <!-- Created Date -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Posted Date</h4>
                                <p class="text-base text-gray-900">{{ $this->selectedJob->created_at->format('M d, Y') }}
                                </p>
                            </div>

                            <!-- Application Deadline -->
                            <div class="space-y-2">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide">Application Deadline
                                </h4>
                                <p class="text-base text-gray-900">
                                    {{ $this->selectedJob->application_deadline ? $this->selectedJob->application_deadline->format('M d, Y') : 'Not specified' }}
                                </p>
                            </div>
                        </div>

                        <!-- Job Description -->
                        @if($this->selectedJob->description)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Job Description</h4>
                                <div class="prose prose-sm max-w-none text-gray-700">
                                    {!! nl2br(e($this->selectedJob->description)) !!}
                                </div>
                            </div>
                        @endif

                        <!-- Requirements -->
                        @if($this->selectedJob->requirements)
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-3">Requirements</h4>
                                <div class="prose prose-sm max-w-none text-gray-700">
                                    {!! nl2br(e($this->selectedJob->requirements)) !!}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if($selectedJobId)
                <!-- Status Tabs -->
                <div class="border-b border-gray-200 bg-gray-50 px-6">
                    <nav class="-mb-px flex flex-wrap space-x-4 overflow-x-auto" aria-label="Tabs">
                        <!-- All Tab -->
                        <button wire:click="selectStatus(-1)"
                            class="whitespace-nowrap py-3 px-2 border-b-2 font-medium text-sm transition-colors {{ $selectedStatus == -1 ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            All
                            <span
                                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedStatus == -1 ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-800' }}">
                                {{ $this->applications->count() }}
                            </span>
                        </button>
                        @foreach(\App\Enums\JobApplicationStatus::cases() as $status)
                            <button wire:click="selectStatus({{ $status->value }})"
                                class="whitespace-nowrap py-3 px-2 border-b-2 font-medium text-sm transition-colors {{ $selectedStatus == $status->value ? 'border-brand text-brand' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                {{ $status->label() }}
                                <span
                                    class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $selectedStatus == $status->value ? 'bg-brand/10 text-brand' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusCounts[$status->value] ?? 0 }}
                                </span>
                            </button>
                        @endforeach
                    </nav>
                </div>

                <!-- Applications Table -->
                <div class="px-0 py-0">
                    <div class="px-6 pt-4 pb-2">
                        <h3 class="text-base font-semibold text-gray-900">
                            @if($selectedStatus != -1)
                                {{ \App\Enums\JobApplicationStatus::fromValue($selectedStatus)?->label() ?? 'Unknown' }}
                                Applications
                            @else
                                All Applications
                            @endif
                            ({{ $this->getFilteredApplications()->count() }})
                        </h3>
                    </div>
                    <div class="overflow-x-auto">
                        @if($this->getFilteredApplications()->count() > 0)
                            <table class="min-w-full w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Candidate</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Languages</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Skills</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Applied Date</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status</th>
                                        <th
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($this->getFilteredApplications() as $application)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <a href="{{ route('platform.candidates.view', $application->candidate->id) }}"
                                                    class="flex items-center inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 rounded-md hover:bg-gray-200 transition-colors duration-200"
                                                    title="View Candidate Profile">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div
                                                            class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ strtoupper(substr($application->candidate->user->name, 0, 2)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $application->candidate->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $application->candidate->user->email }}
                                                        </div>
                                                    </div>
                                                </a>

                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $application->candidate->languages }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-900">
                                                {{ $application->candidate->skills }}
                                            </td>
                                            <td class="px-3 py-2 text-sm text-gray-500">
                                                {{ $application->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap">
                                                <!-- Status Dropdown -->
                                                <select
                                                    wire:change="updateApplicationStatus({{ $application->id }}, $event.target.value)"
                                                    class="pl-2 pr-7 py-1 border border-gray-300 rounded-md text-xs focus:outline-none focus:ring-brand focus:border-brand">
                                                    @foreach(\App\Enums\JobApplicationStatus::cases() as $statusOption)
                                                        <option value="{{ $statusOption->value }}"
                                                            @if($application->status == $statusOption->value) selected @endif>
                                                            {{ $statusOption->label() }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td class="px-3 py-2 whitespace-nowrap text-sm font-medium">
                                                <div class="flex items-center space-x-3">
                                                    <a href="{{ route('platform.job_application.view', $application->id) }}"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200"
                                                        title="View Application Details">
                                                        <i class="bi bi-file-earmark-text mr-1.5 text-base"></i>
                                                        Application
                                                    </a>
                                                    <a href="{{ $application->getCv()?->path ?? '#' }}" target="_blank"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200"
                                                        title="View CV">
                                                        <i class="bi bi-file-earmark-person mr-1.5 text-base"></i>
                                                        CV
                                                    </a>
                                                    <a href="{{ $application->getCoverLetter()?->path ?? '#' }}" target="_blank"
                                                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors duration-200"
                                                        title="View Cover Letter">
                                                        <i class="bi bi-envelope-paper mr-1.5 text-base"></i>
                                                        Cover Letter
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No applications</h3>
                                    <p class="mt-1 text-sm text-gray-500">No applications found in this status.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <div class="text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-square-mouse-pointer-icon lucide-square-mouse-pointer">
                            <path
                                d="M12.034 12.681a.498.498 0 0 1 .647-.647l9 3.5a.5.5 0 0 1-.033.943l-3.444 1.068a1 1 0 0 0-.66.66l-1.067 3.443a.5.5 0 0 1-.943.033z" />
                            <path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Select a job</h3>
                        <p class="mt-1 text-sm text-gray-500">Choose a job from the dropdown above to view its applications.
                        </p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Success Notification -->
        <div x-data="{ show: false, message: '' }" x-show="show" x-cloak @status-updated.window="
                 show = true;
                 message = $event.detail.message;
                 setTimeout(() => show = false, 3000);
             " class="fixed top-4 right-4 z-50">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <span x-text="message"></span>
            </div>
        </div>
    </div>