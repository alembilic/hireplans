@push('styles')
<style>
        .activity-link {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: rgba(248, 249, 250, 0.8);
    border: 1px solid rgba(206, 212, 218, 0.6);
    border-radius: 0.375rem;
    color: #495057;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease-in-out;
    backdrop-filter: blur(10px);
}

.activity-link:hover {
    background: rgba(255, 255, 255, 0.95);
    border-color: rgba(206, 212, 218, 0.8);
    color: #212529;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.activity-link i {
    font-size: 0.875rem;
    opacity: 0.8;
    flex-shrink: 0;
}

.activity-link-job:hover {
    border-color: rgba(40, 167, 69, 0.5);
    background: rgba(40, 167, 69, 0.05);
}

.activity-link-job:hover i {
    color: #28a745;
    opacity: 1;
}

.activity-link-application:hover {
    border-color: rgba(23, 162, 184, 0.5);
    background: rgba(23, 162, 184, 0.05);
}

.activity-link-application:hover i {
    color: #17a2b8;
    opacity: 1;
}

.activity-link span {
    font-weight: 500;
    letter-spacing: 0.01em;
}

.btn-add-note {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
}

.btn-add-note:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    color: white;
}

.btn-add-note:active {
    transform: translateY(0);
    box-shadow: 0 2px 6px rgba(102, 126, 234, 0.3);
}
</style>
@endpush

<div class="bg-white mt-6 rounded-lg shadow-sm border border-gray-200">
    <!-- Main Content Area -->
    <div class="row g-4 p-4">
        
        <!-- Left Sidebar (1/3) -->
        <div class="col-lg-3 col-md-4">
            
            <!-- Profile Image Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center py-4">
                    <div class="position-relative d-inline-block mb-3">
                        <img src="{{ $candidate->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($candidate->user->name) . '&color=7F9CF5&background=EBF4FF&size=150' }}" 
                             alt="{{ $candidate->user->name }}" 
                             class="rounded-circle shadow-sm"
                             style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                        @if($candidate->user->email_verified_at)
                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle d-flex align-items-center justify-content-center" 
                              style="width: 30px; height: 30px; border: 3px solid white;">
                            <i class="bi bi-check text-white fw-bold"></i>
                        </span>
                        @endif
                    </div>
                    <div class="fw-bold fs-5 text-dark mb-1">{{ $candidate->user->name }}</div>
                    @if($candidate->current_job_title)
                    <div class="text-muted mb-1">{{ $candidate->current_job_title }}</div>
                    @endif
                    @if($candidate->current_company)
                    <div class="text-muted small">{{ $candidate->current_company }}</div>
                    @endif
                </div>
            </div>
            
            <!-- Unified Profile Card -->
            <div class="card shadow-sm">
                <!-- Contact Information Section -->
                <div class="card-body border-bottom">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        Contact Information
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope text-muted me-3"></i>
                            <a href="mailto:{{ $candidate->user->email }}" class="text-decoration-none text-primary small">
                                {{ $candidate->user->email ?? 'Not provided' }}
                            </a>
                        </div>
                        @if($candidate->user->phone)
                        <div class="d-flex align-items-center">
                            <i class="bi bi-telephone text-muted me-3"></i>
                            <a href="tel:{{ $candidate->user->phone }}" class="text-decoration-none text-primary small">
                                {{ $candidate->user->phone }}
                            </a>
                        </div>
                        @endif
                        @if($candidate->user->address_line_1 || $candidate->user->city || $candidate->user->country)
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt text-muted me-3 mt-1"></i>
                            <div class="small text-muted">
                                @if($candidate->user->address_line_1)
                                    <div>{{ $candidate->user->address_line_1 }}</div>
                                @endif
                                @if($candidate->user->city)
                                    <div>{{ $candidate->user->city }}@if($candidate->user->postcode), {{ $candidate->user->postcode }}@endif</div>
                                @endif
                                @if($candidate->user->country)
                                    <div>{{ $candidate->user->country }}</div>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        Basic Information
                    </div>
                    <div class="d-flex flex-column gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="small fw-medium text-muted">Reference:</span>
                            <span class="small text-dark">{{ $candidate->candidate_ref ?? 'N/A' }}</span>
                        </div>
                        @if($candidate->gender)
                        <div class="d-flex justify-content-between">
                            <span class="small fw-medium text-muted">Gender:</span>
                            <span class="small text-dark">{{ $candidate->gender }}</span>
                        </div>
                        @endif
                        @if($candidate->user->nationality)
                        <div class="d-flex justify-content-between">
                            <span class="small fw-medium text-muted">Nationality:</span>
                            <span class="small text-dark">{{ $candidate->user->nationality }}</span>
                        </div>
                        @endif
                        @if($candidate->user->dob)
                        <div class="d-flex justify-content-between">
                            <span class="small fw-medium text-muted">Date of Birth:</span>
                            <span class="small text-dark">{{ \Carbon\Carbon::parse($candidate->user->dob)->format('M d, Y') }}</span>
                        </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <span class="small fw-medium text-muted">Email Verified:</span>
                            <span class="small">
                                @if($candidate->user->email_verified_at)
                                    <span class="badge bg-success-subtle text-success-emphasis">
                                        <i class="bi bi-check-circle me-1"></i>
                                        Verified
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger-emphasis">
                                        <i class="bi bi-x-circle me-1"></i>
                                        Not Verified
                                    </span>
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Activity Toggle Button -->
            <div class="mt-3">
                <button type="button" 
                        class="btn btn-primary w-100 d-flex align-items-center justify-content-center" 
                        wire:click="toggleActivity">
                    <i class="bi {{ $showActivity ? 'bi-arrow-left' : 'bi-activity' }} me-2"></i>
                    {{ $showActivity ? 'Back to Profile' : 'Show Activity' }}
                </button>
            </div>

        </div>

        <!-- Right Content Area (2/3) -->
        <div class="col-lg-9 col-md-8">
            
            @if($showActivity)
                <!-- Activity View -->
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="fw-bold fs-5 d-flex align-items-center mb-0">
                                <i class="bi bi-activity text-primary me-2"></i>
                                Activity Log
                            </div>
                            <button type="button" 
                                    class="btn btn-primary btn-sm" 
                                    wire:click="toggleNoteInput">
                                <i class="bi bi-plus me-1"></i>
                                Add Note
                            </button>
                        </div>
                        
                        <!-- Add Note Input -->
                        @if($showNoteInput)
                        <div class="bg-light rounded-lg p-4 mb-5 shadow-sm">
                            <div class="fw-bold fs-5 text-dark mb-3 d-flex align-items-center">
                                <i class="bi bi-chat-text text-primary me-2"></i>
                                Add Activity Note
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control form-control-xl border-0 shadow-sm" 
                                          rows="4" 
                                          placeholder="Enter your note here..." 
                                          wire:model="newNote"
                                          wire:keydown.ctrl.enter="addNote"
                                          style="resize: vertical; min-height: 160px; font-size: 1rem; min-width: 100%;"></textarea>
                            </div>
                            <div class="d-flex gap-3 align-items-center">
                                <button class="btn btn-primary px-4 py-2" 
                                        type="button" 
                                        wire:click="addNote">
                                    <i class="bi bi-check-lg me-2"></i>
                                    Add Note
                                </button>
                                <button class="btn btn-light px-4 py-2" 
                                        type="button" 
                                        wire:click="toggleNoteInput">
                                    <i class="bi bi-x-lg me-2"></i>
                                    Cancel
                                </button>
                                <small class="text-muted ms-auto">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Press Ctrl+Enter to save quickly
                                </small>
                            </div>
                            @error('newNote') <div class="text-danger mt-2"><i class="bi bi-exclamation-triangle me-1"></i>{{ $message }}</div> @enderror
                        </div>
                        @endif
                        
                        <!-- Activity Timeline -->
                        @if($activities && $activities->count() > 0)
                        <div class="activity-timeline">
                            @foreach($activities as $activity)
                            <div class="d-flex mb-4">
                                <!-- Activity Icon -->
                                <div class="flex-shrink-0 me-3">
                                    <div class="bg-{{ $activity->color }}-subtle text-{{ $activity->color }}-emphasis rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi {{ $activity->icon }}"></i>
                                    </div>
                                </div>
                                
                                <!-- Activity Content -->
                                <div class="flex-grow-1">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body py-3">
                                             <div class="fw-bold fs-6 text-dark mb-1">{{ $activity->title }}</div>
                                            @if($activity->description)
                                            <p class="card-text mb-2" style="white-space: pre-wrap;">{{ $activity->description }}</p>
                                            @endif
                                            
                                            <!-- Activity Metadata Links -->
                                            @if($activity->metadata)
                                                @if(isset($activity->metadata['job_id']) && isset($activity->metadata['job_title']))
                                                <div class="mb-2">
                                                    <a href="{{ route('platform.jobs.view', $activity->metadata['job_id']) }}" 
                                                       class="activity-link activity-link-job">
                                                        <i class="bi bi-briefcase"></i>
                                                        <span>View Job: {{ $activity->metadata['job_title'] }}</span>
                                                    </a>
                                                </div>
                                                @endif
                                                
                                                @if(isset($activity->metadata['application_id']) && isset($activity->metadata['application_ref']))
                                                <div class="mb-2">
                                                    <a href="{{ route('platform.job-applications.view', $activity->metadata['application_id']) }}" 
                                                       class="activity-link activity-link-application">
                                                        <i class="bi bi-file-text"></i>
                                                        <span>View Application: {{ $activity->metadata['application_ref'] }}</span>
                                                    </a>
                                                </div>
                                                @endif
                                                
                                                @if(isset($activity->metadata['meeting_id']) && isset($activity->metadata['meeting_title']))
                                                <div class="mb-2">
                                                    <span class="badge bg-warning-subtle text-warning-emphasis">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $activity->metadata['meeting_title'] }}
                                                    </span>
                                                </div>
                                                @endif
                                            @endif
                                            
                                            <!-- Activity Footer -->
                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <small class="text-muted">
                                                    {{ $activity->created_at->format('M d, Y \a\t g:i A') }}
                                                    @if($activity->createdBy)
                                                        • by {{ $activity->createdBy->name }}
                                                    @endif
                                                </small>
                                                @if($activity->activity_type !== 'note_added')
                                                <small class="badge bg-secondary-subtle text-secondary-emphasis">
                                                    {{ \Illuminate\Support\Str::title(str_replace('_', ' ', $activity->activity_type)) }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-5">
                            <i class="bi bi-clock-history text-muted mb-3" style="font-size: 3rem;"></i>
                            <div class="fw-bold fs-6 text-muted">No Activities Yet</div>
                            <p class="text-muted small">
                                Activities will appear here as the candidate interacts with the system.
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            @else
                <!-- Normal Profile Content -->
            
            <!-- Skills Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-lightbulb text-primary me-2"></i>
                        Skills
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($this->skillsArray as $skill)
                            <span class="badge bg-primary-subtle text-primary-emphasis px-3 py-2 d-flex align-items-center">
                                {{ $skill }}
                                <button type="button" class="btn-close ms-2 text-black" style="font-size: 0.6rem;" 
                                        wire:click="removeSkill('{{ $skill }}')" 
                                        aria-label="Remove skill"></button>
                            </span>
                        @endforeach
                    </div>
                    
                    @if($showSkillInput)
                        <div class="input-group input-group-sm mb-3" style="max-width: 350px;">
                            <input type="text" class="form-control" 
                                   placeholder="Enter new skill" 
                                   wire:model="newSkill"
                                   wire:keydown.enter="addSkill"
                                   autofocus>
                            <button class="btn btn-success d-flex align-items-center justify-content-center" 
                                    type="button" 
                                    wire:click="addSkill"
                                    style="min-width: 45px;">
                                <i class="bi bi-check"></i>
                            </button>
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center ms-1" 
                                    type="button" 
                                    wire:click="toggleSkillInput"
                                    style="min-width: 45px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @error('newSkill') <div class="text-danger small">{{ $message }}</div> @enderror
                    @else
                        <button type="button" class="btn btn-outline-primary btn-sm px-3" wire:click="toggleSkillInput">
                            <i class="bi bi-plus me-1"></i>Add Skill
                        </button>
                    @endif
                </div>
            </div>

            <!-- Languages Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-translate text-primary me-2"></i>
                        Languages
                    </div>
                    <div class="d-flex flex-wrap gap-2 mb-3">
                        @foreach($this->languagesArray as $language)
                            <span class="badge bg-success-subtle text-success-emphasis px-3 py-2 d-flex align-items-center">
                                {{ $language }}
                                <button type="button" class="btn-close ms-2 text-black" style="font-size: 0.6rem;" 
                                        wire:click="removeLanguage('{{ $language }}')" 
                                        aria-label="Remove language"></button>
                            </span>
                        @endforeach
                    </div>
                    
                    @if($showLanguageInput)
                        <div class="input-group input-group-sm mb-3" style="max-width: 350px;">
                            <input type="text" class="form-control" 
                                   placeholder="Enter new language" 
                                   wire:model="newLanguage"
                                   wire:keydown.enter="addLanguage"
                                   autofocus>
                            <button class="btn btn-success d-flex align-items-center justify-content-center" 
                                    type="button" 
                                    wire:click="addLanguage"
                                    style="min-width: 45px;">
                                <i class="bi bi-check"></i>
                            </button>
                            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center ms-1" 
                                    type="button" 
                                    wire:click="toggleLanguageInput"
                                    style="min-width: 45px;">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                        @error('newLanguage') <div class="text-danger small">{{ $message }}</div> @enderror
                    @else
                        <button type="button" class="btn btn-outline-success btn-sm px-3" wire:click="toggleLanguageInput">
                            <i class="bi bi-plus me-1"></i>Add Language
                        </button>
                    @endif
                </div>
            </div>

            <!-- Job Applications Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-file-earmark-person text-primary me-2"></i>
                        Job Applications
                    </div>
                    
                    @if($candidate->jobApplications && $candidate->jobApplications->count() > 0)
                        <div class="row g-3">
                            @foreach($candidate->jobApplications->sortByDesc('created_at') as $application)
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div class="flex-grow-1">
                                            <div class="fw-medium text-dark mb-4">
                                                {{ $application->job->title ?? 'Job Title Not Available' }}
                                                <span class="text-muted small ms-3">
                                                    <i class="bi bi-building me-1"></i>
                                                    {{ $application->job->employer->name ?? 'Company Not Available' }}
                                                </span>
                                            </div>
                                        
                                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                                <span class="badge 
                                                    @if($application->status instanceof \App\Enums\JobApplicationStatus)
                                                        @switch($application->status)
                                                            @case(\App\Enums\JobApplicationStatus::APPLIED)
                                                                bg-info-subtle text-info-emphasis
                                                                @break
                                                            @case(\App\Enums\JobApplicationStatus::LONGLIST)
                                                            @case(\App\Enums\JobApplicationStatus::SHORTLIST)
                                                            @case(\App\Enums\JobApplicationStatus::SCREENING)
                                                                bg-warning-subtle text-warning-emphasis
                                                                @break
                                                            @case(\App\Enums\JobApplicationStatus::INTERVIEWING)
                                                                bg-primary-subtle text-primary-emphasis
                                                                @break
                                                            @case(\App\Enums\JobApplicationStatus::HIRED)
                                                                bg-success-subtle text-success-emphasis
                                                                @break
                                                            @case(\App\Enums\JobApplicationStatus::REJECTED)
                                                                bg-danger-subtle text-danger-emphasis
                                                                @break
                                                            @default
                                                                bg-secondary-subtle text-secondary-emphasis
                                                        @endswitch
                                                    @else
                                                        bg-secondary-subtle text-secondary-emphasis
                                                    @endif
                                                ">
                                                    <i class="bi 
                                                        @if($application->status instanceof \App\Enums\JobApplicationStatus)
                                                            @switch($application->status)
                                                                @case(\App\Enums\JobApplicationStatus::APPLIED)
                                                                    bi-send
                                                                    @break
                                                                @case(\App\Enums\JobApplicationStatus::LONGLIST)
                                                                @case(\App\Enums\JobApplicationStatus::SHORTLIST)
                                                                @case(\App\Enums\JobApplicationStatus::SCREENING)
                                                                    bi-eye
                                                                    @break
                                                                @case(\App\Enums\JobApplicationStatus::INTERVIEWING)
                                                                    bi-people
                                                                    @break
                                                                @case(\App\Enums\JobApplicationStatus::HIRED)
                                                                    bi-check-circle
                                                                    @break
                                                                @case(\App\Enums\JobApplicationStatus::REJECTED)
                                                                    bi-x-circle
                                                                    @break
                                                                @default
                                                                    bi-clock
                                                            @endswitch
                                                        @else
                                                            bi-clock
                                                        @endif
                                                    me-1"></i>
                                                    @if($application->status instanceof \App\Enums\JobApplicationStatus)
                                                        {{ $application->status->label() }}
                                                    @else
                                                        {{ ucfirst($application->status) }}
                                                    @endif
                                                </span>
                                                <small class="text-muted ms-2">
                                                    <i class="bi bi-calendar-event me-1"></i>
                                                    Applied {{ $application->created_at->format('M d, Y') }}
                                                </small>
                                                @if($application->job->location)
                                                <small class="text-muted ms-2">
                                                    <i class="bi bi-geo-alt me-1"></i>
                                                    {{ $application->job->location }}
                                                </small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($application->cover_letter)
                                    <div class="mt- pt-2 border-top">
                                        <small class="text-muted fw-medium ">Cover Letter:</small>
                                            @php
                                                $coverLetter = $application->getCoverLetter();
                                            @endphp
                                            @if($coverLetter)
                                                <a href="{{ $coverLetter->url }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm inline">
                                                    <i class="bi bi-file-earmark-text me-1"></i>
                                                    {{ $coverLetter->original_name ?? 'Cover Letter' }}
                                                    <i class="bi bi-download ms-1"></i>
                                                </a>
                                            @else
                                                <small class="text-muted">Cover letter file not found</small>
                                            @endif
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-file-earmark-x text-muted mb-3" style="font-size: 3rem;"></i>
                            <div class="fw-bold fs-6 text-muted mb-1">No Job Applications</div>
                            <p class="text-muted small mb-0">
                                This candidate hasn't applied to any jobs yet.
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Documents Section -->
            @if($cvLinks || $otherDocumentsLinks)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-file-earmark-text text-primary me-2"></i>
                        Documents
                    </div>
                    
                    @if($cvLinks)
                    <div class="mb-4">
                        <div class="fw-medium fs-6 mb-2">CV/Resume</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($cvLinks as $id => $link)
                            <div class="d-inline-flex align-items-center">
                                <i class="bi bi-file-pdf me-2 text-primary"></i>
                                {!! $link !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($otherDocumentsLinks)
                    <div>
                        <div class="fw-medium fs-6 mb-2">Other Documents</div>
                        <div class="d-flex flex-column gap-2">
                            @foreach($otherDocumentsLinks as $id => $link)
                            <div class="d-inline-flex align-items-center">
                                <i class="bi bi-file-earmark me-2 text-primary"></i>
                                {!! $link !!}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Notes Section -->
            @if($candidate->notes)
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-chat-text text-primary me-2"></i>
                        Notes
                    </div>
                    <p class="text-muted" style="white-space: pre-wrap;">{{ $candidate->notes }}</p>
                </div>
            </div>
            @endif

            <!-- System Information -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <div class="fw-bold fs-5 d-flex align-items-center mb-3">
                        <i class="bi bi-info-circle text-primary me-2"></i>
                        System Information
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="fw-medium text-muted small d-block">Profile Created:</span>
                            <span class="text-dark small">{{ \Carbon\Carbon::parse($candidate->user->created_at)->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                        <div class="col-md-6">
                            <span class="fw-medium text-muted small d-block">Last Updated:</span>
                            <span class="text-dark small">{{ \Carbon\Carbon::parse($candidate->user->updated_at)->format('M d, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            @endif
            
        </div>
    </div>
</div>
